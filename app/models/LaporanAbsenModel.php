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
        // Tabel absensi_siswa dan absensi_siswa_detail dikelola oleh UserModel.
        // Tidak ada penambahan tabel baru untuk laporan.
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
            $query = "
                SELECT 
                    a.id, 
                    a.tanggal, 
                    s.nisn, 
                    u.nama_lengkap, 
                    r.nama_rombel AS kelas, 
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
            // Per Jam Pelajaran
            $query = "
                SELECT 
                    ad.id, 
                    ad.tanggal, 
                    ad.jam_ke,
                    s.nisn, 
                    u.nama_lengkap, 
                    r.nama_rombel AS kelas, 
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

    /**
     * Akumulasi kehadiran per siswa dalam satu kelas untuk rentang tanggal tertentu.
     * Mengembalikan: [siswa_id, nisn, nama_lengkap, kelas, hadir, sakit, izin, alpa, total]
     */
    public function getAkumulasiPerKelas($rombel_id, $tgl_mulai, $tgl_sampai)
    {
        $mode = $this->getModeSiswa();
        $tabel = $mode === 'Normal' ? "(
            SELECT 
                siswa_id, 
                tanggal,
                CASE
                    WHEN COUNT(CASE WHEN status = 'Alpa' THEN 1 END) > 0 THEN 'Alpa'
                    WHEN COUNT(CASE WHEN status = 'Izin' THEN 1 END) > 0 THEN 'Izin'
                    WHEN COUNT(CASE WHEN status = 'Sakit' THEN 1 END) > 0 THEN 'Sakit'
                    WHEN COUNT(CASE WHEN status = 'Hadir' THEN 1 END) > 0 THEN 'Hadir'
                    ELSE 'Alpa'
                END AS status,
                MAX(id) as id
            FROM absensi_siswa
            GROUP BY siswa_id, tanggal
        )" : 'absensi_siswa_detail';

        $this->db->query("
            SELECT
                s.id AS siswa_id,
                s.nisn,
                u.nama_lengkap,
                r.nama_rombel AS kelas,
                SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN a.status = 'Izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN a.status = 'Alpa'  THEN 1 ELSE 0 END) AS alpa,
                COUNT(a.id) AS total
            FROM anggota_rombel ar
            JOIN siswa s ON ar.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            JOIN rombel r ON ar.rombel_id = r.id
            LEFT JOIN {$tabel} a ON a.siswa_id = s.id
                AND a.tanggal BETWEEN :mulai AND :sampai
            WHERE ar.rombel_id = :rombel_id
            GROUP BY s.id, s.nisn, u.nama_lengkap, r.nama_rombel
            ORDER BY u.nama_lengkap ASC
        ");
        $this->db->bind('mulai', $tgl_mulai);
        $this->db->bind('sampai', $tgl_sampai);
        $this->db->bind('rombel_id', $rombel_id);
        return $this->db->resultSet();
    }

    /**
     * Dapatkan rekapitulasi / summary absen per siswa untuk view laporan utama.
     */
    public function getSummarySiswa($tgl_mulai, $tgl_sampai, $rombel_id = null)
    {
        $mode = $this->getModeSiswa();
        $tabel = $mode === 'Normal' ? "(
            SELECT 
                siswa_id, 
                tanggal,
                CASE
                    WHEN COUNT(CASE WHEN status = 'Alpa' THEN 1 END) > 0 THEN 'Alpa'
                    WHEN COUNT(CASE WHEN status = 'Izin' THEN 1 END) > 0 THEN 'Izin'
                    WHEN COUNT(CASE WHEN status = 'Sakit' THEN 1 END) > 0 THEN 'Sakit'
                    WHEN COUNT(CASE WHEN status = 'Hadir' THEN 1 END) > 0 THEN 'Hadir'
                    ELSE 'Alpa'
                END AS status,
                MAX(id) as id
            FROM absensi_siswa
            GROUP BY siswa_id, tanggal
        )" : 'absensi_siswa_detail';

        $query = "
            SELECT
                s.id AS siswa_id,
                s.nisn,
                u.nama_lengkap,
                r.nama_rombel AS kelas,
                SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN a.status = 'Izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN a.status = 'Alpa'  THEN 1 ELSE 0 END) AS alpa,
                COUNT(a.id) AS total
            FROM anggota_rombel ar
            JOIN siswa s ON ar.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            JOIN rombel r ON ar.rombel_id = r.id
            LEFT JOIN {$tabel} a ON a.siswa_id = s.id
                AND a.tanggal BETWEEN :mulai AND :sampai
        ";

        if ($rombel_id) {
            $query .= " WHERE ar.rombel_id = :rombel_id";
        }

        $query .= " GROUP BY s.id, s.nisn, u.nama_lengkap, r.nama_rombel ORDER BY r.nama_rombel ASC, u.nama_lengkap ASC";

        $this->db->query($query);
        $this->db->bind('mulai', $tgl_mulai);
        $this->db->bind('sampai', $tgl_sampai);
        if ($rombel_id) {
            $this->db->bind('rombel_id', $rombel_id);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Kembalikan rentang tanggal semester aktif.
     * Semester 1 : Juli - Desember, Semester 2 : Januari - Juni
     */
    public function getRentangSemester()
    {
        $bulan = (int)date('n');
        $tahun = (int)date('Y');

        if ($bulan >= 7) {
            return [
                'mulai'    => "{$tahun}-07-01",
                'sampai'   => "{$tahun}-12-31",
                'label'    => "Semester 1 {$tahun}/" . ($tahun + 1),
                'semester' => 1,
                'ta_mulai' => $tahun,
                'ta_akhir' => $tahun + 1,
            ];
        } else {
            return [
                'mulai'    => ($tahun - 1) . '-07-01',
                'sampai'   => "{$tahun}-06-30",
                'label'    => 'Semester 2 ' . ($tahun - 1) . "/{$tahun}",
                'semester' => 2,
                'ta_mulai' => $tahun - 1,
                'ta_akhir' => $tahun,
            ];
        }
    }

    public function getGrafikSummary($tgl_mulai, $tgl_sampai, $rombel_id = null)
    {
        $mode = $this->getModeSiswa();

        if ($mode === 'Normal') {
            // Prefix a.status agar tidak ambigu dengan siswa.status
            $query = "
                SELECT a.status, COUNT(a.id) AS total 
                FROM (
                    SELECT 
                        siswa_id, 
                        tanggal,
                        CASE
                            WHEN COUNT(CASE WHEN status = 'Alpa' THEN 1 END) > 0 THEN 'Alpa'
                            WHEN COUNT(CASE WHEN status = 'Izin' THEN 1 END) > 0 THEN 'Izin'
                            WHEN COUNT(CASE WHEN status = 'Sakit' THEN 1 END) > 0 THEN 'Sakit'
                            WHEN COUNT(CASE WHEN status = 'Hadir' THEN 1 END) > 0 THEN 'Hadir'
                            ELSE 'Alpa'
                        END AS status,
                        MAX(id) as id
                    FROM absensi_siswa
                    GROUP BY siswa_id, tanggal
                ) a
                JOIN siswa s ON a.siswa_id = s.id
                JOIN anggota_rombel ar ON s.id = ar.siswa_id
                WHERE a.tanggal BETWEEN :mulai AND :sampai
            ";
            if ($rombel_id) {
                $query .= " AND ar.rombel_id = :rombel_id";
            }
            $query .= " GROUP BY a.status";
        } else {
            // Prefix a.status agar tidak ambigu dengan siswa.status
            $query = "
                SELECT a.status, COUNT(a.id) AS total 
                FROM absensi_siswa_detail a
                JOIN siswa s ON a.siswa_id = s.id
                JOIN anggota_rombel ar ON s.id = ar.siswa_id
                WHERE a.tanggal BETWEEN :mulai AND :sampai
            ";
            if ($rombel_id) {
                $query .= " AND ar.rombel_id = :rombel_id";
            }
            $query .= " GROUP BY a.status";
        }

        $this->db->query($query);
        $this->db->bind('mulai', $tgl_mulai);
        $this->db->bind('sampai', $tgl_sampai);
        if ($rombel_id) {
            $this->db->bind('rombel_id', $rombel_id);
        }

        $result  = $this->db->resultSet();
        $summary = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alpa' => 0];
        foreach ($result as $row) {
            if (isset($summary[$row['status']])) {
                $summary[$row['status']] = (int)$row['total'];
            }
        }
        return $summary;
    }
}
