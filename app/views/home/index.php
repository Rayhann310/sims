<!-- Hero Section (PPDB) -->
<section class="bg-white overflow-hidden font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 border-b border-slate-100 pb-6">
            <div class="flex items-center gap-4">
                <?php if(!empty($data['pengaturan']['logo_sekolah'])): ?>
                    <img src="<?= $data['pengaturan']['logo_sekolah'] ?>" alt="Logo" class="w-16 h-16 object-contain bg-white rounded-full shadow-md p-1">
                <?php else: ?>
                    <div class="w-16 h-16 bg-emerald-700 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                        NW
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-lg md:text-xl font-bold text-slate-800 leading-tight whitespace-nowrap">SMA NAHDLATUL WATHAN JAKARTA</h1>
                    <p class="text-emerald-700 font-semibold italic text-sm mt-1">Religius • Nasionalis • Berkualitas</p>
                </div>
            </div>
            <div class="hidden md:flex gap-4 mt-4 md:mt-0">
                <a href="<?= BASEURL; ?>/login" class="px-6 py-2 border border-emerald-600 text-emerald-700 font-medium rounded-full hover:bg-emerald-50 transition-colors">Login Admin</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Left Column: Title & Features -->
            <div class="lg:w-7/12">
                <h1 class="text-7xl md:text-8xl font-black text-[#004d33] tracking-tighter mb-2 leading-none">SPMB</h1>
                <h2 class="text-2xl md:text-4xl font-extrabold text-[#004d33] tracking-tight mb-4 uppercase">Sistem Penerimaan<br>Murid Baru</h2>
                
                <div class="inline-block bg-amber-400 text-[#004d33] font-bold px-6 py-2 rounded-full text-lg mb-6 shadow-sm">
                    TAHUN AJARAN <?= !empty($data['tahun_akademik']) ? htmlspecialchars($data['tahun_akademik']['nama_tahun']) : '2026/2027' ?>
                </div>

                <p class="text-xl md:text-2xl font-medium text-slate-600 italic mb-8 leading-snug">
                    Bersama NW, Membangun Generasi<br>Cerdas, Berakhlak Mulia, dan Berwawasan Global
                </p>

                <?php if(!empty($data['pengaturan']['brosur_spmb'])): ?>
                <div class="mb-10">
                    <a href="<?= $data['pengaturan']['brosur_spmb'] ?>" download="Brosur_PPDB_SMANW" class="inline-flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition-transform transform hover:-translate-y-1">
                        <i class="fas fa-file-download text-xl"></i> 
                        <span>Download Brosur PPDB</span>
                    </a>
                </div>
                <?php endif; ?>

                <!-- Value Props Icons -->
                <div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-10 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-800">Pendidikan Berbasis Al-Qur'an</h4>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-800">Akademik Berkualitas</h4>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-mosque text-xl"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-800">Pembinaan Karakter Islami</h4>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-trophy text-xl"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-800">Prestasi & Non Akademik</h4>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-globe text-xl"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-800">Pengembangan Soft Skill</h4>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-800">Fasilitas Lengkap</h4>
                    </div>
                </div>

            </div>

            <!-- Right Column: Image & Rincian Biaya -->
            <div class="lg:w-5/12">
                <!-- Hero Image -->
                <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 group h-64 md:h-80">
                    <?php 
                    $hero_img = !empty($data['pengaturan']['gambar_hero_spmb']) ? $data['pengaturan']['gambar_hero_spmb'] : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop'; 
                    ?>
                    <img src="<?= $hero_img ?>" alt="Siswa SMA NW" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#004d33]/80 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-4 py-2 rounded-lg shadow-sm">
                        <p class="font-bold text-emerald-800 text-sm">SMA NAHDLATUL WATHAN JAKARTA</p>
                    </div>
                </div>

                <!-- Rincian Biaya -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                    <div class="bg-slate-50 py-4 px-6 text-center border-b border-slate-200">
                        <h3 class="font-bold text-lg text-slate-800">RINCIAN BIAYA MASUK</h3>
                        <p class="text-sm font-semibold text-emerald-700">TAHUN PELAJARAN 2026-2027</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Looping Kategori Biaya (Dynamic) -->
                        <?php if (!empty($data['kategori_biaya'])): ?>
                            <!-- Tab navigation if multiple categories -->
                            <?php if (count($data['kategori_biaya']) > 1): ?>
                            <div class="flex border-b border-gray-200 mb-4 overflow-x-auto gap-2 pb-2">
                                <?php foreach($data['kategori_biaya'] as $index => $kat): ?>
                                    <button class="px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition-colors <?= $index === 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' ?>" onclick="showBiayaTab('kat-<?= $kat['id'] ?>', this)">
                                        <?= htmlspecialchars($kat['nama_kategori']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Tab Contents -->
                            <?php foreach($data['kategori_biaya'] as $index => $kat): ?>
                                <div id="kat-<?= $kat['id'] ?>" class="biaya-tab <?= $index !== 0 ? 'hidden' : '' ?>">
                                    <div class="space-y-3 mb-6">
                                        <?php $total = 0; $no = 1; ?>
                                        <?php foreach($kat['rincian'] as $r): ?>
                                            <?php $total += $r['nominal']; ?>
                                            <div class="flex justify-between items-center py-2 border-b border-slate-100 border-dashed">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold"><?= $no++; ?></div>
                                                    <span class="text-slate-700 text-sm font-medium"><?= htmlspecialchars($r['nama_item']); ?></span>
                                                </div>
                                                <span class="font-bold text-slate-800 text-sm">Rp. <?= number_format($r['nominal'], 0, ',', '.'); ?>,-</span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="bg-[#004d33] text-white rounded-xl p-4 flex justify-between items-center">
                                        <span class="font-bold text-lg">TOTAL</span>
                                        <span class="font-black text-xl">Rp. <?= number_format($total, 0, ',', '.'); ?>,-</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <p class="text-center text-xs text-slate-500 mt-4 italic">* Biaya dapat berubah sewaktu-waktu sesuai kebijakan Yayasan</p>
                        
                        <?php else: ?>
                            <div class="text-center py-8 text-slate-500">
                                <i class="fas fa-info-circle text-2xl mb-2"></i>
                                <p>Rincian biaya belum diatur.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lower Section: Programs, Facilities, Flow & Schedule -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-12 bg-slate-50 rounded-3xl p-6 md:p-10 border border-slate-200">
            
            <!-- Program & Fasilitas (Left) -->
            <div class="lg:col-span-5 space-y-8">
                <!-- Program Unggulan -->
                <div>
                    <div class="bg-[#004d33] text-white py-2 px-6 rounded-t-xl font-bold text-center uppercase tracking-wider mb-4">
                        Program Unggulan
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-2">
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Tahfidz Al-Qur'an</div>
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Persiapan Perguruan Tinggi</div>
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Kurikulum Merdeka</div>
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Literasi Digital</div>
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Bahasa Arab & Inggris</div>
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Pembinaan Karakter</div>
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Ekstrakurikuler Beragam</div>
                        <div class="flex items-center gap-3 text-slate-700 text-sm font-medium"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Kegiatan Islami & Sosial</div>
                    </div>
                </div>

                <!-- Fasilitas Sekolah -->
                <div>
                    <div class="bg-emerald-600 text-white py-2 px-6 rounded-t-xl font-bold text-center uppercase tracking-wider mb-4">
                        Fasilitas Sekolah
                    </div>
                    <div class="grid grid-cols-4 gap-4 text-center px-2">
                        <div>
                            <i class="fas fa-mosque text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">Masjid</p>
                        </div>
                        <div>
                            <i class="fas fa-desktop text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">Lab Komputer</p>
                        </div>
                        <div>
                            <i class="fas fa-flask text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">Lab IPA</p>
                        </div>
                        <div>
                            <i class="fas fa-book text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">Perpustakaan</p>
                        </div>
                        <div>
                            <i class="fas fa-wifi text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">WiFi</p>
                        </div>
                        <div>
                            <i class="fas fa-volleyball-ball text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">Lapangan</p>
                        </div>
                        <div>
                            <i class="fas fa-chalkboard-teacher text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">Smart Class</p>
                        </div>
                        <div>
                            <i class="fas fa-utensils text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-xs font-semibold text-slate-700">Kantin Sehat</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alur & Jadwal (Right) -->
            <div class="lg:col-span-7 space-y-8 lg:border-l border-slate-200 lg:pl-8">
                <!-- Alur Pendaftaran -->
                <div>
                    <h3 class="font-bold text-xl text-slate-800 text-center mb-6">ALUR PENDAFTARAN</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#004d33] text-white font-bold flex items-center justify-center shrink-0">01</div>
                            <p class="text-sm font-semibold text-slate-700 leading-tight">Isi Formulir<br>Online</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#004d33] text-white font-bold flex items-center justify-center shrink-0">02</div>
                            <p class="text-sm font-semibold text-slate-700 leading-tight">Upload<br>Berkas</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#004d33] text-white font-bold flex items-center justify-center shrink-0">03</div>
                            <p class="text-sm font-semibold text-slate-700 leading-tight">Verifikasi<br>Berkas</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#004d33] text-white font-bold flex items-center justify-center shrink-0">04</div>
                            <p class="text-sm font-semibold text-slate-700 leading-tight">Tes / Wawancara<br><span class="text-xs font-normal text-slate-500">(Jika diperlukan)</span></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#004d33] text-white font-bold flex items-center justify-center shrink-0">05</div>
                            <p class="text-sm font-semibold text-slate-700 leading-tight">Pengumuman<br>Hasil</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#004d33] text-white font-bold flex items-center justify-center shrink-0">06</div>
                            <p class="text-sm font-semibold text-slate-700 leading-tight">Daftar<br>Ulang</p>
                        </div>
                    </div>
                </div>

                <!-- Jadwal PPDB -->
                <div>
                    <div class="inline-block bg-emerald-500 text-white font-bold px-4 py-1 rounded-full text-sm mb-4">JADWAL PPDB</div>
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 px-4 font-medium text-slate-800"><i class="fas fa-calendar-alt text-emerald-500 w-6"></i> Pendaftaran</td>
                                    <td class="py-3 px-4 text-slate-600 font-semibold text-right">
                                        <?php if (!empty($data['gelombang_aktif'])): ?>
                                            <?= date('d M', strtotime($data['gelombang_aktif']['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($data['gelombang_aktif']['tanggal_selesai'])) ?>
                                        <?php else: ?>
                                            Menunggu Informasi
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 px-4 font-medium text-slate-800"><i class="fas fa-file-signature text-emerald-500 w-6"></i> Verifikasi Berkas</td>
                                    <td class="py-3 px-4 text-slate-600 text-right">Menyusul</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 px-4 font-medium text-slate-800"><i class="fas fa-comments text-emerald-500 w-6"></i> Tes / Wawancara</td>
                                    <td class="py-3 px-4 text-slate-600 text-right">Jika Diperlukan</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 px-4 font-medium text-slate-800"><i class="fas fa-bullhorn text-emerald-500 w-6"></i> Pengumuman</td>
                                    <td class="py-3 px-4 text-slate-600 text-right">Menyusul</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 font-medium text-slate-800"><i class="fas fa-id-card text-emerald-500 w-6"></i> Daftar Ulang</td>
                                    <td class="py-3 px-4 text-slate-600 text-right">Menyusul</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / CTA Section -->
        <div class="mt-8 bg-[#004d33] rounded-3xl p-6 md:p-8 flex flex-col md:flex-row gap-8 justify-between items-center text-white overflow-hidden relative">
            <div class="absolute right-0 bottom-0 opacity-10">
                <i class="fas fa-graduation-cap text-9xl -mb-4 -mr-4"></i>
            </div>
            
            <div class="flex-1 flex flex-col md:flex-row items-center md:items-start gap-8 z-10 w-full">
                <!-- CTA -->
                <div class="text-center md:text-left bg-emerald-800/50 p-6 rounded-2xl border border-emerald-600 w-full md:w-1/3">
                    <h3 class="text-2xl font-black mb-2">DAFTAR SEKARANG!</h3>
                    <p class="text-emerald-100 text-sm mb-6 leading-relaxed">Wujudkan masa depan terbaik bersama SMA Nahdlatul Wathan Jakarta</p>
                    
                    <?php if (!empty($data['gelombang_aktif'])): ?>
                        <a href="<?= BASEURL; ?>/spmb" class="inline-block w-full bg-amber-400 hover:bg-amber-500 text-[#004d33] font-bold py-3 px-6 rounded-xl text-center transition-colors shadow-lg">
                            KLIK UNTUK MENDAFTAR
                        </a>
                    <?php else: ?>
                        <button disabled class="inline-block w-full bg-gray-400 text-gray-200 font-bold py-3 px-6 rounded-xl text-center cursor-not-allowed">
                            PENDAFTARAN DITUTUP
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Hubungi Kami -->
                <div class="w-full md:w-1/3 text-center md:text-left">
                    <h4 class="font-bold text-lg mb-4 text-amber-400">HUBUNGI KAMI</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center justify-center md:justify-start gap-3">
                            <i class="fab fa-whatsapp text-2xl text-emerald-400"></i>
                            <span class="font-medium">+62 857-9468-7189</span>
                        </li>
                        <li class="flex items-center justify-center md:justify-start gap-3">
                            <i class="fas fa-globe text-2xl text-emerald-400"></i>
                            <span class="font-medium">sims.serbakabar.com</span>
                        </li>
                        <li class="flex items-center justify-center md:justify-start gap-3">
                            <i class="fab fa-instagram text-2xl text-emerald-400"></i>
                            <span class="font-medium">sma.nwjakarta</span>
                        </li>
                    </ul>
                </div>

                <!-- Lokasi -->
                <div class="w-full md:w-1/3 text-center md:text-left">
                    <h4 class="font-bold text-lg mb-4 text-amber-400">LOKASI SEKOLAH</h4>
                    <div class="flex flex-col gap-3">
                        <p class="text-sm text-emerald-50 leading-relaxed">
                            Jalan Raya Penggilingan,<br>Kec. Cakung, Kota Administrasi<br>Jakarta Timur, DKI Jakarta
                        </p>
                        <a href="#" class="inline-flex items-center justify-center md:justify-start gap-2 text-emerald-300 hover:text-white transition-colors text-sm font-medium">
                            <i class="fas fa-map-marker-alt"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-8 text-sm text-slate-500 font-medium">
            &copy; 2026 SMA Nahdlatul Wathan Jakarta. All rights reserved.
        </div>
    </div>
</section>

<script>
function showBiayaTab(tabId, button) {
    // Hide all tabs
    document.querySelectorAll('.biaya-tab').forEach(el => el.classList.add('hidden'));
    
    // Show selected tab
    document.getElementById(tabId).classList.remove('hidden');
    
    // Reset all buttons
    let buttons = button.parentElement.querySelectorAll('button');
    buttons.forEach(btn => {
        btn.classList.remove('bg-emerald-100', 'text-emerald-800');
        btn.classList.add('bg-gray-50', 'text-gray-600', 'hover:bg-gray-100');
    });
    
    // Set active button
    button.classList.remove('bg-gray-50', 'text-gray-600', 'hover:bg-gray-100');
    button.classList.add('bg-emerald-100', 'text-emerald-800');
}
</script>
