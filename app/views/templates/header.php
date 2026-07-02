<?php
$db = new Database();
try {
    $db->query("SELECT * FROM pengaturan ORDER BY id ASC LIMIT 1");
    $pengaturan = $db->single() ?: [];
} catch (Exception $e) {
    $pengaturan = [];
}
$app_name = $pengaturan ? htmlspecialchars($pengaturan['nama_aplikasi']) : 'SMA NAHDLATUL WATHAN JAKARTA';
$app_logo = (!empty($pengaturan['logo_sekolah'])) ? htmlspecialchars($pengaturan['logo_sekolah']) : BASEURL . '/img/logo.png';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> - <?= $app_name ?></title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#064e3b', // emerald-900
                        secondary: '#047857', // emerald-700
                        accent: '#facc15', // yellow-400
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white font-sans text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <?php if (!isset($data['hide_navbar']) || !$data['hide_navbar']): ?>
    <nav x-data="{ mobileMenuOpen: false }" class="bg-primary w-full z-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center gap-4">
                    <img src="<?= $app_logo ?>" alt="Logo" class="w-12 h-12 object-contain bg-white rounded-full p-1" onerror="this.src='https://ui-avatars.com/api/?name=NW&background=fff&color=064e3b'">
                    <div>
                        <h1 class="text-white font-bold text-lg tracking-wide leading-tight"><?= $app_name ?></h1>
                        <p class="text-accent text-xs font-medium tracking-wide">Religius • Nasionalis • Berkualitas</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center text-sm font-medium text-emerald-50">
                    <a href="<?= BASEURL; ?>/" class="hover:text-white transition-colors">Beranda</a>
                    <a href="#tentang" class="hover:text-white transition-colors">Tentang</a>
                    <a href="#fitur" class="hover:text-white transition-colors">Fitur</a>
                    <a href="#berita" class="hover:text-white transition-colors">Berita</a>
                    <a href="#kontak" class="hover:text-white transition-colors">Kontak</a>
                    
                    <a href="<?= BASEURL; ?>/login" class="flex items-center gap-2 border border-emerald-500/50 hover:bg-emerald-800 text-white px-5 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Masuk
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-primary border-t border-emerald-800 mt-4" style="display: none;">
            <div class="px-4 py-4 space-y-2">
                <a href="<?= BASEURL; ?>/" class="block px-3 py-2 text-white font-medium hover:bg-emerald-800 rounded-md">Beranda</a>
                <a href="#tentang" class="block px-3 py-2 text-white font-medium hover:bg-emerald-800 rounded-md">Tentang</a>
                <a href="#fitur" class="block px-3 py-2 text-white font-medium hover:bg-emerald-800 rounded-md">Fitur</a>
                <a href="#berita" class="block px-3 py-2 text-white font-medium hover:bg-emerald-800 rounded-md">Berita</a>
                <a href="#kontak" class="block px-3 py-2 text-white font-medium hover:bg-emerald-800 rounded-md">Kontak</a>
                <a href="<?= BASEURL; ?>/login" class="block px-3 py-2 text-white font-medium hover:bg-emerald-800 rounded-md mt-4 border border-emerald-700 text-center">Masuk ke Sistem</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="flex-grow">
