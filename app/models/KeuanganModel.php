<?php

class KeuanganModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        // Buat tabel master tarif jika belum ada
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS `keuangan_kategori` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nama_kategori` varchar(100) NOT NULL,
                `tipe` enum('Bulanan','Sekali') NOT NULL DEFAULT 'Bulanan',
                `nominal_default` decimal(15,2) NOT NULL DEFAULT '0.00',
                `keterangan` text DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->execute();
            
            // Tambahkan kolom kategori_id ke tagihan_spp untuk relasi jenis tagihan
            $this->db->query("ALTER TABLE tagihan_spp ADD COLUMN kategori_id INT(11) NULL DEFAULT NULL AFTER siswa_id");
            $this->db->execute();
        } catch (Throwable $e) {
            // Abaikan jika error (kolom sudah ada)
        }

        // Buat tabel Buku Kas Umum
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS `keuangan_kas` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
                `sumber` varchar(100) NOT NULL,
                `tanggal` date NOT NULL,
                `nominal` decimal(15,2) NOT NULL DEFAULT '0.00',
                `keterangan` text DEFAULT NULL,
                `referensi_id` int(11) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->execute();

            // Migrasi otomatis: Pindahkan semua data pembayaran SPP lama ke keuangan_kas jika belum ada
            $this->db->query("
                INSERT INTO keuangan_kas (jenis, sumber, tanggal, nominal, keterangan, referensi_id, created_at)
                SELECT 'Pemasukan', 'Pembayaran SPP', p.tanggal_bayar, p.jumlah_bayar, p.keterangan, p.id, p.created_at
                FROM pembayaran_spp p
                WHERE NOT EXISTS (
                    SELECT 1 FROM keuangan_kas k WHERE k.referensi_id = p.id AND k.sumber = 'Pembayaran SPP'
                )
            ");
            $this->db->execute();
        } catch (Throwable $e) {
            // Abaikan
        }
    }

    // CRUD Master Tarif
    public function getAllKategori()
    {
        $this->db->query("SELECT * FROM keuangan_kategori ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function tambahKategori($data)
    {
        $this->db->query("INSERT INTO keuangan_kategori (nama_kategori, tipe, nominal_default, keterangan) VALUES (:nama, :tipe, :nominal, :keterangan)");
        $this->db->bind('nama', $data['nama_kategori']);
        $this->db->bind('tipe', $data['tipe']);
        $this->db->bind('nominal', $data['nominal_default']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahKategori($data)
    {
        $this->db->query("UPDATE keuangan_kategori SET nama_kategori = :nama, tipe = :tipe, nominal_default = :nominal, keterangan = :keterangan WHERE id = :id");
        $this->db->bind('nama', $data['nama_kategori']);
        $this->db->bind('tipe', $data['tipe']);
        $this->db->bind('nominal', $data['nominal_default']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKategori($id)
    {
        $this->db->query("DELETE FROM keuangan_kategori WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAllTagihan()
    {
        $this->db->query("
            SELECT t.*, u.nama_lengkap, s.nisn, s.nama_wali, s.no_hp_wali,
                   k.nama_kategori, k.tipe,
                   (SELECT COALESCE(SUM(jumlah_bayar), 0) FROM pembayaran_spp WHERE tagihan_id = t.id) as total_dibayar
            FROM tagihan_spp t
            JOIN siswa s ON t.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            LEFT JOIN keuangan_kategori k ON t.kategori_id = k.id
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
        // Hapus dari buku kas terlebih dahulu (referensi_id)
        $this->db->query("DELETE FROM keuangan_kas WHERE referensi_id = :id AND sumber = 'Pembayaran SPP'");
        $this->db->bind('id', $id);
        $this->db->execute();

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
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        $status = ($httpcode >= 200 && $httpcode < 300) ? 'Sukses' : 'Gagal';
        $response_body = $response ?: $error;
        
        try {
            $this->db->query("INSERT INTO log_fonnte (nomor_tujuan, pesan, response_code, response_body, status) VALUES (:no, :pesan, :code, :body, :status)");
            $this->db->bind('no', $no_hp);
            $this->db->bind('pesan', $pesan);
            $this->db->bind('code', $httpcode);
            $this->db->bind('body', $response_body);
            $this->db->bind('status', $status);
            $this->db->execute();
        } catch(PDOException $e) {
            if(strpos($e->getMessage(), 'Table') !== false && strpos($e->getMessage(), 'doesn\'t exist') !== false) {
                $db_heal = new Database();
                $db_heal->query("CREATE TABLE `log_fonnte` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
                  `nomor_tujuan` varchar(20) NOT NULL,
                  `pesan` text NOT NULL,
                  `response_code` int(11) DEFAULT NULL,
                  `response_body` text DEFAULT NULL,
                  `status` varchar(50) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
                $db_heal->execute();
                
                $this->db->execute();
            }
        }
        
        return $response;
    }

    public function sendFonnteTagihanWA($tagihan_id) 
    {
        // Ambil token dari pengaturan
        $token = '';
        try {
            $this->db->query("SELECT fonnte_token FROM pengaturan LIMIT 1");
            $pengaturan = $this->db->single();
            $token = $pengaturan['fonnte_token'] ?? '';
        } catch (PDOException $e) {
            return false;
        }
        
        if(empty($token)) return false;
        
        // Ambil detail tagihan dan siswa
        $this->db->query("
            SELECT t.bulan, t.tahun, t.nominal, s.nama_wali, s.no_hp_wali, s.nisn, u.nama_lengkap, k.nama_kategori,
            (SELECT COALESCE(SUM(jumlah_bayar), 0) FROM pembayaran_spp WHERE tagihan_id = t.id) as total_dibayar
            FROM tagihan_spp t 
            JOIN siswa s ON t.siswa_id = s.id 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN keuangan_kategori k ON t.kategori_id = k.id
            WHERE t.id = :tagihan_id
        ");
        $this->db->bind('tagihan_id', $tagihan_id);
        $tagihan = $this->db->single();
        
        if(!$tagihan || empty($tagihan['no_hp_wali'])) return false;
        
        $sisa = $tagihan['nominal'] - $tagihan['total_dibayar'];
        if ($sisa <= 0) return false;

        $no_hp = preg_replace('/[^0-9]/', '', $tagihan['no_hp_wali']);
        if (substr($no_hp, 0, 1) == '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }
        
        $jenis = !empty($tagihan['nama_kategori']) ? $tagihan['nama_kategori'] : 'SPP Bulanan';
        $sisa_rp = number_format($sisa, 0, ',', '.');
        $nominal_rp = number_format($tagihan['nominal'], 0, ',', '.');
        
        $pesan = "Assalamu'alaikum Bapak/Ibu {$tagihan['nama_wali']},\n\n";
        $pesan .= "Mohon maaf mengganggu waktunya. Kami dari bagian Keuangan SMA Nahdlatul Wathan Jakarta bermaksud menginformasikan rincian tagihan administrasi ananda:\n\n";
        $pesan .= "Nama: {$tagihan['nama_lengkap']}\n";
        $pesan .= "NISN: {$tagihan['nisn']}\n";
        $pesan .= "Jenis: {$jenis} - {$tagihan['bulan']} {$tagihan['tahun']}\n";
        $pesan .= "Total Tagihan: Rp {$nominal_rp}\n";
        $pesan .= "Sisa Belum Dibayar: *Rp {$sisa_rp}*\n\n";
        $pesan .= "Mohon perkenannya untuk melakukan pembayaran sesuai nominal di atas. Jika Bapak/Ibu sudah melakukan pembayaran, mohon abaikan pesan ini atau konfirmasikan bukti transfer kepada kami.\n\n";
        $pesan .= "Terima kasih banyak atas perhatian dan kerja samanya. Semoga sehat selalu.\n\n";
        $pesan .= "Wassalamu'alaikum Warahmatullahi Wabarakatuh.";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
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
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        $status = ($httpcode >= 200 && $httpcode < 300) ? 'Sukses' : 'Gagal';
        $response_body = $response ?: $error;
        
        try {
            $this->db->query("INSERT INTO log_fonnte (nomor_tujuan, pesan, response_code, response_body, status) VALUES (:no, :pesan, :code, :body, :status)");
            $this->db->bind('no', $no_hp);
            $this->db->bind('pesan', $pesan);
            $this->db->bind('code', $httpcode);
            $this->db->bind('body', $response_body);
            $this->db->bind('status', $status);
            $this->db->execute();
        } catch (Exception $e) {
        }
        
        return $status == 'Sukses';
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
            // Jika spesifik kategori, cek juga kategorinya
            if (!empty($data['kategori_id'])) {
                $this->db->query("SELECT id FROM tagihan_spp WHERE siswa_id = :siswa_id AND bulan = :bulan AND tahun = :tahun AND kategori_id = :kategori_id");
                $this->db->bind('kategori_id', $data['kategori_id']);
            } else {
                $this->db->query("SELECT id FROM tagihan_spp WHERE siswa_id = :siswa_id AND bulan = :bulan AND tahun = :tahun AND kategori_id IS NULL");
            }
            $this->db->bind('siswa_id', $s['id']);
            $this->db->bind('bulan', $data['bulan']);
            $this->db->bind('tahun', $data['tahun']);
            $this->db->single();
            
            if($this->db->rowCount() == 0) {
                // Buat tagihan baru
                $this->db->query("INSERT INTO tagihan_spp (siswa_id, kategori_id, bulan, tahun, nominal, jatuh_tempo) VALUES (:siswa_id, :kategori_id, :bulan, :tahun, :nominal, :jatuh_tempo)");
                $this->db->bind('siswa_id', $s['id']);
                $this->db->bind('kategori_id', !empty($data['kategori_id']) ? $data['kategori_id'] : null);
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
        
        $pembayaran_id = $this->db->lastInsertId();
        $rowCount = $this->db->rowCount();

        if ($rowCount > 0) {
            // Catat ke Buku Kas Umum sebagai Pemasukan
            $this->db->query("INSERT INTO keuangan_kas (jenis, sumber, tanggal, nominal, keterangan, referensi_id) VALUES ('Pemasukan', 'Pembayaran SPP', :tanggal, :nominal, :keterangan, :ref_id)");
            $this->db->bind('tanggal', date('Y-m-d'));
            $this->db->bind('nominal', $data['jumlah_bayar']);
            $this->db->bind('keterangan', 'SPP Ref: ' . $pembayaran_id . ($data['keterangan'] ? ' - ' . $data['keterangan'] : ''));
            $this->db->bind('ref_id', $pembayaran_id);
            $this->db->execute();
        }
        
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

    public function batalBayarTagihan($tagihan_id)
    {
        // 1. Ambil detail tagihan dan pembayaran
        $this->db->query("
            SELECT t.bulan, t.tahun, t.nominal, s.nama_wali, s.no_hp_wali, s.nisn, u.nama_lengkap, k.nama_kategori
            FROM tagihan_spp t 
            JOIN siswa s ON t.siswa_id = s.id 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN keuangan_kategori k ON t.kategori_id = k.id
            WHERE t.id = :tagihan_id
        ");
        $this->db->bind('tagihan_id', $tagihan_id);
        $tagihan = $this->db->single();

        if (!$tagihan) return false;

        // 2. Cari semua pembayaran untuk tagihan ini dan hapus
        $this->db->query("SELECT id FROM pembayaran_spp WHERE tagihan_id = :tagihan_id");
        $this->db->bind('tagihan_id', $tagihan_id);
        $pembayaran_list = $this->db->resultSet();
        
        foreach ($pembayaran_list as $p) {
            $this->hapusPembayaran($p['id']);
        }

        // 3. Update status tagihan kembali menjadi 'Belum Lunas'
        $this->db->query("UPDATE tagihan_spp SET status = 'Belum Lunas' WHERE id = :tagihan_id");
        $this->db->bind('tagihan_id', $tagihan_id);
        $this->db->execute();

        // 4. Kirim notifikasi Fonnte WA
        $token = '';
        try {
            $this->db->query("SELECT fonnte_token FROM pengaturan LIMIT 1");
            $pengaturan = $this->db->single();
            $token = $pengaturan['fonnte_token'] ?? '';
        } catch (Exception $e) {}

        if(!empty($token) && !empty($tagihan['no_hp_wali'])) {
            $no_hp = preg_replace('/[^0-9]/', '', $tagihan['no_hp_wali']);
            if (substr($no_hp, 0, 1) == '0') {
                $no_hp = '62' . substr($no_hp, 1);
            }
            $jenis = !empty($tagihan['nama_kategori']) ? $tagihan['nama_kategori'] : 'SPP Bulanan';
            $nominal_rp = number_format($tagihan['nominal'], 0, ',', '.');
            
            $pesan = "Mohon maaf Bapak/Ibu {$tagihan['nama_wali']},\n\n";
            $pesan .= "Terdapat pembatalan pembayaran untuk tagihan administrasi ananda:\n\n";
            $pesan .= "Nama: {$tagihan['nama_lengkap']}\n";
            $pesan .= "NISN: {$tagihan['nisn']}\n";
            $pesan .= "Jenis: {$jenis} - {$tagihan['bulan']} {$tagihan['tahun']}\n";
            $pesan .= "Sebesar: Rp {$nominal_rp}\n\n";
            $pesan .= "Pembatalan ini dilakukan karena kesalahan sistem/input. Status tagihan kini kembali menjadi *Belum Lunas*. Mohon maaf atas ketidaknyamanannya.\n\n";
            $pesan .= "Terima kasih.";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
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
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
            
            $status = ($httpcode >= 200 && $httpcode < 300) ? 'Sukses' : 'Gagal';
            $response_body = $response ?: $error;
            
            try {
                $this->db->query("INSERT INTO log_fonnte (nomor_tujuan, pesan, response_code, response_body, status) VALUES (:no, :pesan, :code, :body, :status)");
                $this->db->bind('no', $no_hp);
                $this->db->bind('pesan', $pesan);
                $this->db->bind('code', $httpcode);
                $this->db->bind('body', $response_body);
                $this->db->bind('status', $status);
                $this->db->execute();
            } catch(Exception $e) {}
        }

        return true;
    }

    // ==========================================
    // BUKU KAS UMUM & ANALISA KEUANGAN
    // ==========================================
    
    public function getAllKas($bulan = '', $tahun = '')
    {
        $where = "";
        if (!empty($bulan) && !empty($tahun)) {
            $where = "WHERE MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun";
        } elseif (!empty($tahun)) {
            $where = "WHERE YEAR(tanggal) = :tahun";
        }

        $this->db->query("SELECT * FROM keuangan_kas $where ORDER BY tanggal DESC, id DESC");
        
        if (!empty($bulan) && !empty($tahun)) {
            $this->db->bind('bulan', $bulan);
            $this->db->bind('tahun', $tahun);
        } elseif (!empty($tahun)) {
            $this->db->bind('tahun', $tahun);
        }

        return $this->db->resultSet();
    }

    public function getStatistikKas()
    {
        $this->db->query("
            SELECT 
                SUM(CASE WHEN jenis = 'Pemasukan' THEN nominal ELSE 0 END) as total_pemasukan,
                SUM(CASE WHEN jenis = 'Pengeluaran' THEN nominal ELSE 0 END) as total_pengeluaran,
                SUM(CASE WHEN jenis = 'Pemasukan' THEN nominal ELSE -nominal END) as saldo_akhir
            FROM keuangan_kas
        ");
        $all = $this->db->single();

        $bulan_ini = date('m');
        $tahun_ini = date('Y');
        
        $this->db->query("
            SELECT 
                SUM(CASE WHEN jenis = 'Pemasukan' THEN nominal ELSE 0 END) as pemasukan_bulan_ini,
                SUM(CASE WHEN jenis = 'Pengeluaran' THEN nominal ELSE 0 END) as pengeluaran_bulan_ini
            FROM keuangan_kas
            WHERE MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun
        ");
        $this->db->bind('bulan', $bulan_ini);
        $this->db->bind('tahun', $tahun_ini);
        $month = $this->db->single();

        return array_merge($all, $month);
    }

    public function getChartData($tahun)
    {
        $this->db->query("
            SELECT 
                MONTH(tanggal) as bulan,
                SUM(CASE WHEN jenis = 'Pemasukan' THEN nominal ELSE 0 END) as pemasukan,
                SUM(CASE WHEN jenis = 'Pengeluaran' THEN nominal ELSE 0 END) as pengeluaran
            FROM keuangan_kas
            WHERE YEAR(tanggal) = :tahun
            GROUP BY MONTH(tanggal)
            ORDER BY MONTH(tanggal) ASC
        ");
        $this->db->bind('tahun', $tahun);
        $result = $this->db->resultSet();
        
        $chart = array_fill(1, 12, ['pemasukan' => 0, 'pengeluaran' => 0]);
        foreach($result as $row) {
            $chart[$row['bulan']]['pemasukan'] = $row['pemasukan'];
            $chart[$row['bulan']]['pengeluaran'] = $row['pengeluaran'];
        }
        
        return $chart;
    }

    public function tambahKas($data)
    {
        $this->db->query("INSERT INTO keuangan_kas (jenis, sumber, tanggal, nominal, keterangan) VALUES (:jenis, :sumber, :tanggal, :nominal, :keterangan)");
        $this->db->bind('jenis', $data['jenis']);
        $this->db->bind('sumber', $data['sumber']);
        $this->db->bind('tanggal', $data['tanggal']);
        $this->db->bind('nominal', $data['nominal']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKas($id)
    {
        // Cegah hapus manual jika itu dari SPP (harus hapus dari riwayat pembayaran SPP)
        $this->db->query("DELETE FROM keuangan_kas WHERE id = :id AND (sumber != 'Pembayaran SPP' OR referensi_id IS NULL)");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
