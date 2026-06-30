<?php

class KeuanganModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllTagihan()
    {
        $this->db->query("
            SELECT t.*, u.nama_lengkap, s.nisn, s.nama_wali, s.no_hp_wali,
                   (SELECT COALESCE(SUM(jumlah_bayar), 0) FROM pembayaran_spp WHERE tagihan_id = t.id) as total_dibayar
            FROM tagihan_spp t
            JOIN siswa s ON t.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            ORDER BY t.tahun DESC, FIELD(t.bulan, 'Desember', 'November', 'Oktober', 'September', 'Agustus', 'Juli', 'Juni', 'Mei', 'April', 'Maret', 'Februari', 'Januari') DESC, u.nama_lengkap ASC
        ");
        return $this->db->resultSet();
    }

    public function getRiwayatPembayaran()
    {
        $this->db->query("
            SELECT p.*, t.bulan, t.tahun, u.nama_lengkap, s.nisn
            FROM pembayaran_spp p
            JOIN tagihan_spp t ON p.tagihan_id = t.id
            JOIN siswa s ON t.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            ORDER BY p.tanggal_bayar DESC, p.created_at DESC
        ");
        return $this->db->resultSet();
    }

    public function hapusPembayaran($id)
    {
        $this->db->query("DELETE FROM pembayaran_spp WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
    
    public function sendFonnteWA($tagihan_id) 
    {
        // Ambil token dari pengaturan
        $token = '';
        try {
            $this->db->query("SELECT fonnte_token FROM pengaturan LIMIT 1");
            $pengaturan = $this->db->single();
            $token = $pengaturan['fonnte_token'] ?? '';
        } catch (PDOException $e) {
            // Self healing jika kolom belum ada
            if(strpos($e->getMessage(), 'Unknown column') !== false) {
                $db_heal = new Database();
                $db_heal->query("ALTER TABLE pengaturan ADD COLUMN fonnte_token VARCHAR(255) NULL DEFAULT NULL AFTER logo_sekolah");
                $db_heal->execute();
                
                $this->db->query("SELECT fonnte_token FROM pengaturan LIMIT 1");
                $pengaturan = $this->db->single();
                $token = $pengaturan['fonnte_token'] ?? '';
            }
        }
        
        if(empty($token)) return false;
        
        // Ambil detail tagihan dan siswa
        $this->db->query("
            SELECT t.bulan, t.tahun, t.nominal, s.nama_wali, s.no_hp_wali, s.nisn, u.nama_lengkap 
            FROM tagihan_spp t 
            JOIN siswa s ON t.siswa_id = s.id 
            JOIN users u ON s.user_id = u.id 
            WHERE t.id = :tagihan_id
        ");
        $this->db->bind('tagihan_id', $tagihan_id);
        $tagihan = $this->db->single();
        
        if(!$tagihan || empty($tagihan['no_hp_wali'])) return false;
        
        $no_hp = preg_replace('/[^0-9]/', '', $tagihan['no_hp_wali']);
        if (substr($no_hp, 0, 1) == '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }
        $nominal = number_format($tagihan['nominal'], 0, ',', '.');
        $pesan = "Halo Bapak/Ibu {$tagihan['nama_wali']},\n\nKami menginformasikan bahwa pembayaran SPP atas nama:\nNama: {$tagihan['nama_lengkap']}\nNISN: {$tagihan['nisn']}\nBulan: {$tagihan['bulan']} {$tagihan['tahun']}\nSebesar: Rp {$nominal}\n\nTelah *LUNAS*.\nTerima kasih.";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15, // Ditingkatkan agar fonnte punya waktu proses
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0, // Disable SSL Verify untuk server hosting yang usang
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $no_hp,
                'message' => $pesan
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }

    public function getTahunPembayaran()
    {
        $this->db->query("SELECT DISTINCT t.tahun FROM tagihan_spp t JOIN pembayaran_spp p ON p.tagihan_id = t.id ORDER BY t.tahun DESC");
        return $this->db->resultSet();
    }

    public function getRiwayatPembayaranBySiswa($tahun)
    {
        $whereClause = "";
        if ($tahun !== 'semua') {
            $whereClause = "WHERE t.tahun = :tahun";
        }
        
        $this->db->query("
            SELECT s.id as siswa_id, u.nama_lengkap, s.nisn, 
                   t.bulan, t.tahun, p.tanggal_bayar, p.jumlah_bayar, p.metode, p.keterangan, p.created_at
            FROM pembayaran_spp p
            JOIN tagihan_spp t ON p.tagihan_id = t.id
            JOIN siswa s ON t.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            $whereClause
            ORDER BY u.nama_lengkap ASC, p.tanggal_bayar DESC, p.created_at DESC
        ");
        
        if ($tahun !== 'semua') {
            $this->db->bind('tahun', $tahun);
        }
        
        $results = $this->db->resultSet();
        
        $grouped = [];
        foreach($results as $row) {
            $siswa_id = $row['siswa_id'];
            if(!isset($grouped[$siswa_id])) {
                $grouped[$siswa_id] = [
                    'nama_lengkap' => $row['nama_lengkap'],
                    'nisn' => $row['nisn'],
                    'total_pembayaran' => 0,
                    'pembayaran' => []
                ];
            }
            $grouped[$siswa_id]['total_pembayaran'] += $row['jumlah_bayar'];
            $grouped[$siswa_id]['pembayaran'][] = [
                'bulan' => $row['bulan'],
                'tahun' => $row['tahun'],
                'tanggal_bayar' => $row['tanggal_bayar'],
                'jumlah_bayar' => $row['jumlah_bayar'],
                'metode' => $row['metode'],
                'keterangan' => $row['keterangan'],
                'created_at' => $row['created_at']
            ];
        }
        
        return array_values($grouped);
    }

    public function generateTagihanMasal($data)
    {
        // Ambil semua siswa
        $this->db->query("SELECT id FROM siswa");
        $siswa = $this->db->resultSet();
        
        $inserted = 0;
        foreach($siswa as $s) {
            // Cek apakah tagihan untuk siswa ini di bulan dan tahun tsb sudah ada
            $this->db->query("SELECT id FROM tagihan_spp WHERE siswa_id = :siswa_id AND bulan = :bulan AND tahun = :tahun");
            $this->db->bind('siswa_id', $s['id']);
            $this->db->bind('bulan', $data['bulan']);
            $this->db->bind('tahun', $data['tahun']);
            $this->db->single();
            
            if($this->db->rowCount() == 0) {
                // Buat tagihan baru
                $this->db->query("INSERT INTO tagihan_spp (siswa_id, bulan, tahun, nominal, jatuh_tempo) VALUES (:siswa_id, :bulan, :tahun, :nominal, :jatuh_tempo)");
                $this->db->bind('siswa_id', $s['id']);
                $this->db->bind('bulan', $data['bulan']);
                $this->db->bind('tahun', $data['tahun']);
                $this->db->bind('nominal', $data['nominal']);
                $this->db->bind('jatuh_tempo', $data['jatuh_tempo']);
                $this->db->execute();
                $inserted++;
            }
        }
        return $inserted;
    }

    public function prosesPembayaran($data)
    {
        // Insert history pembayaran
        $this->db->query("INSERT INTO pembayaran_spp (tagihan_id, tanggal_bayar, jumlah_bayar, metode, keterangan) VALUES (:tagihan_id, :tanggal_bayar, :jumlah_bayar, :metode, :keterangan)");
        $this->db->bind('tagihan_id', $data['tagihan_id']);
        $this->db->bind('tanggal_bayar', date('Y-m-d'));
        $this->db->bind('jumlah_bayar', $data['jumlah_bayar']);
        $this->db->bind('metode', $data['metode']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->execute();
        
        $rowCount = $this->db->rowCount();
        
        // Update status tagihan (cek jika sudah lunas)
        $this->db->query("SELECT nominal, siswa_id, bulan, tahun FROM tagihan_spp WHERE id = :tagihan_id");
        $this->db->bind('tagihan_id', $data['tagihan_id']);
        $tagihan = $this->db->single();
        
        $this->db->query("SELECT SUM(jumlah_bayar) as total_bayar FROM pembayaran_spp WHERE tagihan_id = :tagihan_id");
        $this->db->bind('tagihan_id', $data['tagihan_id']);
        $pembayaran = $this->db->single();
        
        if($pembayaran['total_bayar'] >= $tagihan['nominal']) {
            $this->db->query("UPDATE tagihan_spp SET status = 'Lunas' WHERE id = :tagihan_id");
            $this->db->bind('tagihan_id', $data['tagihan_id']);
            $this->db->execute();
            
            // Fonnte Notification Self-Healing
            try {
                $this->sendFonnteWA($data['tagihan_id']);
            } catch (Exception $e) {
                // Silently ignore to prevent crashing the payment process
            }
        }
        
        if ($rowCount > 0) {
            // Ambil user_id siswa
            $this->db->query("SELECT user_id FROM siswa WHERE id = :siswa_id");
            $this->db->bind('siswa_id', $tagihan['siswa_id']);
            $siswa = $this->db->single();
            
            if ($siswa && $siswa['user_id']) {
                require_once 'NotifikasiModel.php';
                $notifModel = new NotifikasiModel();
                $jumlah = number_format($data['jumlah_bayar'], 0, ',', '.');
                $notifModel->createNotifikasi(
                    $siswa['user_id'], 
                    'pembayaran', 
                    "Pembayaran SPP bulan {$tagihan['bulan']} {$tagihan['tahun']} sebesar Rp {$jumlah} berhasil.", 
                    BASEURL . '/keuangan/riwayat'
                );
            }
        }
        
        return $rowCount;
    }
}
