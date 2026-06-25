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
            SELECT t.*, u.nama_lengkap, s.nisn,
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
