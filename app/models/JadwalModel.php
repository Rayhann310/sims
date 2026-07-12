<?php

class JadwalModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getGuruIdByUserId($user_id)
    {
        $this->db->query("SELECT id FROM guru WHERE user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        $result = $this->db->single();
        return $result ? $result['id'] : null;
    }

    public function getJadwalByRombel($rombel_id)
    {
        $this->db->query("
            SELECT jp.*, 
                   m.nama_mapel, m.kode_mapel, 
                   u.nama_lengkap as nama_guru,
                   r.nama_rombel,
                   k.nama_kelas,
                   t.nama_tahun, t.semester
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            JOIN guru g ON jp.guru_id = g.id
            JOIN users u ON g.user_id = u.id
            JOIN rombel r ON jp.rombel_id = r.id
            JOIN kelas k ON r.kelas_id = k.id
            JOIN tahun_akademik t ON r.tahun_akademik_id = t.id
            WHERE jp.rombel_id = :rombel_id
            ORDER BY FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jp.jam_mulai ASC
        ");
        $this->db->bind('rombel_id', $rombel_id);
        return $this->db->resultSet();
    }

    /**
     * Cek konflik jam untuk guru (guru yang sama tidak bisa ngajar 2 kelas di jam yang sama)
     * dan konflik jam untuk rombel (rombel tidak bisa punya 2 pelajaran di jam yang sama)
     * @return array ['konflik_guru' => bool, 'konflik_rombel' => bool, 'detail' => string]
     */
    public function cekKonflikJam($rombel_id, $guru_id, $hari, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $exclude_sql = $exclude_id ? " AND jp.id != :exclude_id" : "";

        // Cek bentrok untuk ROMBEL
        $this->db->query("
            SELECT jp.id, m.nama_mapel, jp.jam_mulai, jp.jam_selesai
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            WHERE jp.rombel_id = :rombel_id
              AND jp.hari = :hari
              AND :jam_mulai < jp.jam_selesai
              AND :jam_selesai > jp.jam_mulai
              $exclude_sql
            LIMIT 1
        ");
        $this->db->bind('rombel_id', $rombel_id);
        $this->db->bind('hari', $hari);
        $this->db->bind('jam_mulai', $jam_mulai);
        $this->db->bind('jam_selesai', $jam_selesai);
        if ($exclude_id) $this->db->bind('exclude_id', $exclude_id);
        $konflik_rombel = $this->db->single();

        // Cek bentrok untuk GURU
        $this->db->query("
            SELECT jp.id, r2.nama_rombel, jp.jam_mulai, jp.jam_selesai
            FROM jadwal_pelajaran jp
            JOIN rombel r2 ON jp.rombel_id = r2.id
            WHERE jp.guru_id = :guru_id
              AND jp.hari = :hari
              AND :jam_mulai < jp.jam_selesai
              AND :jam_selesai > jp.jam_mulai
              $exclude_sql
            LIMIT 1
        ");
        $this->db->bind('guru_id', $guru_id);
        $this->db->bind('hari', $hari);
        $this->db->bind('jam_mulai', $jam_mulai);
        $this->db->bind('jam_selesai', $jam_selesai);
        if ($exclude_id) $this->db->bind('exclude_id', $exclude_id);
        $konflik_guru = $this->db->single();

        return [
            'konflik_rombel' => $konflik_rombel ?: false,
            'konflik_guru'   => $konflik_guru ?: false,
        ];
    }

    public function tambahJadwal($data)
    {
        $konflik = $this->cekKonflikJam(
            $data['rombel_id'], $data['guru_id'],
            $data['hari'], $data['jam_mulai'], $data['jam_selesai']
        );

        if ($konflik['konflik_rombel']) {
            return ['status' => false, 'pesan' => 'Bentrok jam di rombel! Mapel "' . $konflik['konflik_rombel']['nama_mapel'] . '" sudah ada di jam ' . $konflik['konflik_rombel']['jam_mulai'] . '-' . $konflik['konflik_rombel']['jam_selesai'] . ' hari ' . $data['hari'] . '.'];
        }
        if ($konflik['konflik_guru']) {
            return ['status' => false, 'pesan' => 'Bentrok jam guru! Guru ini sudah mengajar rombel "' . $konflik['konflik_guru']['nama_rombel'] . '" di jam ' . $konflik['konflik_guru']['jam_mulai'] . '-' . $konflik['konflik_guru']['jam_selesai'] . ' hari ' . $data['hari'] . '.'];
        }

        $this->db->query("INSERT INTO jadwal_pelajaran (rombel_id, mapel_id, guru_id, hari, jam_mulai, jam_selesai) VALUES (:rombel_id, :mapel_id, :guru_id, :hari, :jam_mulai, :jam_selesai)");
        $this->db->bind('rombel_id', $data['rombel_id']);
        $this->db->bind('mapel_id', $data['mapel_id']);
        $this->db->bind('guru_id', $data['guru_id']);
        $this->db->bind('hari', $data['hari']);
        $this->db->bind('jam_mulai', $data['jam_mulai']);
        $this->db->bind('jam_selesai', $data['jam_selesai']);
        $this->db->execute();
        return ['status' => true];
    }

    public function hapusJadwal($id)
    {
        $this->db->query("DELETE FROM jadwal_pelajaran WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function importJadwalMassal($data)
    {
        $inserted = 0;
        $errors = [];
        foreach($data as $i => $row) {
            $konflik = $this->cekKonflikJam($row['rombel_id'], $row['guru_id'], $row['hari'], $row['jam_mulai'], $row['jam_selesai']);
            if ($konflik['konflik_rombel'] || $konflik['konflik_guru']) {
                $errors[] = 'Baris ' . ($i + 2) . ': bentrok jam.';
                continue;
            }
            $this->db->query("INSERT IGNORE INTO jadwal_pelajaran (rombel_id, mapel_id, guru_id, hari, jam_mulai, jam_selesai) VALUES (:rombel_id, :mapel_id, :guru_id, :hari, :jam_mulai, :jam_selesai)");
            $this->db->bind('rombel_id', $row['rombel_id']);
            $this->db->bind('mapel_id', $row['mapel_id']);
            $this->db->bind('guru_id', $row['guru_id']);
            $this->db->bind('hari', $row['hari']);
            $this->db->bind('jam_mulai', $row['jam_mulai']);
            $this->db->bind('jam_selesai', $row['jam_selesai']);
            $this->db->execute();
            $inserted += $this->db->rowCount();
        }
        return ['inserted' => $inserted, 'errors' => $errors];
    }

    public function getAllGuru()
    {
        $this->db->query("SELECT g.id, u.nama_lengkap FROM guru g JOIN users u ON g.user_id = u.id ORDER BY u.nama_lengkap ASC");
        return $this->db->resultSet();
    }

    public function getAllMapel()
    {
        $this->db->query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
        return $this->db->resultSet();
    }

    // --- PENGATURAN JADWAL ---
    public function getPengaturanJadwal()
    {
        $this->db->query("SELECT * FROM pengaturan_jadwal LIMIT 1");
        $result = $this->db->single();
        if (!$result) {
            // Default settings if empty
            return [
                'jam_mulai' => '07:00:00',
                'durasi_per_jp' => 45,
                'max_jp_per_hari' => 10,
                'hari_aktif' => 'Senin,Selasa,Rabu,Kamis,Jumat'
            ];
        }
        return $result;
    }

    public function savePengaturanJadwal($data)
    {
        $this->db->query("SELECT id FROM pengaturan_jadwal LIMIT 1");
        $exists = $this->db->single();
        if ($exists) {
            $this->db->query("UPDATE pengaturan_jadwal SET jam_mulai = :jam_mulai, durasi_per_jp = :durasi_per_jp, max_jp_per_hari = :max_jp_per_hari, hari_aktif = :hari_aktif WHERE id = :id");
            $this->db->bind('id', $exists['id']);
        } else {
            $this->db->query("INSERT INTO pengaturan_jadwal (jam_mulai, durasi_per_jp, max_jp_per_hari, hari_aktif) VALUES (:jam_mulai, :durasi_per_jp, :max_jp_per_hari, :hari_aktif)");
        }
        $this->db->bind('jam_mulai', $data['jam_mulai']);
        $this->db->bind('durasi_per_jp', $data['durasi_per_jp']);
        $this->db->bind('max_jp_per_hari', $data['max_jp_per_hari']);
        $this->db->bind('hari_aktif', $data['hari_aktif']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAllIstirahat()
    {
        $this->db->query("SELECT * FROM jadwal_istirahat ORDER BY setelah_jp_ke ASC");
        return $this->db->resultSet();
    }

    public function simpanIstirahat($data)
    {
        if (!empty($data['id'])) {
            $this->db->query("UPDATE jadwal_istirahat SET nama_istirahat = :nama_istirahat, setelah_jp_ke = :setelah_jp_ke, durasi_menit = :durasi_menit, hari_khusus = :hari_khusus WHERE id = :id");
            $this->db->bind('id', $data['id']);
        } else {
            $this->db->query("INSERT INTO jadwal_istirahat (nama_istirahat, setelah_jp_ke, durasi_menit, hari_khusus) VALUES (:nama_istirahat, :setelah_jp_ke, :durasi_menit, :hari_khusus)");
        }
        $this->db->bind('nama_istirahat', $data['nama_istirahat']);
        $this->db->bind('setelah_jp_ke', $data['setelah_jp_ke']);
        $this->db->bind('durasi_menit', $data['durasi_menit']);
        $this->db->bind('hari_khusus', !empty($data['hari_khusus']) ? $data['hari_khusus'] : null);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusIstirahat($id)
    {
        $this->db->query("DELETE FROM jadwal_istirahat WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // --- ALOKASI MAPEL ---
    public function getAllAlokasi()
    {
        $this->db->query("SELECT a.*, m.nama_mapel FROM alokasi_mapel a JOIN mata_pelajaran m ON a.mapel_id = m.id ORDER BY a.tingkat, a.jurusan, m.nama_mapel");
        return $this->db->resultSet();
    }

    public function simpanAlokasi($data)
    {
        if (!empty($data['id'])) {
            $this->db->query("UPDATE alokasi_mapel SET mapel_id = :mapel_id, tingkat = :tingkat, jurusan = :jurusan, jumlah_jp = :jumlah_jp WHERE id = :id");
            $this->db->bind('id', $data['id']);
        } else {
            $this->db->query("INSERT INTO alokasi_mapel (mapel_id, tingkat, jurusan, jumlah_jp) VALUES (:mapel_id, :tingkat, :jurusan, :jumlah_jp) ON DUPLICATE KEY UPDATE jumlah_jp = :jumlah_jp");
        }
        $this->db->bind('mapel_id', $data['mapel_id']);
        $this->db->bind('tingkat', $data['tingkat']);
        $this->db->bind('jurusan', $data['jurusan']);
        $this->db->bind('jumlah_jp', $data['jumlah_jp']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusAlokasi($id)
    {
        $this->db->query("DELETE FROM alokasi_mapel WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
