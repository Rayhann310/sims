<?php
$db = new Database();
try {
    $db->query("SELECT * FROM pengaturan ORDER BY id ASC LIMIT 1");
    $pengaturan = $db->single() ?: [];
} catch (Throwable $e) {
    $pengaturan = [];
}
$app_name = $pengaturan ? htmlspecialchars($pengaturan['nama_aplikasi']) : 'SIAKAD';
$app_logo = $pengaturan ? htmlspecialchars($pengaturan['logo_teks']) : 'S';
// Make pengaturan available globally for this request
$GLOBALS['pengaturan'] = $pengaturan;

// Helper `hasMenuAccess` sekarang sudah ada di `app/core/HakAksesHelper.php` yang dimuat di `init.php`
$role = $_SESSION['user']['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> - <?= $app_name ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#1e3a8a', secondary: '#3b82f6', accent: '#f59e0b' }
                }
            }
        }
    </script>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.min.css" rel="stylesheet">
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased flex h-[100dvh] overflow-hidden" x-data="{ sidebarOpen: true, mobileOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileOpen" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden transition-opacity" @click="mobileOpen = false" style="display: none;"></div>

    <!-- Sidebar (White Minimalist) -->
    <aside :class="{'translate-x-0': mobileOpen, '-translate-x-full': !mobileOpen, 'lg:w-20': !sidebarOpen}" 
           class="fixed lg:static lg:translate-x-0 inset-y-0 left-0 z-50 bg-emerald-900 border-none transition-all duration-300 flex flex-col shadow-sm w-64">
        
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-between lg:justify-center px-4 border-b border-emerald-800">
            <div class="flex items-center gap-3 overflow-hidden">
                <?php if (!empty($pengaturan['logo_sekolah'])): ?>
                    <img src="<?= htmlspecialchars($pengaturan['logo_sekolah']) ?>" class="w-8 h-8 rounded-lg object-contain shrink-0 bg-white p-0.5">
                <?php else: ?>
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white font-bold shrink-0"><?= $app_logo ?></div>
                <?php endif; ?>
                <span x-show="sidebarOpen || mobileOpen" class="font-bold text-white tracking-wide truncate transition-opacity duration-300"><?= $app_name ?></span>
            </div>
            <!-- Close Mobile Button -->
            <button @click="mobileOpen = false" class="lg:hidden text-emerald-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 scrollbar-hide">
            
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Menu Utama</p>
                <a href="<?= BASEURL; ?>/dashboard" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Dasbor">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Dasbor</span>
                </a>
            </div>

            <?php if(hasMenuAccess('data_siswa') || hasMenuAccess('data_guru') || hasMenuAccess('orangtua') || hasMenuAccess('data_alumni')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Master Data</p>
                <div class="space-y-1">
                    <?php if(hasMenuAccess('data_siswa')): ?>
                    <a href="<?= BASEURL; ?>/siswa" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'siswa') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Data Siswa">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Data Siswa</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('data_guru')): ?>
                    <a href="<?= BASEURL; ?>/guru" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'guru') !== false && strpos($_SERVER['REQUEST_URI'], 'pengaturan') === false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Data Guru">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Data Guru</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('orangtua')): ?>
                    <a href="<?= BASEURL; ?>/orangtua" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'orangtua') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Data Orang Tua">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Data Orang Tua</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('data_alumni')): ?>
                    <a href="<?= BASEURL; ?>/alumni" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'alumni') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Data Alumni">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14v6"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Data Alumni</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if(hasMenuAccess('spmb') || hasMenuAccess('spmb_peserta') || hasMenuAccess('spmb_biaya')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">SPMB / PPDB</p>
                <div class="space-y-1">
                    <?php if(hasMenuAccess('spmb')): ?>
                    <a href="<?= BASEURL; ?>/adminspmb" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= ($_SERVER['REQUEST_URI'] == '/smanw/adminspmb' || $_SERVER['REQUEST_URI'] == '/smanw/adminspmb/') ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Gelombang & Info">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Gelombang & Info</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(hasMenuAccess('spmb_peserta')): ?>
                    <a href="<?= BASEURL; ?>/adminspmb/peserta" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/adminspmb/peserta') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Data Peserta">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Data Peserta</span>
                    </a>
                    <?php endif; ?>

                    <?php if(hasMenuAccess('spmb_biaya')): ?>
                    <a href="<?= BASEURL; ?>/adminspmb/biaya" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/adminspmb/biaya') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Biaya Pendaftaran">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Biaya Pendaftaran</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(hasMenuAccess('akademik_tahun') || hasMenuAccess('akademik_kelas') || hasMenuAccess('akademik_mapel') || hasMenuAccess('jabatan') || hasMenuAccess('rombel') || hasMenuAccess('naik_kelas') || hasMenuAccess('jadwal') || hasMenuAccess('elearning') || hasMenuAccess('nilai')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Akademik</p>
                <div class="space-y-1">
                    
                    <?php if(hasMenuAccess('akademik_tahun') || hasMenuAccess('akademik_kelas') || hasMenuAccess('akademik_mapel') || hasMenuAccess('jabatan')): ?>
                    <!-- Dropdown Master Akademik -->
                    <div x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors group text-emerald-100/70 hover:bg-emerald-800 hover:text-white">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Master Akademik</span>
                            </div>
                            <svg x-show="sidebarOpen || mobileOpen" :class="{'rotate-180': expanded}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="expanded && (sidebarOpen || mobileOpen)" x-collapse class="pl-11 pr-3 py-1 space-y-1">
                            <?php if(hasMenuAccess('akademik_tahun')): ?>
                            <a href="<?= BASEURL; ?>/akademik/tahun" class="block py-2 text-sm text-slate-500 hover:text-blue-600 transition-colors">Tahun Akademik</a>
                            <?php endif; ?>
                            <?php if(hasMenuAccess('akademik_kelas')): ?>
                            <a href="<?= BASEURL; ?>/akademik/kelas" class="block py-2 text-sm text-slate-500 hover:text-blue-600 transition-colors">Tingkat Kelas</a>
                            <a href="<?= BASEURL; ?>/akademik/jurusan" class="block py-2 text-sm text-slate-500 hover:text-blue-600 transition-colors">Master Jurusan</a>
                            <?php endif; ?>
                            <?php if(hasMenuAccess('akademik_mapel')): ?>
                            <a href="<?= BASEURL; ?>/akademik/mapel" class="block py-2 text-sm text-slate-500 hover:text-blue-600 transition-colors">Mata Pelajaran</a>
                            <?php endif; ?>
                            <?php if(hasMenuAccess('jabatan')): ?>
                            <a href="<?= BASEURL; ?>/jabatan" class="block py-2 text-sm <?= (strpos($_SERVER['REQUEST_URI'], '/jabatan') !== false) ? 'text-emerald-400 font-semibold' : 'text-slate-500 hover:text-blue-600' ?> transition-colors">Jabatan Guru</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(hasMenuAccess('rombel')): ?>
                    <a href="<?= BASEURL; ?>/akademik/rombel" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'akademik/rombel') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Rombongan Belajar">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Rombel & Siswa</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('naik_kelas')): ?>
                    <a href="<?= BASEURL; ?>/akademik/naikKelas" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'akademik/naikKelas') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Kenaikan Kelas">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Naik Kelas</span>
                    </a>
                    <?php endif; ?>

                    <?php if(hasMenuAccess('jadwal')): ?>
                    <a href="<?= BASEURL; ?>/jadwal" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group text-emerald-100/70 hover:bg-emerald-800 hover:text-white" title="Jadwal Pelajaran">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Jadwal Pelajaran</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('elearning')): ?>
                    <a href="<?= BASEURL; ?>/elearning" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group text-emerald-100/70 hover:bg-emerald-800 hover:text-white" title="E-Learning">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">E-Learning</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('nilai')): ?>
                    <a href="<?= BASEURL; ?>/nilai" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/nilai') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Presensi & Nilai">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Presensi & Nilai</span>
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION['user']['role'] === 'guru'): ?>
                    <?php
                    $showAbsensiKelas = true;
                    // Check mode absen
                    $db_absen = new Database();
                    $db_absen->query("SELECT mode_siswa FROM pengaturan_absensi ORDER BY id ASC LIMIT 1");
                    $pengaturan_absensi = $db_absen->single();
                    if ($pengaturan_absensi && $pengaturan_absensi['mode_siswa'] === 'Normal') {
                        $showAbsensiKelas = hasMenuAccess('absensi_kelas');
                    }
                    ?>
                    <?php if ($showAbsensiKelas): ?>
                    <a href="<?= BASEURL; ?>/ScannerKelas" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/ScannerKelas') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Absensi Kelas">
                        <i class="fas fa-qrcode w-5 h-5 text-center transition-colors group-hover:text-white <?= (strpos($_SERVER['REQUEST_URI'], '/ScannerKelas') !== false) ? 'text-white' : 'text-emerald-300' ?>"></i>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Absensi Kelas</span>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(hasMenuAccess('keuangan_tarif') || hasMenuAccess('keuangan_tagihan') || hasMenuAccess('keuangan_riwayat') || hasMenuAccess('keuangan_bukukas')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Keuangan</p>
                <div class="space-y-1">
                    <?php if(hasMenuAccess('keuangan_tarif')): ?>
                    <a href="<?= BASEURL; ?>/keuangan/tarif" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/keuangan/tarif') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Master Tarif">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Master Tarif</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('keuangan_tagihan')): ?>
                    <a href="<?= BASEURL; ?>/keuangan/tagihan" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/keuangan/tagihan') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Tagihan & Pembayaran">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Tagihan & Pembayaran</span>
                    </a>
                    <a href="<?= BASEURL; ?>/keuangan/tunggakan" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/keuangan/tunggakan') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Tunggakan SPP">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Tunggakan SPP</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('keuangan_riwayat')): ?>
                    <a href="<?= BASEURL; ?>/keuangan/riwayat" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/keuangan/riwayat') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Riwayat Bayar">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Riwayat Bayar</span>
                    </a>
                    <?php endif; ?>
                    <?php if(hasMenuAccess('keuangan_bukukas')): ?>
                    <a href="<?= BASEURL; ?>/keuangan/bukuKas" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/keuangan/bukuKas') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Buku Kas & Analisa">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Buku Kas & Analisa</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(hasMenuAccess('cbt_bank_soal') || hasMenuAccess('cbt_jadwal') || hasMenuAccess('cbt_proctor') || hasMenuAccess('cbt_setor_soal') || hasMenuAccess('cbt_ujian_siswa')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Ujian / CBT</p>
                <div class="space-y-1">
                    <?php if(hasMenuAccess('cbt_ujian_siswa')): ?>
                    <a href="<?= BASEURL; ?>/UjianSiswa" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/UjianSiswa') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Ujian CBT Siswa">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Ujian CBT Siswa</span>
                    </a>
                    <?php endif; ?>

                    <?php if(hasMenuAccess('cbt_bank_soal')): ?>
                    <a href="<?= BASEURL; ?>/BankSoal" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/BankSoal') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Bank Soal CBT">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Bank Soal CBT</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(hasMenuAccess('cbt_setor_soal')): ?>
                    <a href="<?= BASEURL; ?>/SetorSoal" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/SetorSoal') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Setor Soal Ujian">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Setor Soal Ujian</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(hasMenuAccess('cbt_jadwal')): ?>
                    <a href="<?= BASEURL; ?>/JadwalUjian" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/JadwalUjian') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Jadwal & Pengawas">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Jadwal & Pengawas</span>
                    </a>
                    <?php endif; ?>

                    <?php if(hasMenuAccess('cbt_proctor')): ?>
                    <a href="<?= BASEURL; ?>/Proctor" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/Proctor') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Dashboard Pengawas">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Dashboard Pengawas</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(hasMenuAccess('pengumuman') || hasMenuAccess('pesan')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Komunikasi</p>
                <?php if(hasMenuAccess('pengumuman')): ?>
                <a href="<?= BASEURL; ?>/komunikasi/pengumuman" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/komunikasi/pengumuman') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Pengumuman">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Pengumuman</span>
                </a>
                <?php endif; ?>
                <?php if(hasMenuAccess('pesan')): ?>
                <a href="<?= BASEURL; ?>/komunikasi/pesan" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/komunikasi/pesan') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Pesan Masuk">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Pesan Masuk</span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if(hasMenuAccess('kedisiplinan') || hasMenuAccess('ked_kategori') || hasMenuAccess('ked_riwayat_siswa')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Kedisiplinan</p>
                <div class="space-y-1">
                    <?php if(hasMenuAccess('kedisiplinan')): ?>
                    <a href="<?= BASEURL; ?>/kedisiplinan/rekap" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/kedisiplinan/rekap') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Rekap & Catatan">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Rekap & Catatan</span>
                    </a>
                    <?php endif; ?>

                    <?php if(hasMenuAccess('ked_riwayat_siswa')): ?>
                    <a href="<?= BASEURL; ?>/kedisiplinan/riwayat" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/kedisiplinan/riwayat') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Riwayat Saya">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Riwayat Saya</span>
                    </a>
                    <?php endif; ?>

                    <?php if(hasMenuAccess('ked_kategori')): ?>
                    <a href="<?= BASEURL; ?>/kedisiplinan/kategori" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/kedisiplinan/kategori') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Master Kategori">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Master Kategori</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(hasMenuAccess('kearsipan')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Kearsipan & TU</p>
                <a href="<?= BASEURL; ?>/kearsipan" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], '/kearsipan') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Data Surat">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Data Surat</span>
                </a>
            </div>
            <?php endif; ?>

            <?php if(hasMenuAccess('pengaturan') || hasMenuAccess('hak_akses')): ?>
            <div class="mb-6">
                <p x-show="sidebarOpen || mobileOpen" class="px-3 text-xs font-semibold text-emerald-400/60 uppercase tracking-wider mb-2">Sistem</p>
                <?php if(hasMenuAccess('pengaturan')): ?>
                <a href="<?= BASEURL; ?>/pengaturan" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'pengaturan') !== false && strpos($_SERVER['REQUEST_URI'], 'fonntelog') === false && strpos($_SERVER['REQUEST_URI'], 'hakAkses') === false && strpos($_SERVER['REQUEST_URI'], 'PengaturanAbsensi') === false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Pengaturan">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Pengaturan Utama</span>
                </a>
                <a href="<?= BASEURL; ?>/PengaturanAbsensi" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'PengaturanAbsensi') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Pengaturan Absensi">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Pengaturan Absensi</span>
                </a>
                <a href="<?= BASEURL; ?>/pengaturan/fonntelog" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'fonntelog') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Debug Fonnte">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Debug Fonnte API</span>
                </a>
                <?php endif; ?>
                
                <?php if(hasMenuAccess('hak_akses')): ?>
                <a href="<?= BASEURL; ?>/hakAkses" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group <?= (strpos($_SERVER['REQUEST_URI'], 'hakAkses') !== false) ? 'bg-emerald-800 text-white' : 'text-emerald-100/70 hover:bg-emerald-800 hover:text-white' ?>" title="Hak Akses Menu">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span x-show="sidebarOpen || mobileOpen" class="ml-3 font-medium whitespace-nowrap">Hak Akses Menu</span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Sidebar Footer / Kata Mutiara -->
            <div x-show="sidebarOpen || mobileOpen" class="mt-auto pt-8 pb-4 px-4 text-center transition-opacity duration-300">
                <div class="bg-emerald-800/40 rounded-xl p-4 border border-emerald-700/50 shadow-inner">
                    <p class="text-[11px] font-medium text-emerald-100 italic leading-relaxed">
                        "Sepandai pandai tupai melompat tetap tidak bisa main karet."
                    </p>
                    <p class="text-[10px] text-emerald-300/80 mt-2 font-semibold tracking-wide uppercase">— meigovic suparta</p>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc]">
        <!-- Navbar (White Minimalist) -->
        <header class="h-16 bg-emerald-800 flex items-center justify-between px-4 lg:px-8 z-30 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <!-- Toggle Desktop -->
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex items-center justify-center w-8 h-8 rounded-md text-emerald-100 hover:bg-emerald-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>
                <!-- Toggle Mobile -->
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden flex items-center justify-center w-8 h-8 rounded-md text-emerald-100 hover:bg-emerald-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <!-- Page Title -->
                <h1 class="text-white font-semibold text-lg hidden sm:block"><?= $data['judul']; ?></h1>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Notifications -->
                <div x-data="notifikasiComponent()" x-init="init()" class="relative">
                    <button @click="open = !open" class="w-8 h-8 flex items-center justify-center text-emerald-100 hover:text-white transition-colors relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span x-show="count > 0" x-text="count" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white text-[9px] text-white flex items-center justify-center font-bold"></span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-[-10px] sm:right-0 mt-2 w-[90vw] sm:w-80 max-w-[320px] bg-white rounded-lg shadow-xl border border-slate-100 py-2 z-50" style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                            <span class="text-xs text-slate-500" x-text="count + ' Baru'"></span>
                        </div>
                        <div class="max-h-80 overflow-y-auto scrollbar-hide">
                            <template x-if="items.length === 0">
                                <div class="px-4 py-6 text-center text-sm text-slate-500">Tidak ada notifikasi baru</div>
                            </template>
                            <template x-for="item in items" :key="item.id">
                                <a :href="item.link" @click.prevent="markReadAndGo(item)" class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 transition-colors">
                                    <p class="text-xs font-semibold text-slate-800 mb-0.5 uppercase tracking-wider" x-text="item.tipe.replace('_', ' ')"></p>
                                    <p class="text-sm text-slate-600 leading-snug line-clamp-2" x-text="item.pesan"></p>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-emerald-700 mx-1"></div>

                <!-- Profile -->
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block max-w-[200px]">
                        <p class="text-sm font-medium text-white truncate"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? 'User'); ?></p>
                        <p class="text-[11px] text-emerald-100/70 uppercase font-bold tracking-wider truncate" title="<?= htmlspecialchars($_SESSION['user']['role'] ?? 'Role'); ?><?= !empty($GLOBALS['guruJabatanNames']) ? ' - ' . htmlspecialchars($GLOBALS['guruJabatanNames']) : '' ?>">
                            <?= htmlspecialchars($_SESSION['user']['role'] ?? 'Role'); ?>
                            <?php if(!empty($GLOBALS['guruJabatanNames'])): ?>
                                <span class="font-normal opacity-80">- <?= htmlspecialchars($GLOBALS['guruJabatanNames']) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-emerald-700 flex items-center justify-center text-white font-bold border border-emerald-600">
                        <?= substr($_SESSION['user']['nama_lengkap'] ?? 'U', 0, 1); ?>
                    </div>
                </div>
                
                <a href="<?= BASEURL; ?>/profil" class="ml-2 w-8 h-8 flex items-center justify-center text-emerald-100 hover:text-white hover:bg-emerald-700 rounded-md transition-colors" title="Pengaturan Profil">
                    <i class="fas fa-cog text-lg"></i>
                </a>

                <a href="<?= BASEURL; ?>/login/logout" class="ml-2 w-8 h-8 flex items-center justify-center text-emerald-100 hover:text-white hover:bg-emerald-700 rounded-md transition-colors" title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </header>

        <!-- Main Page Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 relative">
