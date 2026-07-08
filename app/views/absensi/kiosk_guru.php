<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .clock { font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex flex-col" x-data="kioskData()">

    <!-- Header / Navbar -->
    <header class="bg-indigo-600 text-white p-4 shadow-md flex justify-between items-center shrink-0">
        <div class="flex items-center gap-3">
            <i class="fas fa-school text-2xl"></i>
            <div>
                <h1 class="font-bold text-xl leading-tight">Sistem Presensi Guru</h1>
                <p class="text-xs text-indigo-200">SMA Nahdlatul Wathan Jakarta</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="relative w-64">
                <input type="text" x-model="search" placeholder="Cari nama guru..." class="w-full pl-10 pr-4 py-2 bg-indigo-500/50 border border-indigo-400 rounded-full text-white placeholder-indigo-200 focus:ring-white focus:border-white outline-none">
                <i class="fas fa-search absolute left-4 top-3 text-indigo-200"></i>
            </div>
            <div class="flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full" :class="isOnline ? 'text-emerald-300' : 'text-red-300'">
                <i class="fas fa-circle text-[10px]"></i>
                <span class="text-sm font-medium" x-text="isOnline ? 'Online' : 'Offline'"></span>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold clock" x-text="currentTime"></div>
                <div class="text-xs text-indigo-200" x-text="currentDate"></div>
            </div>
            <a href="<?= BASEURL; ?>/dashboard" class="ml-4 p-2 text-indigo-200 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </header>

    <!-- Main Content (Dual Pane Grid) -->
    <main class="flex-1 overflow-hidden flex bg-slate-50">
        <!-- Left Pane: Belum Hadir -->
        <div class="w-1/2 h-full flex flex-col border-r border-slate-200">
            <div class="p-4 bg-white border-b border-slate-200 shrink-0">
                <h2 class="text-lg font-bold text-slate-800 flex items-center justify-between">
                    <span><i class="fas fa-clock text-amber-500 mr-2"></i> Belum Hadir</span>
                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full font-bold" x-text="belumHadirList.length"></span>
                </h2>
                <p class="text-slate-500 text-xs mt-1">Klik pada kartu untuk absen masuk (Satu klik otomatis Hadir).</p>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="g in belumHadirList" :key="g.id">
                        <button @click="openModal(g)" class="bg-white rounded-2xl p-4 flex flex-col items-center justify-center text-center gap-3 border shadow-sm transition-all hover:-translate-y-1 hover:shadow-md hover:border-indigo-300 focus:outline-none border-slate-200">
                            <div class="relative w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-slate-200">
                                <template x-if="g.foto">
                                    <img :src="g.foto" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!g.foto">
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 font-bold text-xl" x-text="g.nama_lengkap.charAt(0)"></div>
                                </template>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800 text-xs line-clamp-2 leading-tight" x-text="g.nama_lengkap"></p>
                            </div>
                        </button>
                    </template>
                </div>
                <div x-show="belumHadirList.length === 0" class="text-center py-12 text-slate-400">
                    <i class="fas fa-check-circle text-4xl mb-3 text-emerald-300"></i>
                    <p>Semua guru sudah hadir.</p>
                </div>
            </div>
        </div>

        <!-- Right Pane: Sudah Hadir -->
        <div class="w-1/2 h-full flex flex-col">
            <div class="p-4 bg-white border-b border-slate-200 shrink-0">
                <h2 class="text-lg font-bold text-slate-800 flex items-center justify-between">
                    <span><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Sudah Hadir / Tercatat</span>
                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-bold" x-text="sudahHadirList.length"></span>
                </h2>
                <p class="text-slate-500 text-xs mt-1">Klik pada kartu jika ingin mencatat jam pulang atau info lainnya.</p>
            </div>
            <div class="flex-1 overflow-y-auto p-4 bg-emerald-50/30">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="g in sudahHadirList" :key="g.id">
                        <button @click="openModal(g)" class="bg-white rounded-2xl p-4 flex flex-col items-center justify-center text-center gap-3 border shadow-sm transition-all hover:-translate-y-1 focus:outline-none" :class="getCardClass(g.id)">
                            <div class="relative w-16 h-16 rounded-full overflow-hidden shrink-0 border-2" :class="getBorderClass(g.id)">
                                <template x-if="g.foto">
                                    <img :src="g.foto" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!g.foto">
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 font-bold text-xl" x-text="g.nama_lengkap.charAt(0)"></div>
                                </template>
                                
                                <!-- Badge Status -->
                                <template x-if="absensi[g.id]">
                                    <div class="absolute bottom-0 right-0 w-5 h-5 rounded-full flex items-center justify-center border-2 border-white" :class="getBadgeClass(g.id)">
                                        <i class="fas fa-check text-[10px] text-white" x-show="absensi[g.id].status == 'Hadir'"></i>
                                        <i class="fas fa-medkit text-[10px] text-white" x-show="absensi[g.id].status == 'Sakit'"></i>
                                        <i class="fas fa-envelope text-[10px] text-white" x-show="absensi[g.id].status == 'Izin'"></i>
                                        <i class="fas fa-briefcase text-[10px] text-white" x-show="absensi[g.id].status == 'Dinas Luar'"></i>
                                    </div>
                                </template>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800 text-xs line-clamp-2 leading-tight" x-text="g.nama_lengkap"></p>
                                <p class="text-[10px] text-slate-500 mt-1 font-mono bg-white/50 px-1 rounded inline-block" x-text="absensi[g.id].waktu_masuk"></p>
                            </div>
                        </button>
                    </template>
                </div>
                <div x-show="sudahHadirList.length === 0" class="text-center py-12 text-slate-400">
                    <i class="fas fa-user-clock text-4xl mb-3 text-slate-300"></i>
                    <p>Belum ada guru yang hadir.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Absen (Hanya untuk yang sudah absen / Pulang) -->
    <div x-show="selectedGuru !== null" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div x-show="selectedGuru !== null" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
        
        <div x-show="selectedGuru !== null" x-transition.scale class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-200">
            <template x-if="selectedGuru">
                <div>
                    <!-- Header Modal -->
                    <div class="bg-indigo-600 p-6 flex flex-col items-center text-center text-white relative">
                        <button @click="closeModal()" class="absolute top-4 right-4 text-indigo-200 hover:text-white">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-indigo-300 bg-white mb-3">
                            <template x-if="selectedGuru.foto">
                                <img :src="selectedGuru.foto" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!selectedGuru.foto">
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-500 font-bold text-3xl" x-text="selectedGuru.nama_lengkap.charAt(0)"></div>
                            </template>
                        </div>
                        <h3 class="text-xl font-bold" x-text="selectedGuru.nama_lengkap"></h3>
                        <p class="text-indigo-200 text-sm" x-text="selectedGuru.nip || 'NIP/NUPTK: -'"></p>
                    </div>

                    <!-- Content Modal -->
                    <div class="p-6 bg-slate-50">
                        <template x-if="absensi[selectedGuru.id]">
                            <!-- Sudah Absen Masuk -->
                            <div class="text-center">
                                <div class="mb-4 inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-check text-3xl"></i>
                                </div>
                                <h4 class="text-lg font-bold text-slate-800 mb-1">Status: <span x-text="absensi[selectedGuru.id].status"></span></h4>
                                <p class="text-slate-500 text-sm mb-6">Waktu Masuk: <span class="font-bold" x-text="absensi[selectedGuru.id].waktu_masuk"></span></p>
                                
                                <template x-if="!absensi[selectedGuru.id].waktu_pulang && absensi[selectedGuru.id].status == 'Hadir'">
                                    <button @click="submitAbsen('Pulang')" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold shadow-lg shadow-slate-200 transition-all">
                                        Catat Waktu Pulang
                                    </button>
                                </template>
                                <template x-if="absensi[selectedGuru.id].waktu_pulang">
                                    <div class="p-3 bg-slate-200 text-slate-600 rounded-xl font-medium text-sm">
                                        Sudah Absen Pulang pada <span x-text="absensi[selectedGuru.id].waktu_pulang"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Sync Indicator -->
    <div x-show="pendingSync.length > 0" x-transition class="fixed bottom-6 right-6 bg-slate-800 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 z-40" style="display: none;">
        <i class="fas fa-sync fa-spin text-indigo-400"></i>
        <div class="text-sm">
            <p class="font-bold">Menyinkronkan Data...</p>
            <p class="text-slate-300 text-xs"><span x-text="pendingSync.length"></span> antrean tersimpan offline.</p>
        </div>
    </div>

    <!-- Notifikasi Toast -->
    <div x-show="toast.show" x-transition class="fixed top-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-full shadow-lg flex items-center gap-3 font-medium text-sm" :class="toast.type === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'" style="display: none;">
        <i class="fas" :class="toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
        <span x-text="toast.message"></span>
    </div>

    <!-- Gunakan Base URL agar Alpine bisa request ke endpoint -->
    <script>
        const BASEURL = '<?= BASEURL; ?>';
        const GURU_LIST = <?= json_encode($data['guru']); ?>;
        const INITIAL_ABSENSI = <?= json_encode($data['absensi']); ?>;
    </script>
    <script src="<?= BASEURL; ?>/public/js/kiosk-sync.js?v=<?= time(); ?>"></script>

</body>
</html>
