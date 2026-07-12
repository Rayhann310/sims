<?php

class AkademikModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // ==========================================
    // TAHUN AKADEMIK
    // ==========================================
    public function getAllTahun()
    {
        $this->db->query("SELECT * FROM tahun_akademik ORDER BY nama_tahun DESC, semester DESC");
        return $this->db->resultSet();
    }

    public function getTahunAktif()
    {
        $this->db->query("SELECT * FROM tahun_akademik WHERE status = 'Aktif' LIMIT 1");
        return $this->db->single();
    }

    public function tambahTahun($data)
    {
        $this->db->query("INSERT INTO tahun_akademik (nama_tahun, semester, status) VALUES (:nama_tahun, :semester, 'Tidak Aktif')");
        $this->db->bind('nama_tahun', $data['nama_tahun']);
        $this->db->bind('semester', $data['semester']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusTahun($id)
    {
        $this->db->query("DELETE FROM tahun_akademik WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function setAktifTahun($id)
    {
        // Nonaktifkan semua dulu
        $this->db->query("UPDATE tahun_akademik SET status = 'Tidak Aktif'");
        $this->db->execute();

        // Aktifkan yang dipilih
        $this->db->query("UPDATE tahun_akademik SET status = 'Aktif' WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ==========================================
    // MATA PELAJARAN
    // ==========================================
    public function getAllMapel()
    {
        $this->db->query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
        return $this->db->resultSet();
    }

    public function tambahMapel($data)
    {
        $this->db->query("INSERT INTO mata_pelajaran (kode_mapel, nama_mapel) VALUES (:kode_mapel, :nama_mapel)");
        $this->db->bind('kode_mapel', $data['kode_mapel']);
        $this->db->bind('nama_mapel', $data['nama_mapel']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusMapel($id)
    {
        $this->db->query("DELETE FROM mata_pelajaran WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function importMapelMassal($data)
    {
        $inserted = 0;
        foreach($data as $row) {
            // Cek apakah kode mapel sudah ada
            $this->db->query("SELECT id FROM mata_pelajaran WHERE kode_mapel = :kode_mapel");
            $this->db->bind('kode_mapel', $row['kode_mapel']);
            $this->db->single();
            if($this->db->rowCount() == 0) {
                $this->db->query("INSERT INTO mata_pelajaran (kode_mapel, nama_mapel, kategori) VALUES (:kode_mapel, :nama_mapel, :kategori)");
                $this->db->bind('kode_mapel', $row['kode_mapel']);
                $this->db->bind('nama_mapel', $row['nama_mapel']);
                $this->db->bind('kategori', $row['kategori']);
                $this->db->execute();
                $inserted += $this->db->rowCount();
            }
        }
        return $inserted;
    }

    // ==========================================
    // KELAS (MASTER TINGKAT)
    // ==========================================
    public function getAllKelas()
    {
        $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC, jurusan ASC");
        return $this->db->resultSet();
    }

    public function tambahKelas($data)
    {
        $this->db->query("INSERT INTO kelas (nama_kelas, jurusan) VALUES (:nama_kelas, :jurusan)");
        $this->db->bind('nama_kelas', $data['nama_kelas']);
        $this->db->bind('jurusan', $data['jurusan']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKelas($id)
    {
        $this->db->query("DELETE FROM kelas WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function editKelas($data)
    {
        $this->db->query("UPDATE kelas SET nama_kelas = :nama_kelas, jurusan = :jurusan WHERE id = :id");
        $this->db->bind('nama_kelas', $data['nama_kelas']);
        $this->db->bind('jurusan', $data['jurusan']);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ==========================================
    // MASTER JURUSAN
    // ==========================================
    public function getAllJurusan()
    {
        $this->db->query("SELECT * FROM master_jurusan ORDER BY nama_jurusan ASC");
        return $this->db->resultSet();
    }

    public function tambahJurusan($data)
    {
        $this->db->query("INSERT INTO master_jurusan (nama_jurusan) VALUES (:nama_jurusan)");
        $this->db->bind('nama_jurusan', $data['nama_jurusan']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function editJurusan($data)
    {
        $this->db->query("UPDATE master_jurusan SET nama_jurusan = :nama_jurusan WHERE id = :id");
        $this->db->bind('nama_jurusan', $data['nama_jurusan']);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusJurusan($id)
    {
        $this->db->query("DELETE FROM master_jurusan WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
