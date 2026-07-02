<?php

class KedisiplinanModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // -- Kategori --
    public function getAllKategori()
    {
        $this->db->query("SELECT * FROM kategori_kedisiplinan ORDER BY jenis ASC, tingkatan ASC, poin DESC");
        return $this->db->resultSet();
    }

    public function tambahKategori($data)
    {
        $this->db->query("INSERT INTO kategori_kedisiplinan (nama_kategori, jenis, tingkatan, poin) VALUES (:nama_kategori, :jenis, :tingkatan, :poin)");
        $this->db->bind('nama_kategori', $data['nama_kategori']);
        $this->db->bind('jenis', $data['jenis']);
        $this->db->bind('tingkatan', $data['tingkatan']);
        $this->db->bind('poin', $data['poin']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKategori($id)
    {
        $this->db->query("DELETE FROM kategori_kedisiplinan WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // -- Siswa & Rekap --
    public function getRekapSiswa()
    {
        // Calculate total points: sum of Pelanggaran - sum of Penghargaan
        $this->db->query("
            SELECT s.id, s.nisn as nis, u.nama_lengkap, k.nama_kelas, r.nama_rombel,
                   (
                       200 +
                       COALESCE((SELECT SUM(c2.poin_dicatat) FROM catatan_kedisiplinan c2 JOIN kategori_kedisiplinan kat2 ON c2.kategori_id = kat2.id WHERE c2.siswa_id = s.id AND kat2.jenis = 'Penghargaan'), 0)
                       -
                       COALESCE((SELECT SUM(c1.poin_dicatat) FROM catatan_kedisiplinan c1 JOIN kategori_kedisiplinan kat1 ON c1.kategori_id = kat1.id WHERE c1.siswa_id = s.id AND kat1.jenis = 'Pelanggaran'), 0)
                   ) as total_poin
            FROM siswa s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN anggota_rombel ar ON s.id = ar.siswa_id
            LEFT JOIN rombel r ON ar.rombel_id = r.id
            LEFT JOIN kelas k ON r.kelas_id = k.id
            WHERE s.status = 'Aktif'
            GROUP BY s.id
            ORDER BY k.nama_kelas ASC, total_poin DESC, u.nama_lengkap ASC
        ");
        return $this->db->resultSet();
    }

    // -- Catatan & Riwayat --
    public function getRiwayatBySiswa($siswa_id)
    {
        $this->db->query("
            SELECT c.*, k.nama_kategori, k.jenis, k.tingkatan, u.nama_lengkap as pencatat
            FROM catatan_kedisiplinan c
            JOIN kategori_kedisiplinan k ON c.kategori_id = k.id
            JOIN users u ON c.dicatat_oleh = u.id
            WHERE c.siswa_id = :siswa_id
            ORDER BY c.tanggal DESC, c.created_at DESC
        ");
        $this->db->bind('siswa_id', $siswa_id);
        return $this->db->resultSet();
    }

    public function tambahCatatan($data)
    {
        // Get category info
        $this->db->query("SELECT poin, jenis, nama_kategori FROM kategori_kedisiplinan WHERE id = :id");
        $this->db->bind('id', $data['kategori_id']);
        $kategori = $this->db->single();
        
        $poin = isset($data['poin_kustom']) && $data['poin_kustom'] !== '' ? $data['poin_kustom'] : $kategori['poin'];

        $this->db->query("INSERT INTO catatan_kedisiplinan (siswa_id, kategori_id, tanggal, poin_dicatat, keterangan, dicatat_oleh) VALUES (:siswa_id, :kategori_id, :tanggal, :poin_dicatat, :keterangan, :dicatat_oleh)");
        $this->db->bind('siswa_id', $data['siswa_id']);
        $this->db->bind('kategori_id', $data['kategori_id']);
        $this->db->bind('tanggal', $data['tanggal']);
        $this->db->bind('poin_dicatat', $poin);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('dicatat_oleh', $_SESSION['user']['id']);
        $this->db->execute();
        
        $rowCount = $this->db->rowCount();
        
        // Buat notifikasi ke siswa
        if ($rowCount > 0) {
            $this->db->query("SELECT s.user_id, s.nama_wali, s.no_hp_wali, s.nisn, u.nama_lengkap FROM siswa s JOIN users u ON s.user_id = u.id WHERE s.id = :siswa_id");
            $this->db->bind('siswa_id', $data['siswa_id']);
            $siswa = $this->db->single();
            
            if ($siswa && $siswa['user_id']) {
                require_once 'NotifikasiModel.php';
                $notifModel = new NotifikasiModel();
                $jenis_text = $kategori['jenis'] == 'Pelanggaran' ? 'Pelanggaran baru dicatat' : 'Penghargaan baru diberikan';
                $notifModel->createNotifikasi(
                    $siswa['user_id'], 
                    'kedisiplinan', 
                    "{$jenis_text}: {$kategori['nama_kategori']} (Poin: {$poin})", 
                    BASEURL . '/kedisiplinan/riwayatSaya'
                );
                
                // Trigger WA ke Orang Tua
                $this->sendFonnteWAKedisiplinan($siswa, $kategori, $poin, $data['keterangan']);
            }
        }
        
        return $rowCount;
    }
    
    private function sendFonnteWAKedisiplinan($siswa, $kategori, $poin, $keterangan)
    {
        // Ambil token
        $token = '';
        try {
            $this->db->query("SELECT fonnte_token FROM pengaturan LIMIT 1");
            $token = $this->db->single()['fonnte_token'] ?? '';
        } catch (PDOException $e) {
            return false;
        }
        
        if(empty($token) || empty($siswa['no_hp_wali'])) return false;
        
        $no_hp = preg_replace('/[^0-9]/', '', $siswa['no_hp_wali']);
        if (substr($no_hp, 0, 1) == '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }
        
        if($kategori['jenis'] == 'Penghargaan') {
            $pesan = "Halo Bapak/Ibu {$siswa['nama_wali']},\n\nKami menginformasikan bahwa ananda *{$siswa['nama_lengkap']}* (NISN: {$siswa['nisn']}) baru saja mendapatkan *Penghargaan Kedisiplinan* di sekolah.\n\nDetail Penghargaan:\n- Kategori: {$kategori['nama_kategori']}\n- Keterangan: {$keterangan}\n- Poin Ditambahkan: +{$poin}\n\nSaat ini total poin kedisiplinan ananda bertambah. Terima kasih atas dukungan dan bimbingan Bapak/Ibu di rumah.";
        } else {
            $pesan = "Halo Bapak/Ibu {$siswa['nama_wali']},\n\nMohon maaf, kami menginformasikan bahwa ananda *{$siswa['nama_lengkap']}* (NISN: {$siswa['nisn']}) baru saja melakukan *Pelanggaran Kedisiplinan* di sekolah.\n\nDetail Pelanggaran:\n- Kategori: {$kategori['nama_kategori']}\n- Keterangan: {$keterangan}\n- Poin Dikurangi: -{$poin}\n\nKami mohon bantuan Bapak/Ibu untuk memberikan bimbingan lebih lanjut di rumah. Terima kasih.";
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
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
        } catch(PDOException $e) {
            // Abaikan jika tabel log belum ada, self-healing di handle Keuangan
        }
        
        return true;
    }
}
