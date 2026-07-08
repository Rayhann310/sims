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
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex flex-col" x-data="scannerData()">

    <!-- Header / Navbar -->
    <header class="bg-blue-600 text-white p-4 shadow-md flex justify-between items-center shrink-0">
        <div class="flex items-center gap-3">
            <i class="fas fa-camera text-2xl"></i>
            <div>
                <h1 class="font-bold text-xl leading-tight">Scanner Presensi Siswa</h1>
                <p class="text-xs text-blue-200">Arahkan QR Code ke kamera</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full" :class="isOnline ? 'text-emerald-300' : 'text-red-300'">
                <i class="fas fa-circle text-[10px]"></i>
                <span class="text-sm font-medium" x-text="isOnline ? 'Online' : 'Offline'"></span>
            </div>
            <a href="<?= BASEURL; ?>/dashboard" class="ml-4 p-2 text-blue-200 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-6 bg-slate-50 flex items-center justify-center">
        <div class="w-full max-w-lg">
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-200 text-center relative overflow-hidden">
                <!-- Overlay Success Flash -->
                <div x-show="flashSuccess" x-transition.opacity class="absolute inset-0 bg-emerald-500/90 z-20 flex flex-col items-center justify-center text-white" style="display: none;">
                    <i class="fas fa-check-circle text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold">Berhasil!</h2>
                    <p x-text="lastScannedName"></p>
                </div>

                <!-- Camera Error UI -->
                <div x-show="cameraError" class="absolute inset-0 z-30 bg-white flex flex-col items-center justify-center p-6 text-center" style="display: none;">
                    <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-video-slash text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Akses Kamera Ditolak</h3>
                    <p class="text-slate-500 text-sm mb-6">Pastikan Anda telah memberikan izin akses kamera pada browser Anda. (Harus menggunakan HTTPS atau localhost).</p>
                    <button @click="toggleCamera()" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors">
                        Coba Lagi
                    </button>
                </div>

                <h2 class="text-2xl font-bold text-slate-800 mb-2">Scan QR Code</h2>
                <p class="text-slate-500 text-sm mb-6">Posisikan QR Code siswa di dalam kotak pemindai.</p>
                
                <div class="rounded-2xl overflow-hidden border-4 border-slate-100 shadow-inner bg-black aspect-square relative w-full mx-auto" id="reader">
                    <!-- HTML5 QR Code injects here -->
                </div>

                <div class="mt-6 flex justify-center gap-3">
                    <button @click="toggleCamera()" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-colors shadow-sm" :class="isScanning ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-blue-600 text-white hover:bg-blue-700'">
                        <i class="fas" :class="isScanning ? 'fa-stop' : 'fa-play'"></i>
                        <span x-text="isScanning ? 'Hentikan Kamera' : 'Mulai Scanner'"></span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Sync Indicator -->
    <div x-show="pendingSync.length > 0" x-transition class="fixed bottom-6 right-6 bg-slate-800 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 z-40" style="display: none;">
        <i class="fas fa-sync fa-spin text-blue-400"></i>
        <div class="text-sm">
            <p class="font-bold">Menyinkronkan Data...</p>
            <p class="text-slate-300 text-xs"><span x-text="pendingSync.length"></span> antrean tersimpan offline.</p>
        </div>
    </div>

    <script>
        const BASEURL = '<?= BASEURL; ?>';
    </script>
    <script src="<?= BASEURL; ?>/js/scanner-sync.js"></script>

</body>
</html>
