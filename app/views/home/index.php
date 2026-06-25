<!-- Hero Section -->
<section id="beranda" class="relative bg-white pt-20 pb-16 lg:pt-32 lg:pb-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            
            <!-- Text Content -->
            <div class="lg:w-1/2 text-center lg:text-left z-10">
                <p class="text-emerald-700 font-semibold tracking-wider text-sm mb-4 uppercase">Sistem Informasi Sekolah</p>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Kelola Sekolah <br class="hidden lg:block">
                    Lebih Mudah, Cepat <br class="hidden lg:block">
                    dan <span class="text-emerald-600">Terintegrasi</span>
                </h1>
                <p class="text-lg text-slate-600 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Sistem Informasi Sekolah SMA Nahdlatul Wathan Jakarta membantu mengelola data akademik, keuangan, kesiswaan, kedisiplinan dan arsip dalam satu platform terintegrasi.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="<?= BASEURL; ?>/login" class="bg-primary hover:bg-emerald-950 text-white px-8 py-4 rounded-lg font-semibold text-base transition-colors flex items-center justify-center gap-2">
                        Masuk ke Sistem
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#fitur" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 py-4 rounded-lg font-semibold text-base transition-colors flex items-center justify-center gap-2">
                        Pelajari Lebih Lanjut
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Image Content -->
            <div class="lg:w-1/2 relative z-10 w-full mt-10 lg:mt-0 flex justify-center">
                <div class="relative w-full max-w-md mx-auto h-[500px]">
                    <!-- The Image -->
                    <img src="<?= BASEURL; ?>/img/kepsek.png" alt="Kepala Sekolah SMA Nahdlatul Wathan Jakarta" class="w-full h-full object-cover object-top rounded-t-3xl" onerror="this.src='https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop'">
                    
                    <!-- White Gradient Overlay at the Bottom -->
                    <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
                </div>
                
                <!-- Floating Badge -->
                <div class="absolute bottom-4 right-0 lg:-right-10 bg-white p-4 rounded-xl shadow-xl flex items-center gap-4 max-w-xs border border-slate-100 z-20">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-snug">Berakhlak Mulia, Berprestasi, dan Siap Menghadapi Masa Depan</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Features Section (Modul Terintegrasi) -->
<section id="fitur" class="py-20 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-slate-800">Modul Terintegrasi</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Modul 1: Manajemen Siswa -->
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Manajemen Siswa</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Kelola data siswa, wali kelas, jurusan, dan informasi akademik.
                </p>
                <a href="<?= BASEURL; ?>/login" class="text-emerald-600 group-hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Modul 2: Manajemen Kelas -->
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Manajemen Kelas</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Atur kelas, jadwal pelajaran, wali kelas, dan data akademik.
                </p>
                <a href="<?= BASEURL; ?>/login" class="text-emerald-600 group-hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Modul 3: Modul Kedisiplinan -->
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Modul Kedisiplinan</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Catat pelanggaran, poin siswa, pembinaan, dan riwayat sanksi.
                </p>
                <a href="<?= BASEURL; ?>/login" class="text-emerald-600 group-hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Modul 4: Keuangan SPP -->
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Keuangan SPP</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Kelola tagihan, pembayaran, riwayat transaksi, dan laporan.
                </p>
                <a href="<?= BASEURL; ?>/login" class="text-emerald-600 group-hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Modul 5: Kearsipan -->
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Kearsipan</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Simpan dan kelola dokumen sekolah secara digital.
                </p>
                <a href="<?= BASEURL; ?>/login" class="text-emerald-600 group-hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Modul 6: Pengaduan -->
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Pengaduan</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Sampaikan pengaduan secara online dan pantau statusnya.
                </p>
                <a href="<?= BASEURL; ?>/login" class="text-emerald-600 group-hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-10 bg-slate-50 mb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-100 rounded-2xl flex flex-wrap justify-between items-center px-8 py-8 gap-6 text-center lg:text-left">
            
            <div class="flex items-center gap-4 mx-auto lg:mx-0">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-slate-900">850+</h4>
                    <p class="text-xs text-slate-500 font-medium">Siswa Aktif</p>
                </div>
            </div>

            <div class="flex items-center gap-4 mx-auto lg:mx-0">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-slate-900">60+</h4>
                    <p class="text-xs text-slate-500 font-medium">Guru & Staf</p>
                </div>
            </div>

            <div class="flex items-center gap-4 mx-auto lg:mx-0">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-slate-900">12</h4>
                    <p class="text-xs text-slate-500 font-medium">Kelas</p>
                </div>
            </div>

            <div class="flex items-center gap-4 mx-auto lg:mx-0">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-slate-900">1.320+</h4>
                    <p class="text-xs text-slate-500 font-medium">Dokumen Arsip</p>
                </div>
            </div>

            <div class="flex items-center gap-4 mx-auto lg:mx-0">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-slate-900">126</h4>
                    <p class="text-xs text-slate-500 font-medium">Pengaduan Ditangani</p>
                </div>
            </div>

        </div>
    </div>
</section>
