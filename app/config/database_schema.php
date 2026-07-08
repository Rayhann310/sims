<?php

return array (
  'jabatan' =>
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' =>
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nama_jabatan' => 'varchar(100) NOT NULL',
      'deskripsi' => 'text DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'hak_akses_menu' =>
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `hak_akses_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jabatan_id` int(11) NOT NULL,
  `menu_key` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jabatan_menu_unique` (`jabatan_id`, `menu_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' =>
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'jabatan_id' => 'int(11) NOT NULL',
      'menu_key' => 'varchar(100) NOT NULL',
      'is_active' => 'tinyint(1) NOT NULL DEFAULT 0',
    ),
  ),
  'guru_jabatan' =>
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `guru_jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guru_id` int(11) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guru_jabatan_unique` (`guru_id`, `jabatan_id`),
  KEY `guru_id` (`guru_id`),
  KEY `jabatan_id` (`jabatan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' =>
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'guru_id' => 'int(11) NOT NULL',
      'jabatan_id' => 'int(11) NOT NULL',
    ),
  ),
  'anggota_rombel' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `anggota_rombel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rombel_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `rombel_id` (`rombel_id`),
  KEY `siswa_id` (`siswa_id`),
  CONSTRAINT `anggota_rombel_ibfk_1` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anggota_rombel_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'rombel_id' => 'int(11) NOT NULL',
      'siswa_id' => 'int(11) NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'catatan_kedisiplinan' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `catatan_kedisiplinan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `poin_dicatat` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `dicatat_oleh` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `kategori_id` (`kategori_id`),
  KEY `dicatat_oleh` (`dicatat_oleh`),
  CONSTRAINT `catatan_kedisiplinan_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catatan_kedisiplinan_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_kedisiplinan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catatan_kedisiplinan_ibfk_3` FOREIGN KEY (`dicatat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'siswa_id' => 'int(11) NOT NULL',
      'kategori_id' => 'int(11) NOT NULL',
      'tanggal' => 'date NOT NULL',
      'poin_dicatat' => 'int(11) NOT NULL',
      'keterangan' => 'text DEFAULT NULL',
      'dicatat_oleh' => 'int(11) NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'elearning_materi' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `elearning_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  CONSTRAINT `elearning_materi_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'jadwal_id' => 'int(11) NOT NULL',
      'judul' => 'varchar(255) NOT NULL',
      'deskripsi' => 'text DEFAULT NULL',
      'file_path' => 'varchar(255) DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'elearning_pengumpulan' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `elearning_pengumpulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tugas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `nilai` int(11) DEFAULT NULL,
  `waktu_kumpul` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tugas_id` (`tugas_id`),
  KEY `siswa_id` (`siswa_id`),
  CONSTRAINT `elearning_pengumpulan_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `elearning_tugas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `elearning_pengumpulan_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'tugas_id' => 'int(11) NOT NULL',
      'siswa_id' => 'int(11) NOT NULL',
      'file_path' => 'varchar(255) NOT NULL',
      'nilai' => 'int(11) DEFAULT NULL',
      'waktu_kumpul' => 'timestamp NOT NULL DEFAULT \'current_timestamp()\'',
    ),
  ),
  'elearning_tugas' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `elearning_tugas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tenggat_waktu` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  CONSTRAINT `elearning_tugas_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'jadwal_id' => 'int(11) NOT NULL',
      'judul' => 'varchar(255) NOT NULL',
      'deskripsi' => 'text DEFAULT NULL',
      'tenggat_waktu' => 'datetime NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'elearning_diskusi' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `elearning_diskusi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `elearning_diskusi_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `elearning_diskusi_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'jadwal_id' => 'int(11) NOT NULL',
      'user_id' => 'int(11) NOT NULL',
      'pesan' => 'text NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'elearning_absensi' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `elearning_absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_kehadiran` enum(\'Hadir\',\'Izin\',\'Sakit\',\'Alpa\') NOT NULL DEFAULT \'Alpa\',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  KEY `siswa_id` (`siswa_id`),
  UNIQUE KEY `jadwal_siswa_tanggal` (`jadwal_id`, `siswa_id`, `tanggal`),
  CONSTRAINT `elearning_absensi_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `elearning_absensi_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'jadwal_id' => 'int(11) NOT NULL',
      'siswa_id' => 'int(11) NOT NULL',
      'tanggal' => 'date NOT NULL',
      'status_kehadiran' => 'enum(\'Hadir\',\'Izin\',\'Sakit\',\'Alpa\') NOT NULL DEFAULT \'Alpa\'',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'guru' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `guru` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `jenis_kelamin` enum(\'L\',\'P\') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` LONGTEXT DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'user_id' => 'int(11) NOT NULL',
      'nip' => 'varchar(50) NOT NULL',
      'jenis_kelamin' => 'enum(\'L\',\'P\') NOT NULL',
      'tanggal_lahir' => 'date DEFAULT NULL',
      'no_hp' => 'varchar(20) DEFAULT NULL',
      'alamat' => 'text DEFAULT NULL',
      'foto' => 'LONGTEXT DEFAULT NULL',
      'jabatan_id' => 'int(11) DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'jadwal_pelajaran' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `jadwal_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rombel_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `rombel_id` (`rombel_id`),
  KEY `mapel_id` (`mapel_id`),
  KEY `guru_id` (`guru_id`),
  CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_pelajaran_ibfk_3` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'rombel_id' => 'int(11) NOT NULL',
      'mapel_id' => 'int(11) NOT NULL',
      'guru_id' => 'int(11) NOT NULL',
      'hari' => 'varchar(20) NOT NULL',
      'jam_mulai' => 'time NOT NULL',
      'jam_selesai' => 'time NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'kategori_kedisiplinan' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `kategori_kedisiplinan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `jenis` enum(\'Pelanggaran\',\'Penghargaan\') NOT NULL,
  `tingkatan` enum(\'Ringan\',\'Sedang\',\'Berat\',\'Prestasi\') NOT NULL,
  `poin` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nama_kategori' => 'varchar(100) NOT NULL',
      'jenis' => 'enum(\'Pelanggaran\',\'Penghargaan\') NOT NULL',
      'tingkatan' => 'enum(\'Ringan\',\'Sedang\',\'Berat\',\'Prestasi\') NOT NULL',
      'poin' => 'int(11) NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'kearsipan' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `kearsipan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `jenis_surat` enum(\'Masuk\',\'Keluar\') NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `pengirim_penerima` varchar(255) NOT NULL,
  `perihal` text NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nomor_surat' => 'varchar(100) NOT NULL',
      'tanggal_surat' => 'date NOT NULL',
      'jenis_surat' => 'enum(\'Masuk\',\'Keluar\') NOT NULL',
      'kategori' => 'varchar(100) NOT NULL',
      'pengirim_penerima' => 'varchar(255) NOT NULL',
      'perihal' => 'text NOT NULL',
      'file_surat' => 'varchar(255) DEFAULT NULL',
      'keterangan' => 'text DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'log_fonnte' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `log_fonnte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `nomor_tujuan` varchar(20) NOT NULL,
  `pesan` text NOT NULL,
  `response_code` int(11) DEFAULT NULL,
  `response_body` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'tanggal' => 'timestamp NOT NULL DEFAULT current_timestamp()',
      'nomor_tujuan' => 'varchar(20) NOT NULL',
      'pesan' => 'text NOT NULL',
      'response_code' => 'int(11) DEFAULT NULL',
      'response_body' => 'text DEFAULT NULL',
      'status' => 'varchar(50) DEFAULT NULL',
    ),
  ),
  'kelas' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) NOT NULL,
  `tingkat` enum(\'10\',\'11\',\'12\') NOT NULL,
  `jurusan` enum(\'MIPA\',\'IPS\',\'BAHASA\',\'UMUM\') NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wali_kelas_id` (`wali_kelas_id`),
  CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`wali_kelas_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nama_kelas' => 'varchar(50) NOT NULL',
      'tingkat' => 'enum(\'10\',\'11\',\'12\') NOT NULL',
      'jurusan' => 'enum(\'MIPA\',\'IPS\',\'BAHASA\',\'UMUM\') NOT NULL',
      'wali_kelas_id' => 'int(11) DEFAULT NULL',
    ),
  ),
  'mata_pelajaran' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `mata_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(10) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kategori` enum(\'Wajib A\',\'Wajib B\',\'Peminatan\',\'Lintas Minat\') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_mapel` (`kode_mapel`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'kode_mapel' => 'varchar(10) NOT NULL',
      'nama_mapel' => 'varchar(100) NOT NULL',
      'kategori' => 'enum(\'Wajib A\',\'Wajib B\',\'Peminatan\',\'Lintas Minat\') NOT NULL',
    ),
  ),
  'nilai_siswa' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `nilai_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jenis_nilai` varchar(50) NOT NULL,
  `nilai` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  KEY `siswa_id` (`siswa_id`),
  CONSTRAINT `nilai_siswa_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_siswa_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'jadwal_id' => 'int(11) NOT NULL',
      'siswa_id' => 'int(11) NOT NULL',
      'jenis_nilai' => 'varchar(50) NOT NULL',
      'nilai' => 'decimal(5,2) DEFAULT 0.00',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'notifikasi' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `pesan` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'user_id' => 'int(11) NOT NULL',
      'tipe' => 'varchar(50) NOT NULL',
      'pesan' => 'text NOT NULL',
      'link' => 'varchar(255) DEFAULT NULL',
      'is_read' => 'tinyint(1) DEFAULT 0',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'pembayaran_spp' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `pembayaran_spp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tagihan_id` int(11) NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `metode` varchar(50) DEFAULT \'Cash\',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tagihan_id` (`tagihan_id`),
  CONSTRAINT `pembayaran_spp_ibfk_1` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan_spp` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'tagihan_id' => 'int(11) NOT NULL',
      'tanggal_bayar' => 'date NOT NULL',
      'jumlah_bayar' => 'decimal(10,2) NOT NULL',
      'metode' => 'varchar(50) DEFAULT \'Cash\'',
      'keterangan' => 'text DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'pengaturan' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `pengaturan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_aplikasi` varchar(100) NOT NULL,
  `logo_teks` varchar(10) NOT NULL,
  `teks_footer` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nama_aplikasi' => 'varchar(100) NOT NULL',
      'logo_teks' => 'varchar(10) NOT NULL',
      'logo_sekolah' => 'LONGTEXT DEFAULT NULL',
      'fonnte_token' => 'varchar(255) DEFAULT NULL',
      'teks_footer' => 'varchar(255) NOT NULL',
      'updated_at' => 'timestamp NOT NULL DEFAULT \'current_timestamp()\' on update current_timestamp()',
    ),
  ),
  'pengumuman' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `pengumuman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `penulis_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `penulis_id` (`penulis_id`),
  CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`penulis_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'judul' => 'varchar(255) NOT NULL',
      'isi' => 'text NOT NULL',
      'penulis_id' => 'int(11) NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'pesan' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `pesan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengirim_id` int(11) NOT NULL,
  `penerima_id` int(11) NOT NULL,
  `subjek` varchar(255) NOT NULL,
  `isi_pesan` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pengirim_id` (`pengirim_id`),
  KEY `penerima_id` (`penerima_id`),
  CONSTRAINT `pesan_ibfk_1` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesan_ibfk_2` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'pengirim_id' => 'int(11) NOT NULL',
      'penerima_id' => 'int(11) NOT NULL',
      'subjek' => 'varchar(255) NOT NULL',
      'isi_pesan' => 'text NOT NULL',
      'is_read' => 'tinyint(1) DEFAULT 0',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'presensi_siswa' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `presensi_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status` enum(\'Hadir\',\'Izin\',\'Sakit\',\'Alpa\') DEFAULT \'Hadir\',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  KEY `siswa_id` (`siswa_id`),
  CONSTRAINT `presensi_siswa_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensi_siswa_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'jadwal_id' => 'int(11) NOT NULL',
      'tanggal' => 'date NOT NULL',
      'siswa_id' => 'int(11) NOT NULL',
      'status' => 'enum(\'Hadir\',\'Izin\',\'Sakit\',\'Alpa\') DEFAULT \'Hadir\'',
      'keterangan' => 'varchar(255) DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'rombel' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `rombel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_akademik_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `nama_rombel` varchar(50) NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tahun_akademik_id` (`tahun_akademik_id`),
  KEY `kelas_id` (`kelas_id`),
  KEY `wali_kelas_id` (`wali_kelas_id`),
  CONSTRAINT `rombel_ibfk_1` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rombel_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rombel_ibfk_3` FOREIGN KEY (`wali_kelas_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'tahun_akademik_id' => 'int(11) NOT NULL',
      'kelas_id' => 'int(11) NOT NULL',
      'nama_rombel' => 'varchar(50) NOT NULL',
      'wali_kelas_id' => 'int(11) DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'siswa' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nisn` varchar(15) NOT NULL,
  `jenis_kelamin` enum(\'L\',\'P\') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `status` enum(\'Aktif\',\'Alumni\',\'Keluar\') DEFAULT \'Aktif\',
  `foto` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nisn` (`nisn`),
  KEY `user_id` (`user_id`),
  KEY `kelas_id` (`kelas_id`),
  CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'user_id' => 'int(11) NOT NULL',
      'kelas_id' => 'int(11) DEFAULT NULL',
      'nisn' => 'varchar(15) NOT NULL',
      'jenis_kelamin' => 'enum(\'L\',\'P\') NOT NULL',
      'tanggal_lahir' => 'date DEFAULT NULL',
      'alamat' => 'text DEFAULT NULL',
      'nama_wali' => 'varchar(100) DEFAULT NULL',
      'no_hp_wali' => 'varchar(20) DEFAULT NULL',
      'no_hp' => 'varchar(20) DEFAULT NULL',
      'status' => 'enum(\'Aktif\',\'Alumni\',\'Keluar\') DEFAULT \'Aktif\'',
      'tahun_lulus' => 'int(4) DEFAULT NULL',
      'foto' => 'LONGTEXT DEFAULT NULL',
      'qr_token' => 'VARCHAR(255) NULL DEFAULT NULL',
    ),
  ),
  'tagihan_spp' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `tagihan_spp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `status` enum(\'Belum Lunas\',\'Lunas\') DEFAULT \'Belum Lunas\',
  `jatuh_tempo` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  CONSTRAINT `tagihan_spp_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'siswa_id' => 'int(11) NOT NULL',
      'bulan' => 'varchar(20) NOT NULL',
      'tahun' => 'int(11) NOT NULL',
      'nominal' => 'decimal(10,2) NOT NULL',
      'status' => 'enum(\'Belum Lunas\',\'Lunas\') DEFAULT \'Belum Lunas\'',
      'jatuh_tempo' => 'date DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'tahun_akademik' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `tahun_akademik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tahun` varchar(20) NOT NULL,
  `semester` enum(\'Ganjil\',\'Genap\') NOT NULL,
  `status` enum(\'Aktif\',\'Tidak Aktif\') DEFAULT \'Tidak Aktif\',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nama_tahun' => 'varchar(20) NOT NULL',
      'semester' => 'enum(\'Ganjil\',\'Genap\') NOT NULL',
      'status' => 'enum(\'Aktif\',\'Tidak Aktif\') DEFAULT \'Tidak Aktif\'',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'users' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum(\'admin\',\'guru\',\'siswa\') NOT NULL DEFAULT \'siswa\',
  `nama_lengkap` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'username' => 'varchar(50) NOT NULL',
      'password' => 'varchar(255) NOT NULL',
      'role' => 'enum(\'admin\',\'guru\',\'siswa\') NOT NULL DEFAULT \'siswa\'',
      'nama_lengkap' => 'varchar(100) NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'spmb_gelombang' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `spmb_gelombang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_gelombang` varchar(100) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `harga_formulir` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum(\'Buka\',\'Tutup\') DEFAULT \'Tutup\',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nama_gelombang' => 'varchar(100) NOT NULL',
      'tanggal_mulai' => 'date NOT NULL',
      'tanggal_selesai' => 'date NOT NULL',
      'harga_formulir' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
      'status' => 'enum(\'Buka\',\'Tutup\') DEFAULT \'Tutup\'',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'spmb_peserta' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `spmb_peserta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gelombang_id` int(11) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `asal_sekolah` varchar(150) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `nama_ayah` varchar(150) DEFAULT NULL,
  `nama_ibu` varchar(150) DEFAULT NULL,
  `pekerjaan_ortu` varchar(100) DEFAULT NULL,
  `penghasilan_ortu` varchar(100) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `status_pembayaran` enum(\'Belum Bayar\',\'Lunas\') DEFAULT \'Belum Bayar\',
  `status_seleksi` enum(\'Menunggu\',\'Lulus\',\'Tidak Lulus\') DEFAULT \'Menunggu\',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `gelombang_id` (`gelombang_id`),
  CONSTRAINT `spmb_peserta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `spmb_peserta_ibfk_2` FOREIGN KEY (`gelombang_id`) REFERENCES `spmb_gelombang` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'user_id' => 'int(11) NOT NULL',
      'gelombang_id' => 'int(11) NOT NULL',
      'nisn' => 'varchar(20) NOT NULL',
      'nama_lengkap' => 'varchar(150) NOT NULL',
      'asal_sekolah' => 'varchar(150) NOT NULL',
      'no_hp' => 'varchar(20) NOT NULL',
      'tempat_lahir' => 'varchar(100) DEFAULT NULL',
      'tanggal_lahir' => 'date DEFAULT NULL',
      'alamat_lengkap' => 'text DEFAULT NULL',
      'nama_ayah' => 'varchar(150) DEFAULT NULL',
      'nama_ibu' => 'varchar(150) DEFAULT NULL',
      'pekerjaan_ortu' => 'varchar(100) DEFAULT NULL',
      'penghasilan_ortu' => 'varchar(100) DEFAULT NULL',
      'no_hp_ortu' => 'varchar(20) DEFAULT NULL',
      'status_pembayaran' => 'enum(\'Belum Bayar\',\'Lunas\') DEFAULT \'Belum Bayar\'',
      'status_seleksi' => 'enum(\'Menunggu\',\'Lulus\',\'Tidak Lulus\') DEFAULT \'Menunggu\'',
      'bukti_bayar' => 'varchar(255) DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'spmb_pembayaran' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `spmb_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peserta_id` int(11) NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `metode` varchar(50) NOT NULL,
  `bukti` varchar(255) NOT NULL,
  `status` enum(\'Pending\',\'Diterima\',\'Ditolak\') DEFAULT \'Pending\',
  `tanggal_bayar` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `peserta_id` (`peserta_id`),
  CONSTRAINT `spmb_pembayaran_ibfk_1` FOREIGN KEY (`peserta_id`) REFERENCES `spmb_peserta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'peserta_id' => 'int(11) NOT NULL',
      'jumlah_bayar' => 'decimal(10,2) NOT NULL',
      'metode' => 'varchar(50) NOT NULL',
      'bukti' => 'varchar(255) NOT NULL',
      'status' => 'enum(\'Pending\',\'Diterima\',\'Ditolak\') DEFAULT \'Pending\'',
      'tanggal_bayar' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'spmb_kategori_biaya' =>
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `spmb_kategori_biaya` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' =>
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'nama_kategori' => 'varchar(150) NOT NULL',
      'deskripsi' => 'text DEFAULT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'spmb_rincian_biaya' =>
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `spmb_rincian_biaya` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `nama_rincian` varchar(150) NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `spmb_rincian_biaya_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `spmb_kategori_biaya` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' =>
    array (
      'id' => 'int(11) NOT NULL auto_increment',
      'kategori_id' => 'int(11) NOT NULL',
      'nama_rincian' => 'varchar(150) NOT NULL',
      'nominal' => 'decimal(10,2) NOT NULL',
      'created_at' => 'timestamp NOT NULL DEFAULT current_timestamp()',
    ),
  ),
  'absensi_guru' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `absensi_guru` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `guru_id` INT(11) NOT NULL,
  `tanggal` DATE NOT NULL,
  `waktu_masuk` TIME NOT NULL,
  `waktu_pulang` TIME NULL DEFAULT NULL,
  `status` ENUM(\'Hadir\',\'Sakit\',\'Izin\',\'Dinas Luar\',\'Alpa\') NOT NULL DEFAULT \'Hadir\',
  `terlambat_menit` INT(11) DEFAULT 0,
  `sync_status` BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'INT(11) AUTO_INCREMENT PRIMARY KEY',
      'guru_id' => 'INT(11) NOT NULL',
      'tanggal' => 'DATE NOT NULL',
      'waktu_masuk' => 'TIME NOT NULL',
      'waktu_pulang' => 'TIME NULL DEFAULT NULL',
      'status' => 'ENUM(\'Hadir\',\'Sakit\',\'Izin\',\'Dinas Luar\',\'Alpa\') NOT NULL DEFAULT \'Hadir\'',
      'terlambat_menit' => 'INT(11) DEFAULT 0',
      'sync_status' => 'BOOLEAN DEFAULT 1',
    ),
  ),
  'absensi_siswa' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `absensi_siswa` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `siswa_id` INT(11) NOT NULL,
  `tanggal` DATE NOT NULL,
  `waktu_scan` TIME NOT NULL,
  `status` ENUM(\'Hadir\',\'Sakit\',\'Izin\',\'Alpa\') NOT NULL DEFAULT \'Hadir\',
  `sync_status` BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'INT(11) AUTO_INCREMENT PRIMARY KEY',
      'siswa_id' => 'INT(11) NOT NULL',
      'tanggal' => 'DATE NOT NULL',
      'waktu_scan' => 'TIME NOT NULL',
      'status' => 'ENUM(\'Hadir\',\'Sakit\',\'Izin\',\'Alpa\') NOT NULL DEFAULT \'Hadir\'',
      'sync_status' => 'BOOLEAN DEFAULT 1',
    ),
  ),
  'pengaturan_absensi' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `pengaturan_absensi` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `mode_siswa` ENUM(\'Normal\', \'Per Jam Pelajaran\') NOT NULL DEFAULT \'Normal\',
  `batas_jam_masuk_guru` TIME NOT NULL DEFAULT \'07:00:00\',
  `batas_jam_keluar_guru` TIME NOT NULL DEFAULT \'15:00:00\',
  `toleransi_terlambat_guru` INT(11) NOT NULL DEFAULT 15,
  `min_jam_pelajaran_siswa` INT(11) NOT NULL DEFAULT 4,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'INT(11) AUTO_INCREMENT PRIMARY KEY',
      'mode_siswa' => 'ENUM(\'Normal\', \'Per Jam Pelajaran\') NOT NULL DEFAULT \'Normal\'',
      'batas_jam_masuk_guru' => 'TIME NOT NULL DEFAULT \'07:00:00\'',
      'batas_jam_keluar_guru' => 'TIME NOT NULL DEFAULT \'15:00:00\'',
      'toleransi_terlambat_guru' => 'INT(11) NOT NULL DEFAULT 15',
      'min_jam_pelajaran_siswa' => 'INT(11) NOT NULL DEFAULT 4',
      'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    ),
  ),
  'pengaturan_absensi_guru' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `pengaturan_absensi_guru` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `guru_id` INT(11) NOT NULL,
  `batas_jam_masuk` TIME NULL DEFAULT NULL,
  `batas_jam_keluar` TIME NULL DEFAULT NULL,
  `toleransi_terlambat` INT(11) NULL DEFAULT NULL,
  UNIQUE KEY `guru_id` (`guru_id`),
  CONSTRAINT `pengaturan_absensi_guru_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'INT(11) AUTO_INCREMENT PRIMARY KEY',
      'guru_id' => 'INT(11) NOT NULL',
      'batas_jam_masuk' => 'TIME NULL DEFAULT NULL',
      'batas_jam_keluar' => 'TIME NULL DEFAULT NULL',
      'toleransi_terlambat' => 'INT(11) NULL DEFAULT NULL',
    ),
  ),
  'absensi_siswa_detail' => 
  array (
    'create_sql' => 'CREATE TABLE IF NOT EXISTS `absensi_siswa_detail` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `siswa_id` INT(11) NOT NULL,
  `tanggal` DATE NOT NULL,
  `jam_ke` INT(11) NOT NULL,
  `guru_id` INT(11) NOT NULL,
  `waktu_scan` TIME NOT NULL,
  `status` ENUM(\'Hadir\',\'Sakit\',\'Izin\',\'Alpa\') NOT NULL DEFAULT \'Hadir\',
  `sync_status` BOOLEAN DEFAULT 1,
  UNIQUE KEY `siswa_tanggal_jam` (`siswa_id`, `tanggal`, `jam_ke`),
  CONSTRAINT `absensi_siswa_detail_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_siswa_detail_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    'columns' => 
    array (
      'id' => 'INT(11) AUTO_INCREMENT PRIMARY KEY',
      'siswa_id' => 'INT(11) NOT NULL',
      'tanggal' => 'DATE NOT NULL',
      'jam_ke' => 'INT(11) NOT NULL',
      'guru_id' => 'INT(11) NOT NULL',
      'waktu_scan' => 'TIME NOT NULL',
      'status' => 'ENUM(\'Hadir\',\'Sakit\',\'Izin\',\'Alpa\') NOT NULL DEFAULT \'Hadir\'',
      'sync_status' => 'BOOLEAN DEFAULT 1',
    ),
  ),
);
