<?php
// Ambil mode absensi siswa dari pengaturan
require_once 'app/models/PengaturanAbsensiModel.php';
$pamView = new PengaturanAbsensiModel();
$globalPengaturan = $pamView->getPengaturanGlobal();
$modeAbsen = $globalPengaturan['mode_absen_siswa'] ?? 'Masuk Saja';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0f172a; font-family: 'Inter', sans-serif; }

        /* Scanning frame animation */
        @keyframes scanline {
            0% { top: 0%; }
            50% { top: calc(100% - 4px); }
            100% { top: 0%; }
        }
        .scan-line {
            animation: scanline 2s linear infinite;
        }

        /* Corner brackets */
        .scanner-frame::before, .scanner-frame::after,
        .scanner-frame > span::before, .scanner-frame > span::after {
            content: '';
            position: absolute;
            width: 28px;
            height: 28px;
            border-color: #38bdf8;
            border-style: solid;
        }
        .scanner-frame::before { top: 0; left: 0; border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
        .scanner-frame::after  { top: 0; right: 0; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
        .scanner-frame > span::before { bottom: 0; left: 0; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
        .scanner-frame > span::after  { bottom: 0; right: 0; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

        #reader video { border-radius: 0 !important; }
        #reader { background: #000; }

        /* Flash overlay fade */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex flex-col" x-data="scannerData()">

    <?php if ($modeAbsen === 'Per Mata Pelajaran'): ?>
    <!-- BLOKIR: Mode Per Mata Pelajaran tidak menggunakan scanner ini -->
    <div class="flex-1 flex flex-col items-center justify-center bg-slate-900 text-white text-center p-8">
        <div class="w-20 h-20 rounded-full bg-amber-500/20 flex items-center justify-center mb-6">
            <i class="fas fa-ban text-amber-400 text-4xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-white mb-2">Mode Per Mata Pelajaran Aktif</h1>
        <p class="text-slate-400 text-sm max-w-sm">Scanner ini tidak digunakan. Absensi siswa dilakukan oleh guru di setiap kelas melalui menu <strong>Absensi Kelas</strong>.</p>
        <a href="<?= BASEURL; ?>/dashboard" class="mt-8 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>
    <?php else: ?>

    <!-- Header -->
    <header class="bg-slate-900 border-b border-slate-700/60 px-5 py-3.5 flex justify-between items-center shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-sky-500 flex items-center justify-center">
                <i class="fas fa-qrcode text-white text-sm"></i>
            </div>
            <div>
                <h1 class="font-bold text-white text-base leading-tight">Scanner Presensi Siswa</h1>
                <p class="text-xs text-slate-400">
                    Mode:
                    <?php if ($modeAbsen === 'Masuk & Pulang'): ?>
                        <span class="text-sky-400 font-semibold">Masuk &amp; Pulang</span>
                    <?php else: ?>
                        <span class="text-emerald-400 font-semibold">Masuk Saja</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                 :class="isOnline ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400'">
                <span class="w-1.5 h-1.5 rounded-full" :class="isOnline ? 'bg-emerald-400' : 'bg-red-400'"></span>
                <span x-text="isOnline ? 'Online' : 'Offline'"></span>
            </div>
            <a href="<?= BASEURL; ?>/dashboard" class="w-8 h-8 rounded-lg bg-slate-700 hover:bg-slate-600 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-slate-300 text-sm"></i>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-hidden flex items-center justify-center p-4 bg-slate-900">
        <div class="w-full max-w-sm">

            <!-- Card Scanner -->
            <div class="bg-slate-800 rounded-3xl border border-slate-700/60 shadow-2xl overflow-hidden relative">

                <!-- ===== OVERLAY: Success Masuk ===== -->
                <div x-show="flashSuccess && flashTipe === 'masuk'"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 z-30 bg-emerald-600/95 backdrop-blur-sm flex flex-col items-center justify-center text-white rounded-3xl">
                    <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mb-4">
                        <i class="fas fa-sign-in-alt text-4xl"></i>
                    </div>
                    <div class="text-xs font-bold uppercase tracking-widest text-emerald-200 mb-1">Presensi Masuk</div>
                    <h2 class="text-xl font-bold text-center px-6" x-text="lastScannedName"></h2>
                </div>

                <!-- ===== OVERLAY: Success Pulang ===== -->
                <div x-show="flashSuccess && flashTipe === 'pulang'"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 z-30 bg-violet-600/95 backdrop-blur-sm flex flex-col items-center justify-center text-white rounded-3xl">
                    <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mb-4">
                        <i class="fas fa-sign-out-alt text-4xl"></i>
                    </div>
                    <div class="text-xs font-bold uppercase tracking-widest text-violet-200 mb-1">Presensi Pulang</div>
                    <h2 class="text-xl font-bold text-center px-6" x-text="lastScannedName"></h2>
                </div>

                <!-- ===== OVERLAY: Error ===== -->
                <div x-show="flashError"
                     x-cloak
                     x-transition.opacity
                     class="absolute inset-0 z-30 bg-red-600/95 backdrop-blur-sm flex flex-col items-center justify-center text-white rounded-3xl">
                    <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-4xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-center px-6" x-text="lastScannedName"></h2>
                </div>

                <!-- ===== OVERLAY: Camera Error ===== -->
                <div x-show="cameraError"
                     x-cloak
                     class="absolute inset-0 z-40 bg-slate-800 flex flex-col items-center justify-center p-6 text-center rounded-3xl">
                    <div class="w-16 h-16 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-video-slash text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-white text-lg mb-2">Akses Kamera Ditolak</h3>
                    <p class="text-slate-400 text-sm mb-6">Pastikan izin kamera sudah diberikan. (Gunakan HTTPS atau localhost).</p>
                    <button @click="toggleCamera()" class="px-5 py-2.5 bg-sky-600 text-white rounded-xl font-bold text-sm hover:bg-sky-700 transition-colors">
                        <i class="fas fa-redo mr-1"></i> Coba Lagi
                    </button>
                </div>

                <!-- Header Card -->
                <div class="px-6 pt-6 pb-4 text-center">
                    <h2 class="text-lg font-bold text-white">Scan QR Code</h2>
                    <p class="text-slate-400 text-xs mt-1">
                        <?php if ($modeAbsen === 'Masuk & Pulang'): ?>
                            Scan pertama = <strong class="text-emerald-400">Masuk</strong>, scan kedua = <strong class="text-violet-400">Pulang</strong>
                        <?php else: ?>
                            Posisikan QR Code siswa di dalam kotak pemindai
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Scanner Area -->
                <div class="px-5 pb-5">
                    <div class="relative scanner-frame" style="aspect-ratio:1/1;">
                        <span></span>
                        <div class="w-full h-full rounded-2xl overflow-hidden bg-black" id="reader"></div>
                        <!-- Scan line -->
                        <div x-show="isScanning" class="scan-line absolute left-3 right-3 h-0.5 bg-sky-400/70 shadow-[0_0_8px_2px_rgba(56,189,248,0.4)] pointer-events-none" style="top:0;"></div>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <button @click="toggleCamera()"
                                class="flex-1 py-3 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2"
                                :class="isScanning
                                    ? 'bg-red-500/15 text-red-400 hover:bg-red-500/25 border border-red-500/30'
                                    : 'bg-sky-500 text-white hover:bg-sky-600 shadow-lg shadow-sky-500/20'">
                            <i class="fas" :class="isScanning ? 'fa-stop' : 'fa-play'"></i>
                            <span x-text="isScanning ? 'Hentikan' : 'Mulai Scanner'"></span>
                        </button>
                    </div>
                </div>

                <!-- Stats bar (Mode Masuk & Pulang) -->
                <?php if ($modeAbsen === 'Masuk & Pulang'): ?>
                <div class="px-5 pb-5 grid grid-cols-2 gap-3">
                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-4 py-3 text-center">
                        <i class="fas fa-sign-in-alt text-emerald-400 mb-1"></i>
                        <p class="text-xs text-emerald-400 font-semibold">Scan 1 = Masuk</p>
                    </div>
                    <div class="bg-violet-500/10 border border-violet-500/20 rounded-xl px-4 py-3 text-center">
                        <i class="fas fa-sign-out-alt text-violet-400 mb-1"></i>
                        <p class="text-xs text-violet-400 font-semibold">Scan 2 = Pulang</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Sync Indicator -->
    <div x-show="pendingSync.length > 0"
         x-cloak
         x-transition
         class="fixed bottom-5 right-5 bg-slate-800 border border-slate-600 text-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 z-40">
        <i class="fas fa-sync fa-spin text-sky-400"></i>
        <div class="text-sm">
            <p class="font-semibold">Menyinkronkan...</p>
            <p class="text-slate-400 text-xs"><span x-text="pendingSync.length"></span> antrean offline</p>
        </div>
    </div>

    <script>
        const BASEURL = '<?= BASEURL; ?>';
    </script>
    <script src="<?= BASEURL; ?>/public/js/scanner-sync.js"></script>

    <?php endif; ?>
</body>
</html>
