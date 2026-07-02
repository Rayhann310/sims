<!-- Hero Section -->
<section id="beranda" class="relative bg-white pt-20 pb-16 lg:pt-32 lg:pb-24 overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-emerald-50 blur-3xl opacity-60"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-emerald-100 blur-3xl opacity-40"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            
            <!-- Text Content -->
            <div class="lg:w-1/2 text-center lg:text-left z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-sm mb-6 border border-emerald-200 shadow-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                    </span>
                    Sistem Informasi & SPMB Terpadu
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Langkah Awal <br class="hidden lg:block">
                    Masa Depan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Gemilang</span>
                </h1>
                <p class="text-lg text-slate-600 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Bergabunglah bersama SMA Nahdlatul Wathan Jakarta. Kami mendidik generasi yang berprestasi, berakhlak mulia, dan siap menghadapi tantangan global. Pendaftaran siswa baru kini lebih mudah!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <?php if (!empty($data['gelombang_aktif'])): ?>
                        <a href="<?= BASEURL; ?>/spmb" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-semibold text-base transition-all transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-2">
                            Daftar SPMB Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    <?php else: ?>
                        <a href="#spmb-info" class="bg-slate-800 hover:bg-slate-900 text-white px-8 py-4 rounded-xl font-semibold text-base transition-all transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-2">
                            Info Pendaftaran
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASEURL; ?>/login" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 py-4 rounded-xl font-semibold text-base transition-all flex items-center justify-center gap-2">
                        Login Sistem
                    </a>
                </div>
            </div>

            <!-- Image Content -->
            <div class="lg:w-1/2 relative z-10 w-full mt-10 lg:mt-0 flex justify-center perspective-1000">
                <div class="relative w-full max-w-md mx-auto transform transition-transform duration-500 hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-gradient-to-tr from-emerald-400 to-teal-300 rounded-[2.5rem] transform rotate-3 scale-105 opacity-20 -z-10"></div>
                    <img src="<?= BASEURL; ?>/img/kepsek.png" alt="Siswa SMA Nahdlatul Wathan Jakarta" class="w-full h-auto object-cover rounded-[2rem] shadow-2xl border-4 border-white" onerror="this.src='https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop'">
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-6 -left-6 lg:-left-10 bg-white p-5 rounded-2xl shadow-xl flex items-center gap-4 max-w-xs border border-slate-100 animate-bounce-slow">
                        <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 leading-snug">Kurikulum Modern & Berkarakter</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SPMB Banner Section -->
<section id="spmb-info" class="py-16 bg-slate-900 relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-3xl shadow-2xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 border border-emerald-400/30">
            <div class="text-white flex-1 text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Penerimaan Siswa Baru (SPMB)</h2>
                <?php if (!empty($data['gelombang_aktif'])): ?>
                    <p class="text-emerald-50 text-lg mb-4 opacity-90 max-w-xl">
                        Pendaftaran <strong><?= htmlspecialchars($data['gelombang_aktif']['nama_gelombang']); ?></strong> sedang dibuka! Segera daftarkan putra-putri Anda sebelum kuota penuh.
                    </p>
                    <div class="inline-flex items-center gap-3 bg-white/20 backdrop-blur-md px-6 py-3 rounded-xl border border-white/30">
                        <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div class="text-left">
                            <p class="text-xs text-emerald-100 uppercase tracking-wider font-semibold">Biaya Pendaftaran</p>
                            <p class="text-xl font-bold text-white">Rp <?= number_format($data['gelombang_aktif']['harga_formulir'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-emerald-50 text-lg opacity-90 max-w-xl">
                        Saat ini pendaftaran siswa baru belum dibuka. Silakan pantau terus website kami untuk informasi pendaftaran gelombang berikutnya.
                    </p>
                <?php endif; ?>
            </div>
            <div class="shrink-0">
                <?php if (!empty($data['gelombang_aktif'])): ?>
                    <a href="<?= BASEURL; ?>/spmb" class="bg-white text-emerald-700 hover:bg-emerald-50 px-8 py-4 rounded-xl font-bold text-lg transition-colors shadow-lg flex items-center gap-2 group">
                        Daftar Sekarang
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                <?php else: ?>
                    <button disabled class="bg-white/30 text-white cursor-not-allowed px-8 py-4 rounded-xl font-bold text-lg border border-white/20">
                        Pendaftaran Ditutup
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100">
            
            <div class="text-center px-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 transform rotate-3">
                    <svg class="w-6 h-6 text-emerald-600 transform -rotate-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-900 mb-1">850+</h4>
                <p class="text-sm text-slate-500 font-medium">Siswa Aktif</p>
            </div>

            <div class="text-center px-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 transform -rotate-3">
                    <svg class="w-6 h-6 text-emerald-600 transform rotate-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-900 mb-1">60+</h4>
                <p class="text-sm text-slate-500 font-medium">Guru & Staf</p>
            </div>

            <div class="text-center px-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 transform rotate-3">
                    <svg class="w-6 h-6 text-emerald-600 transform -rotate-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-900 mb-1">A</h4>
                <p class="text-sm text-slate-500 font-medium">Akreditasi</p>
            </div>

            <div class="text-center px-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 transform -rotate-3">
                    <svg class="w-6 h-6 text-emerald-600 transform rotate-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-900 mb-1">100%</h4>
                <p class="text-sm text-slate-500 font-medium">Lulusan Terbaik</p>
            </div>

        </div>
    </div>
</section>

<!-- Features Section --><!-- Section Biaya Pendaftaran SPMB -->
<?php if(!empty($data['kategori_biaya'])): ?>
<section id="biaya" class="py-20 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 max-w-3xl mx-auto">
            <span class="text-primary font-semibold tracking-wider uppercase text-sm mb-2 block">Informasi SPMB</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Rincian Biaya Pendaftaran</h2>
            <p class="text-slate-500 text-lg">Pilih kategori yang sesuai dengan Anda. Kami menyediakan transparansi penuh untuk seluruh komponen biaya pendidikan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-start justify-center">
            <?php foreach($data['kategori_biaya'] as $k): 
                $total_biaya = 0;
                foreach($k['rincian'] as $r) {
                    $total_biaya += $r['nominal'];
                }
            ?>
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 border border-slate-100 overflow-hidden flex flex-col h-full transform hover:-translate-y-1">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50 text-center">
                    <h3 class="text-xl font-bold text-slate-800 mb-2"><?= htmlspecialchars($k['nama_kategori']); ?></h3>
                    <?php if(!empty($k['deskripsi'])): ?>
                        <p class="text-sm text-slate-500 mb-6"><?= htmlspecialchars($k['deskripsi']); ?></p>
                    <?php endif; ?>
                    <div class="text-4xl font-extrabold text-primary mb-2">
                        <span class="text-xl text-slate-500 font-medium">Rp</span> <?= number_format($total_biaya, 0, ',', '.'); ?>
                    </div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Keseluruhan</p>
                </div>
                
                <div class="p-8 flex-grow">
                    <?php if(empty($k['rincian'])): ?>
                        <div class="text-center text-slate-400 italic text-sm py-4">Rincian belum tersedia.</div>
                    <?php else: ?>
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach($k['rincian'] as $r): ?>
                                <tr class="group">
                                    <td class="py-3 text-slate-600 group-hover:text-slate-900 transition-colors">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <?= htmlspecialchars($r['nama_rincian']); ?>
                                        </div>
                                    </td>
                                    <td class="py-3 text-right font-medium text-slate-700">Rp <?= number_format($r['nominal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <div class="p-8 pt-0 mt-auto">
                    <a href="<?= BASEURL; ?>/spmb" class="block w-full py-3 px-4 bg-slate-800 hover:bg-slate-900 text-white text-center font-medium rounded-xl transition-colors shadow-md hover:shadow-lg">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section id="fitur" class="py-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Sistem Terintegrasi Penuh</h2>
            <p class="text-slate-500">Selain sistem pendaftaran siswa baru, sekolah kami telah didukung infrastruktur digital yang lengkap untuk menunjang kegiatan belajar mengajar.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group border border-slate-100">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors duration-300">
                    <svg class="w-7 h-7 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Akademik & E-Learning</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Sistem pembelajaran digital, materi, tugas, ujian online, dan rekap nilai yang terpadu.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group border border-slate-100">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors duration-300">
                    <svg class="w-7 h-7 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Keuangan & SPP</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Pantau tagihan secara transparan dan lakukan pembayaran SPP dengan mudah.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group border border-slate-100">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors duration-300">
                    <svg class="w-7 h-7 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Kedisiplinan</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Pencatatan aktivitas siswa, penghargaan, dan pelanggaran yang dapat dipantau orang tua.
                </p>
            </div>
            
        </div>
    </div>
</section>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(-5%); }
        50% { transform: translateY(5%); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 3s ease-in-out infinite;
    }
    .perspective-1000 {
        perspective: 1000px;
    }
</style>
