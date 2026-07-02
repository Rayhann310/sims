<?php

class SpmbModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // ==========================================
    // GELOMBANG
    // ==========================================
    public function getGelombangAktif()
    {
        $this->db->query("SELECT * FROM spmb_gelombang WHERE status = 'Buka' AND CURRENT_DATE() BETWEEN tanggal_mulai AND tanggal_selesai ORDER BY id DESC LIMIT 1");
        return $this->db->single();
    }

    public function getAllGelombang()
    {
        $this->db->query("SELECT * FROM spmb_gelombang ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getGelombangById($id)
    {
        $this->db->query("SELECT * FROM spmb_gelombang WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahGelombang($data)
    {
        $this->db->query("INSERT INTO spmb_gelombang (nama_gelombang, tanggal_mulai, tanggal_selesai, harga_formulir, status) VALUES (:nama, :mulai, :selesai, :harga, :status)");
        $this->db->bind('nama', $data['nama_gelombang']);
        $this->db->bind('mulai', $data['tanggal_mulai']);
        $this->db->bind('selesai', $data['tanggal_selesai']);
        $this->db->bind('harga', $data['harga_formulir']);
        $this->db->bind('status', $data['status']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahGelombang($data)
    {
        $this->db->query("UPDATE spmb_gelombang SET nama_gelombang = :nama, tanggal_mulai = :mulai, tanggal_selesai = :selesai, harga_formulir = :harga, status = :status WHERE id = :id");
        $this->db->bind('nama', $data['nama_gelombang']);
        $this->db->bind('mulai', $data['tanggal_mulai']);
        $this->db->bind('selesai', $data['tanggal_selesai']);
        $this->db->bind('harga', $data['harga_formulir']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusGelombang($id)
    {
        $this->db->query("DELETE FROM spmb_gelombang WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ==========================================
    // PESERTA
    // ==========================================
    public function getAllPeserta()
    {
        $this->db->query("SELECT p.*, g.nama_gelombang, u.username as akun_username
                          FROM spmb_peserta p 
                          JOIN spmb_gelombang g ON p.gelombang_id = g.id 
                          JOIN users u ON p.user_id = u.id 
                          ORDER BY p.id DESC");
        return $this->db->resultSet();
    }

    public function getPesertaById($id)
    {
        $this->db->query("SELECT p.*, g.nama_gelombang, g.harga_formulir 
                          FROM spmb_peserta p 
                          JOIN spmb_gelombang g ON p.gelombang_id = g.id 
                          WHERE p.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getPesertaByUserId($user_id)
    {
        $this->db->query("SELECT p.*, g.nama_gelombang, g.harga_formulir 
                          FROM spmb_peserta p 
                          JOIN spmb_gelombang g ON p.gelombang_id = g.id 
                          WHERE p.user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        return $this->db->single();
    }

    public function daftarPeserta($data)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // 1. Cek username terdaftar
            $this->db->query("SELECT id FROM users WHERE username = :username");
            $this->db->bind('username', $data['nisn']);
            if($this->db->single()) {
                throw new Exception("NISN sudah terdaftar");
            }

            // 2. Insert User
            $this->db->query("INSERT INTO users (username, password, role, nama_lengkap) VALUES (:username, :password, 'siswa', :nama_lengkap)");
            $this->db->bind('username', $data['nisn']);
            // Generate Random Password (6 characters)
            $raw_password = strtoupper(substr(uniqid(), -6));

            $this->db->bind('password', password_hash($raw_password, PASSWORD_DEFAULT));
            $this->db->bind('nama_lengkap', $data['nama_lengkap']);
            $this->db->execute();

            $this->db->query("SELECT LAST_INSERT_ID() as last_id");
            $userId = $this->db->single()['last_id'];

            // 3. Insert Peserta SPMB
            $this->db->query("INSERT INTO spmb_peserta (user_id, gelombang_id, nisn, nama_lengkap, asal_sekolah, no_hp) VALUES (:user_id, :gelombang_id, :nisn, :nama_lengkap, :asal_sekolah, :no_hp)");
            $this->db->bind('user_id', $userId);
            $this->db->bind('gelombang_id', $data['gelombang_id']);
            $this->db->bind('nisn', $data['nisn']);
            $this->db->bind('nama_lengkap', $data['nama_lengkap']);
            $this->db->bind('asal_sekolah', $data['asal_sekolah']);
            $this->db->bind('no_hp', $data['no_hp']);
            $this->db->execute();

            $this->db->query("COMMIT");
            $this->db->execute();

            // Send WhatsApp Notifications
            try {
                $this->sendFonnteWARegister($data, $raw_password);
            } catch (Exception $e) {
                // Ignore WA errors so registration still succeeds
            }

            return ['status' => true, 'pesan' => 'Pendaftaran berhasil. Silakan login menggunakan NISN Anda dan Password: <strong>' . $raw_password . '</strong> (Password juga telah dikirim ke WhatsApp)'];
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return ['status' => false, 'pesan' => $e->getMessage()];
        }
    }

    public function updateStatusSeleksi($id, $status)
    {
        $this->db->query("UPDATE spmb_peserta SET status_seleksi = :status WHERE id = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function simpanBiodata($data)
    {
        $this->db->query("UPDATE spmb_peserta SET 
            tempat_lahir = :tempat_lahir,
            tanggal_lahir = :tanggal_lahir,
            alamat_lengkap = :alamat_lengkap,
            nama_ayah = :nama_ayah,
            nama_ibu = :nama_ibu,
            pekerjaan_ortu = :pekerjaan_ortu,
            penghasilan_ortu = :penghasilan_ortu,
            no_hp_ortu = :no_hp_ortu
            WHERE id = :id");
            
        $this->db->bind('tempat_lahir', $data['tempat_lahir']);
        $this->db->bind('tanggal_lahir', $data['tanggal_lahir']);
        $this->db->bind('alamat_lengkap', $data['alamat_lengkap']);
        $this->db->bind('nama_ayah', $data['nama_ayah']);
        $this->db->bind('nama_ibu', $data['nama_ibu']);
        $this->db->bind('pekerjaan_ortu', $data['pekerjaan_ortu']);
        $this->db->bind('penghasilan_ortu', $data['penghasilan_ortu']);
        $this->db->bind('no_hp_ortu', $data['no_hp_ortu']);
        $this->db->bind('id', $data['peserta_id']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ==========================================
    // PEMBAYARAN
    // ==========================================
    public function getPembayaranByPeserta($peserta_id)
    {
        $this->db->query("SELECT * FROM spmb_pembayaran WHERE peserta_id = :peserta_id ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function tambahPembayaran($data)
    {
        $this->db->query("INSERT INTO spmb_pembayaran (peserta_id, jumlah_bayar, metode, bukti) VALUES (:peserta_id, :jumlah_bayar, :metode, :bukti)");
        $this->db->bind('peserta_id', $data['peserta_id']);
        $this->db->bind('jumlah_bayar', $data['jumlah_bayar']);
        $this->db->bind('metode', $data['metode']);
        $this->db->bind('bukti', $data['bukti']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function verifikasiPembayaran($id, $status, $peserta_id)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            $this->db->query("UPDATE spmb_pembayaran SET status = :status WHERE id = :id");
            $this->db->bind('status', $status);
            $this->db->bind('id', $id);
            $this->db->execute();

            if ($status == 'Diterima') {
                $this->db->query("UPDATE spmb_peserta SET status_pembayaran = 'Lunas' WHERE id = :peserta_id");
                $this->db->bind('peserta_id', $peserta_id);
                $this->db->execute();
            } else if ($status == 'Ditolak') {
                $this->db->query("UPDATE spmb_peserta SET status_pembayaran = 'Belum Bayar' WHERE id = :peserta_id");
                $this->db->bind('peserta_id', $peserta_id);
                $this->db->execute();
            }

            $this->db->query("COMMIT");
            $this->db->execute();
            return true;
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return false;
        }
    }

    // ==========================================
    // MIGRASI KE SISWA
    // ==========================================
    public function migrasiKeSiswa($peserta_id)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            $peserta = $this->getPesertaById($peserta_id);
            
            if (!$peserta || $peserta['status_seleksi'] != 'Lulus') {
                throw new Exception("Peserta belum lulus seleksi.");
            }

            // Cek apakah sudah ada di tabel siswa
            $this->db->query("SELECT id FROM siswa WHERE user_id = :user_id");
            $this->db->bind('user_id', $peserta['user_id']);
            if($this->db->single()) {
                throw new Exception("Peserta sudah dimigrasi sebelumnya.");
            }

            $this->db->query("INSERT INTO siswa (user_id, nisn, jenis_kelamin, status) VALUES (:user_id, :nisn, 'L', 'Aktif')");
            $this->db->bind('user_id', $peserta['user_id']);
            $this->db->bind('nisn', $peserta['nisn']);
            // Jenis kelamin default L, bisa dilengkapi kemudian oleh siswa/admin
            $this->db->execute();

            $this->db->query("COMMIT");
            $this->db->execute();
            return ['status' => true, 'pesan' => 'Berhasil migrasi peserta ke data siswa.'];
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return ['status' => false, 'pesan' => $e->getMessage()];
        }
    }

    public function migrasiMassalKeSiswa($gelombang_id)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // Ambil semua peserta di gelombang ini yang lulus dan belum dimigrasi (belum ada di tabel siswa)
            $this->db->query("SELECT p.* FROM spmb_peserta p 
                              LEFT JOIN siswa s ON p.user_id = s.user_id 
                              WHERE p.gelombang_id = :gelombang_id 
                              AND p.status_seleksi = 'Lulus' 
                              AND s.id IS NULL");
            $this->db->bind('gelombang_id', $gelombang_id);
            $pesertaLulus = $this->db->resultSet();

            if (empty($pesertaLulus)) {
                throw new Exception("Tidak ada peserta yang siap dimigrasi (Mungkin belum ada yang Lulus atau sudah dimigrasi semua).");
            }

            $count = 0;
            foreach ($pesertaLulus as $p) {
                $this->db->query("INSERT INTO siswa (user_id, nisn, jenis_kelamin, status) VALUES (:user_id, :nisn, 'L', 'Aktif')");
                $this->db->bind('user_id', $p['user_id']);
                $this->db->bind('nisn', $p['nisn']);
                $this->db->execute();
                $count++;
            }

            $this->db->query("COMMIT");
            $this->db->execute();
            return ['status' => true, 'pesan' => "Berhasil migrasi $count peserta ke data siswa."];
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return ['status' => false, 'pesan' => $e->getMessage()];
        }
    }

    // ==========================================
    // BIAYA PENDAFTARAN KUSTOM (KATEGORI & RINCIAN)
    // ==========================================
    public function getAllKategoriBiaya()
    {
        $this->db->query("SELECT * FROM spmb_kategori_biaya ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function getKategoriBiayaById($id)
    {
        $this->db->query("SELECT * FROM spmb_kategori_biaya WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getRincianBiayaByKategori($kategori_id)
    {
        $this->db->query("SELECT * FROM spmb_rincian_biaya WHERE kategori_id = :kategori_id ORDER BY id ASC");
        $this->db->bind('kategori_id', $kategori_id);
        return $this->db->resultSet();
    }

    public function tambahKategoriBiaya($data)
    {
        $this->db->query("INSERT INTO spmb_kategori_biaya (nama_kategori, deskripsi) VALUES (:nama_kategori, :deskripsi)");
        $this->db->bind('nama_kategori', $data['nama_kategori']);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKategoriBiaya($id)
    {
        $this->db->query("DELETE FROM spmb_kategori_biaya WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function tambahRincianBiaya($data)
    {
        $this->db->query("INSERT INTO spmb_rincian_biaya (kategori_id, nama_rincian, nominal) VALUES (:kategori_id, :nama_rincian, :nominal)");
        $this->db->bind('kategori_id', $data['kategori_id']);
        $this->db->bind('nama_rincian', $data['nama_rincian']);
        $this->db->bind('nominal', $data['nominal']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusRincianBiaya($id)
    {
        $this->db->query("DELETE FROM spmb_rincian_biaya WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    private function sendFonnteWARegister($data, $raw_password)
    {
        // 1. Ambil token dari pengaturan
        $this->db->query("SELECT fonnte_token FROM pengaturan LIMIT 1");
        $pengaturan = $this->db->single();
        $token = $pengaturan['fonnte_token'] ?? '';

        if(empty($token)) return false;

        // 2. Siapkan Data
        $no_hp_user = preg_replace('/[^0-9]/', '', $data['no_hp']);
        if (substr($no_hp_user, 0, 1) == '0') {
            $no_hp_user = '62' . substr($no_hp_user, 1);
        }

        $no_hp_admin = '6289684164091';

        // Pesan untuk Pendaftar / Orang Tua
        $pesan_user = "Halo {$data['nama_lengkap']},\n\nTerima kasih telah mendaftar. Data pendaftaran Anda sedang kami proses.\n\nBerikut adalah akses login Anda:\n*NISN:* {$data['nisn']}\n*Password:* {$raw_password}\n\nSilakan login ke portal SPMB untuk melengkapi biodata dan memantau status kelulusan Anda.\n\nTerima kasih.";

        // Pesan untuk Admin
        $pesan_admin = "Halo Admin,\n\nAda pendaftar SPMB baru masuk!\n\n*Nama:* {$data['nama_lengkap']}\n*NISN:* {$data['nisn']}\n*Asal Sekolah:* {$data['asal_sekolah']}\n*No HP:* {$data['no_hp']}\n\nMohon segera dicek pada dashboard admin.";

        // 3. Eksekusi cURL untuk User
        $this->executeFonnte($no_hp_user, $pesan_user, $token);

        // 4. Eksekusi cURL untuk Admin
        $this->executeFonnte($no_hp_admin, $pesan_admin, $token);

        return true;
    }

    private function executeFonnte($no_hp, $pesan, $token)
    {
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
        } catch(Exception $e) {
            // Ignore insert errors to log_fonnte if table doesn't exist
        }
    }
}
