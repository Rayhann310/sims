<?php

class EraporModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function getKepalaSekolahName()
    {
        $this->db->query("
            SELECT u.nama_lengkap
            FROM guru g
            JOIN users u ON g.user_id = u.id
            JOIN guru_jabatan gj ON gj.guru_id = g.id
            JOIN jabatan j ON j.id = gj.jabatan_id
            WHERE j.nama_jabatan LIKE '%Kepala Sekolah%'
            LIMIT 1
        ");
        $res = $this->db->single();
        return $res ? $res['nama_lengkap'] : null;
    }

    public function getMapelByGuru($guru_id, $tahun_akademik_id)
    {
        $this->db->query("
            SELECT DISTINCT m.id, m.nama_mapel, r.id as rombel_id, r.nama_rombel, k.nama_kelas 
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            JOIN rombel r ON jp.rombel_id = r.id
            JOIN kelas k ON r.kelas_id = k.id
            WHERE jp.guru_id = :guru_id AND r.tahun_akademik_id = :tahun_id
            ORDER BY r.nama_rombel ASC, m.nama_mapel ASC
        ");
        $this->db->bind('guru_id', $guru_id);
        $this->db->bind('tahun_id', $tahun_akademik_id);
        return $this->db->resultSet();
    }

    public function getSiswaByRombel($rombel_id)
    {
        $this->db->query("
            SELECT s.*, u.nama_lengkap 
            FROM siswa s
            JOIN anggota_rombel ar ON s.id = ar.siswa_id
            JOIN users u ON s.user_id = u.id
            WHERE ar.rombel_id = :rombel_id
            ORDER BY u.nama_lengkap ASC
        ");
        $this->db->bind('rombel_id', $rombel_id);
        return $this->db->resultSet();
    }

    public function getNilaiRaporBySiswa($siswa_id, $mapel_id, $tahun_akademik_id)
    {
        $this->db->query("SELECT * FROM nilai_rapor WHERE siswa_id = :siswa_id AND mapel_id = :mapel_id AND tahun_akademik_id = :tahun_id");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('mapel_id', $mapel_id);
        $this->db->bind('tahun_id', $tahun_akademik_id);
        return $this->db->single();
    }

    public function saveNilaiRapor($siswa_id, $mapel_id, $guru_id, $tahun_id, $nilai, $sikap)
    {
        $this->db->query("
            INSERT INTO nilai_rapor (siswa_id, mapel_id, guru_id, tahun_akademik_id, nilai, sikap)
            VALUES (:siswa_id, :mapel_id, :guru_id, :tahun_id, :nilai, :sikap)
            ON DUPLICATE KEY UPDATE
                nilai = :nilai_upd,
                sikap = :sikap_upd
        ");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('mapel_id', $mapel_id);
        $this->db->bind('guru_id', $guru_id);
        $this->db->bind('tahun_id', $tahun_id);
        $this->db->bind('nilai', $nilai);
        $this->db->bind('sikap', $sikap);
        $this->db->bind('nilai_upd', $nilai);
        $this->db->bind('sikap_upd', $sikap);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getEkskulSiswa($siswa_id, $tahun_id)
    {
        $this->db->query("SELECT * FROM nilai_ekskul WHERE siswa_id = :siswa_id AND tahun_akademik_id = :tahun_id");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        return $this->db->resultSet();
    }

    public function saveEkskulSiswa($siswa_id, $tahun_id, $nama_ekskul, $nilai, $keterangan)
    {
        $this->db->query("INSERT INTO nilai_ekskul (siswa_id, tahun_akademik_id, nama_ekskul, nilai, keterangan) VALUES (:siswa_id, :tahun_id, :nama, :nilai, :ket)");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        $this->db->bind('nama', $nama_ekskul);
        $this->db->bind('nilai', $nilai);
        $this->db->bind('ket', $keterangan);
        $this->db->execute();
        return $this->db->lastInsertId();
    }
    
    public function deleteEkskul($id)
    {
        $this->db->query("DELETE FROM nilai_ekskul WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getRombelWaliKelas($guru_id, $tahun_id)
    {
        $this->db->query("SELECT * FROM rombel WHERE wali_kelas_id = :guru_id AND tahun_akademik_id = :tahun_id LIMIT 1");
        $this->db->bind('guru_id', $guru_id);
        $this->db->bind('tahun_id', $tahun_id);
        return $this->db->single();
    }

    public function getAbsensiRapor($siswa_id, $tahun_id)
    {
        $this->db->query("SELECT * FROM absensi_rapor WHERE siswa_id = :siswa_id AND tahun_akademik_id = :tahun_id");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        return $this->db->single();
    }

    public function saveAbsensiRapor($siswa_id, $tahun_id, $sakit, $izin, $alfa)
    {
        $this->db->query("
            INSERT INTO absensi_rapor (siswa_id, tahun_akademik_id, sakit, izin, alfa)
            VALUES (:siswa_id, :tahun_id, :sakit, :izin, :alfa)
            ON DUPLICATE KEY UPDATE
                sakit = :sakit_upd,
                izin = :izin_upd,
                alfa = :alfa_upd
        ");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        $this->db->bind('sakit', $sakit);
        $this->db->bind('izin', $izin);
        $this->db->bind('alfa', $alfa);
        $this->db->bind('sakit_upd', $sakit);
        $this->db->bind('izin_upd', $izin);
        $this->db->bind('alfa_upd', $alfa);
        
        $this->db->execute();
        return $this->db->rowCount();
    }


    public function getCatatanWali($siswa_id, $tahun_id)
    {
        $this->db->query("SELECT * FROM catatan_wali WHERE siswa_id = :siswa_id AND tahun_akademik_id = :tahun_id");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        return $this->db->single();
    }

    public function saveCatatanWali($siswa_id, $tahun_id, $catatan)
    {
        $this->db->query("
            INSERT INTO catatan_wali (siswa_id, tahun_akademik_id, catatan)
            VALUES (:siswa_id, :tahun_id, :catatan)
            ON DUPLICATE KEY UPDATE
                catatan = :catatan_upd
        ");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        $this->db->bind('catatan', $catatan);
        $this->db->bind('catatan_upd', $catatan);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function setValidasiNilai($siswa_id, $tahun_id, $validasi_status)
    {
        $this->db->query("UPDATE nilai_rapor SET validasi = :status WHERE siswa_id = :siswa_id AND tahun_akademik_id = :tahun_id");
        $this->db->bind('status', $validasi_status);
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        $this->db->execute();
        return $this->db->rowCount();
    }
    
    // For cetak rapor
    public function getNilaiKelompokBySiswa($siswa_id, $tahun_id)
    {
        $this->db->query("
            SELECT n.*, m.nama_mapel, m.kkm, m.urutan as urutan_mapel, km.nama_kelompok, km.id as id_kelompok, km.urutan as urutan_kelompok
            FROM nilai_rapor n 
            JOIN mata_pelajaran m ON n.mapel_id = m.id 
            LEFT JOIN kelompok_mapel km ON m.kelompok_id = km.id
            WHERE n.siswa_id = :siswa_id AND n.tahun_akademik_id = :tahun_id 
            ORDER BY km.urutan ASC, m.urutan ASC, m.nama_mapel ASC
        ");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tahun_id', $tahun_id);
        
        $results = $this->db->resultSet();
        $kelompok_data = [];
        foreach ($results as $nilai) {
            $id_kelompok = $nilai['id_kelompok'] ?? 'umum';
            $nama_kelompok = $nilai['nama_kelompok'] ?? 'Umum';
            
            if (!isset($kelompok_data[$id_kelompok])) {
                $kelompok_data[$id_kelompok] = [
                    'nama_kelompok' => $nama_kelompok,
                    'mapel' => []
                ];
            }
            $kelompok_data[$id_kelompok]['mapel'][] = $nilai;
        }
        return $kelompok_data;
    }
    
    public function getTahunAkademikAktif()
    {
        $this->db->query("SELECT * FROM tahun_akademik WHERE status = 'Aktif' LIMIT 1");
        $aktif = $this->db->single();
        if(!$aktif) {
            $this->db->query("SELECT * FROM tahun_akademik ORDER BY id DESC LIMIT 1");
            return $this->db->single();
        }
        return $aktif;
    }
    
    public function getAllTahunAkademik()
    {
        $this->db->query("SELECT * FROM tahun_akademik ORDER BY id DESC");
        return $this->db->resultSet();
    }
    
    public function getAllRombel()
    {
        $this->db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        return $this->db->resultSet();
    }

    public function getSiswaDetail($siswa_id)
    {
        $this->db->query("SELECT s.*, u.nama_lengkap, r.nama_rombel as nama_kelas, r.id as id_kelas, r.wali_kelas_id FROM siswa s JOIN anggota_rombel ar ON s.id = ar.siswa_id JOIN rombel r ON ar.rombel_id = r.id JOIN users u ON s.user_id = u.id WHERE s.id = :siswa_id LIMIT 1");
        $this->db->bind('siswa_id', $siswa_id);
        return $this->db->single();
    }

    public function getGuruDetail($guru_id)
    {
        $this->db->query("SELECT * FROM guru WHERE id = :id OR user_id = :id LIMIT 1");
        $this->db->bind('id', $guru_id);
        return $this->db->single();
    }

    public function getSettingSekolah()
    {
        $this->db->query("SELECT * FROM pengaturan LIMIT 1");
        return $this->db->single();
    }

    // ============================================
    // PENGATURAN E-RAPOR
    // ============================================

    public function getAllMapel()
    {
        $this->db->query("
            SELECT m.*, km.nama_kelompok
            FROM mata_pelajaran m
            LEFT JOIN kelompok_mapel km ON m.kelompok_id = km.id
            ORDER BY km.urutan ASC, m.urutan ASC, m.nama_mapel ASC
        ");
        return $this->db->resultSet();
    }

    public function updateMapelKKM($id, $kkm, $kelompok_id, $urutan)
    {
        $this->db->query("UPDATE mata_pelajaran SET kkm = :kkm, kelompok_id = :kelompok_id, urutan = :urutan WHERE id = :id");
        $this->db->bind('kkm', $kkm);
        $this->db->bind('kelompok_id', $kelompok_id ?: null);
        $this->db->bind('urutan', $urutan);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // Kelompok Mapel
    public function getAllKelompokMapel()
    {
        $this->db->query("SELECT * FROM kelompok_mapel ORDER BY urutan ASC");
        return $this->db->resultSet();
    }

    public function saveKelompokMapel($nama, $urutan)
    {
        $this->db->query("INSERT INTO kelompok_mapel (nama_kelompok, urutan) VALUES (:nama, :urutan)");
        $this->db->bind('nama', $nama);
        $this->db->bind('urutan', $urutan);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function updateKelompokMapel($id, $nama, $urutan)
    {
        $this->db->query("UPDATE kelompok_mapel SET nama_kelompok = :nama, urutan = :urutan WHERE id = :id");
        $this->db->bind('nama', $nama);
        $this->db->bind('urutan', $urutan);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteKelompokMapel($id)
    {
        $this->db->query("DELETE FROM kelompok_mapel WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // Master Ekskul
    public function getAllMasterEkskul()
    {
        $this->db->query("SELECT * FROM master_ekskul ORDER BY nama_ekskul ASC");
        return $this->db->resultSet();
    }

    public function saveMasterEkskul($nama, $pembina)
    {
        $this->db->query("INSERT INTO master_ekskul (nama_ekskul, pembina) VALUES (:nama, :pembina)");
        $this->db->bind('nama', $nama);
        $this->db->bind('pembina', $pembina);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function updateMasterEkskul($id, $nama, $pembina)
    {
        $this->db->query("UPDATE master_ekskul SET nama_ekskul = :nama, pembina = :pembina WHERE id = :id");
        $this->db->bind('nama', $nama);
        $this->db->bind('pembina', $pembina);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteMasterEkskul($id)
    {
        $this->db->query("DELETE FROM master_ekskul WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

}

