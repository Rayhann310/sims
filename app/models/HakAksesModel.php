<?php

class HakAksesModel {
    private $db;

    // Definisi semua menu yang bisa dikustomisasi per jabatan
    public static $MENU_LIST = [
        // Akademik (Master)
        'akademik_tahun'   => ['label' => 'Tahun Akademik',         'group' => 'Akademik',          'url' => '/akademik/tahun'],
        'akademik_kelas'   => ['label' => 'Tingkat Kelas',          'group' => 'Akademik',          'url' => '/akademik/kelas'],
        'akademik_mapel'   => ['label' => 'Mata Pelajaran',         'group' => 'Akademik',          'url' => '/akademik/mapel'],
        'jabatan'          => ['label' => 'Jabatan Guru',           'group' => 'Akademik',          'url' => '/jabatan'],
        // Akademik (Lanjutan)
        'jadwal'           => ['label' => 'Jadwal Pelajaran',       'group' => 'Akademik',          'url' => '/jadwal'],
        'nilai'            => ['label' => 'Presensi & Nilai',       'group' => 'Akademik',          'url' => '/nilai'],
        'elearning'        => ['label' => 'E-Learning',             'group' => 'Akademik',          'url' => '/elearning'],
        'rombel'           => ['label' => 'Rombel & Siswa',         'group' => 'Akademik',          'url' => '/akademik/rombel'],
        'naik_kelas'       => ['label' => 'Naik Kelas',             'group' => 'Akademik',          'url' => '/akademik/naikKelas'],
        // Master Data
        'data_siswa'       => ['label' => 'Data Siswa',             'group' => 'Master Data',       'url' => '/siswa'],
        'data_guru'        => ['label' => 'Data Guru',              'group' => 'Master Data',       'url' => '/guru'],
        'orangtua'         => ['label' => 'Data Orang Tua',         'group' => 'Master Data',       'url' => '/orangtua'],
        'data_alumni'      => ['label' => 'Data Alumni',            'group' => 'Master Data',       'url' => '/alumni'],
        // Keuangan
        'keuangan_tarif'   => ['label' => 'Master Tarif',           'group' => 'Keuangan',          'url' => '/keuangan/tarif'],
        'keuangan_tagihan' => ['label' => 'Tagihan & Pembayaran',   'group' => 'Keuangan',          'url' => '/keuangan/tagihan'],
        'keuangan_riwayat' => ['label' => 'Riwayat Bayar',          'group' => 'Keuangan',          'url' => '/keuangan/riwayat'],
        'keuangan_bukukas' => ['label' => 'Buku Kas & Analisa',     'group' => 'Keuangan',          'url' => '/keuangan/bukuKas'],
        // Komunikasi
        'pengumuman'       => ['label' => 'Pengumuman',             'group' => 'Komunikasi',        'url' => '/komunikasi/pengumuman'],
        'pesan'            => ['label' => 'Pesan Masuk',            'group' => 'Komunikasi',        'url' => '/komunikasi/pesan'],
        // Kedisiplinan
        'kedisiplinan'     => ['label' => 'Rekap & Catatan',        'group' => 'Kedisiplinan',      'url' => '/kedisiplinan/rekap'],
        'ked_kategori'     => ['label' => 'Master Kategori',        'group' => 'Kedisiplinan',      'url' => '/kedisiplinan/kategori'],
        // SPMB
        'spmb'             => ['label' => 'SPMB/PPDB (Gelombang)',  'group' => 'SPMB / PPDB',       'url' => '/adminspmb'],
        'spmb_peserta'     => ['label' => 'SPMB (Data Peserta)',    'group' => 'SPMB / PPDB',       'url' => '/adminspmb/peserta'],
        'spmb_biaya'       => ['label' => 'SPMB (Biaya)',           'group' => 'SPMB / PPDB',       'url' => '/adminspmb/biaya'],
        // Kearsipan & Sistem
        'kearsipan'        => ['label' => 'Data Surat (TU)',        'group' => 'Kearsipan & TU',    'url' => '/kearsipan'],
        // CBT & Ujian
        'cbt_bank_soal'    => ['label' => 'Bank Soal CBT',          'group' => 'CBT & Ujian',       'url' => '/banksoal'],
        'cbt_setor_soal'   => ['label' => 'Setor Soal Ujian',       'group' => 'CBT & Ujian',       'url' => '/setorsoal'],
        'cbt_jadwal'       => ['label' => 'Jadwal & Pengawas CBT',  'group' => 'CBT & Ujian',       'url' => '/jadwalujian'],
        'cbt_proctor'      => ['label' => 'Dashboard Pengawas CBT', 'group' => 'CBT & Ujian',       'url' => '/proctor'],
        'pengaturan'       => ['label' => 'Pengaturan Sistem',      'group' => 'Sistem',            'url' => '/pengaturan'],
        'hak_akses'        => ['label' => 'Hak Akses Menu',         'group' => 'Sistem',            'url' => '/hakakses'],
    ];

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS `hak_akses_menu` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `jabatan_id` int(11) NOT NULL,
                `menu_key` varchar(100) NOT NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                UNIQUE KEY `jabatan_menu_unique` (`jabatan_id`, `menu_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->execute();
        } catch (Exception $e) {}
    }

    public function getAksesJabatan($jabatan_id)
    {
        $this->db->query("SELECT menu_key, is_active FROM hak_akses_menu WHERE jabatan_id = :jabatan_id");
        $this->db->bind('jabatan_id', $jabatan_id);
        $rows = $this->db->resultSet();
        
        $result = [];
        foreach ($rows as $row) {
            $result[$row['menu_key']] = (bool)$row['is_active'];
        }
        return $result;
    }

    public function getAllAksesGrouped()
    {
        // Ambil semua jabatan
        $this->db->query("SELECT * FROM jabatan ORDER BY nama_jabatan ASC");
        $jabatans = $this->db->resultSet();

        // Ambil semua hak akses
        $this->db->query("SELECT * FROM hak_akses_menu");
        $allAkses = $this->db->resultSet();

        // Build lookup: jabatan_id => menu_key => is_active
        $lookup = [];
        foreach ($allAkses as $a) {
            $lookup[$a['jabatan_id']][$a['menu_key']] = (bool)$a['is_active'];
        }

        return ['jabatans' => $jabatans, 'lookup' => $lookup];
    }

    public function toggleMenu($jabatan_id, $menu_key, $is_active)
    {
        // Validasi menu_key
        if (!array_key_exists($menu_key, self::$MENU_LIST)) {
            return false;
        }

        $this->db->query("INSERT INTO hak_akses_menu (jabatan_id, menu_key, is_active) VALUES (:jabatan_id, :menu_key, :is_active)
                          ON DUPLICATE KEY UPDATE is_active = :is_active2");
        $this->db->bind('jabatan_id', $jabatan_id);
        $this->db->bind('menu_key', $menu_key);
        $this->db->bind('is_active', $is_active ? 1 : 0);
        $this->db->bind('is_active2', $is_active ? 1 : 0);
        $this->db->execute();
        return true;
    }

    public function hasAccess($jabatan_id, $menu_key)
    {
        $this->db->query("SELECT is_active FROM hak_akses_menu WHERE jabatan_id = :jabatan_id AND menu_key = :menu_key");
        $this->db->bind('jabatan_id', $jabatan_id);
        $this->db->bind('menu_key', $menu_key);
        $row = $this->db->single();
        return $row ? (bool)$row['is_active'] : false;
    }

    public function resetSemua()
    {
        $this->db->query("TRUNCATE TABLE hak_akses_menu");
        $this->db->execute();
        return true;
    }
}
