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
                       COALESCE((SELECT SUM(c2.poin_dicatat) FROM catatan_kedisiplinan c2 JOIN kategori_kedisiplinan kat2 ON c2.kategori_id = kat2.id WHERE c2.siswa_id = s.id AND kat2.jenis = 'Penghargaan'), 0)
                       -
                       COALESCE((SELECT SUM(c1.poin_dicatat) FROM catatan_kedisiplinan c1 JOIN kategori_kedisiplinan kat1 ON c1.kategori_id = kat1.id WHERE c1.siswa_id = s.id AND kat1.jenis = 'Pelanggaran'), 0)
                   ) as total_poin
            FROM siswa s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN anggota_rombel ar ON s.id = ar.siswa_id
            LEFT JOIN rombel r ON ar.rombel_id = r.id
            LEFT JOIN kelas k ON r.kelas_id = k.id
            GROUP BY s.id
            ORDER BY total_poin DESC, u.nama_lengkap ASC
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
            $this->db->query("SELECT user_id FROM siswa WHERE id = :siswa_id");
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
            }
        }
        
        return $rowCount;
    }
}
