<?php

class LaporanAbsenModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        // Pastikan tabel dasar ada. Jika ada struktur tambahan nanti, taruh di sini.
        // Saat ini, absensi_siswa dan absensi_siswa_detail dikelola oleh UserModel,
        // namun kita dapat menambahkan check tambahan jika dibutuhkan.
    }

    public function getModeSiswa()
    {
        $this->db->query("SELECT mode_siswa FROM pengaturan_absensi ORDER BY id ASC LIMIT 1");
        $row = $this->db->single();
        return $row ? $row['mode_siswa'] : 'Normal';
    }

    public function getLaporan($tgl_mulai, $tgl_sampai, $rombel_id = null)
    {
        $mode = $this->getModeSiswa();

        if ($mode === 'Normal') {
            // Laporan Harian
            $query = "
                SELECT 
                    a.id, 
                    a.tanggal, 
                    s.nisn, 
                    u.nama_lengkap, 
                    r.nama_rombel as kelas, 
                    a.status, 
                    a.waktu_scan 
                FROM absensi_siswa a
                JOIN siswa s ON a.siswa_id = s.id
                JOIN users u ON s.user_id = u.id
                JOIN anggota_rombel ar ON s.id = ar.siswa_id
                JOIN rombel r ON ar.rombel_id = r.id
                WHERE a.tanggal BETWEEN :mulai AND :sampai
            ";
            if ($rombel_id) {
                $query .= " AND r.id = :rombel_id";
            }
            $query .= " ORDER BY a.tanggal DESC, u.nama_lengkap ASC";

            $this->db->query($query);
            $this->db->bind('mulai', $tgl_mulai);
            $this->db->bind('sampai', $tgl_sampai);
            if ($rombel_id) {
                $this->db->bind('rombel_id', $rombel_id);
            }
            return $this->db->resultSet();
        } else {
            // Laporan Per Jam Pelajaran
            $query = "
                SELECT 
                    ad.id, 
                    ad.tanggal, 
                    ad.jam_ke,
                    s.nisn, 
                    u.nama_lengkap, 
                    r.nama_rombel as kelas, 
                    ad.status, 
                    ad.waktu_scan,
                    m.nama_mapel
                FROM absensi_siswa_detail ad
                JOIN siswa s ON ad.siswa_id = s.id
                JOIN users u ON s.user_id = u.id
                JOIN anggota_rombel ar ON s.id = ar.siswa_id
                JOIN rombel r ON ar.rombel_id = r.id
                LEFT JOIN jadwal_pelajaran jp ON ad.jam_ke = jp.id
                LEFT JOIN mata_pelajaran m ON jp.mapel_id = m.id
                WHERE ad.tanggal BETWEEN :mulai AND :sampai
            ";
            if ($rombel_id) {
                $query .= " AND r.id = :rombel_id";
            }
            $query .= " ORDER BY ad.tanggal DESC, ad.jam_ke ASC, u.nama_lengkap ASC";

            $this->db->query($query);
            $this->db->bind('mulai', $tgl_mulai);
            $this->db->bind('sampai', $tgl_sampai);
            if ($rombel_id) {
                $this->db->bind('rombel_id', $rombel_id);
            }
            return $this->db->resultSet();
        }
    }

    public function getGrafikSummary($tgl_mulai, $tgl_sampai, $rombel_id = null)
    {
        $mode = $this->getModeSiswa();

        if ($mode === 'Normal') {
            $query = "
                SELECT status, COUNT(id) as total 
                FROM absensi_siswa a
                JOIN siswa s ON a.siswa_id = s.id
                JOIN anggota_rombel ar ON s.id = ar.siswa_id
                WHERE a.tanggal BETWEEN :mulai AND :sampai
            ";
            if ($rombel_id) {
                $query .= " AND ar.rombel_id = :rombel_id";
            }
            $query .= " GROUP BY status";
        } else {
            $query = "
                SELECT status, COUNT(id) as total 
                FROM absensi_siswa_detail a
                JOIN siswa s ON a.siswa_id = s.id
                JOIN anggota_rombel ar ON s.id = ar.siswa_id
                WHERE a.tanggal BETWEEN :mulai AND :sampai
            ";
            if ($rombel_id) {
                $query .= " AND ar.rombel_id = :rombel_id";
            }
            $query .= " GROUP BY status";
        }

        $this->db->query($query);
        $this->db->bind('mulai', $tgl_mulai);
        $this->db->bind('sampai', $tgl_sampai);
        if ($rombel_id) {
            $this->db->bind('rombel_id', $rombel_id);
        }

        $result = $this->db->resultSet();
        $summary = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alpa' => 0];
        foreach ($result as $row) {
            if (isset($summary[$row['status']])) {
                $summary[$row['status']] = (int)$row['total'];
            }
        }
        return $summary;
    }
}
