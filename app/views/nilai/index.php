<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
        <p class="text-slate-500 mt-2 text-lg">Pilih kelas/jadwal untuk mengelola presensi harian dan input nilai siswa.</p>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php if(empty($data['jadwal'])): ?>
            <div class="col-span-full py-12 text-center text-slate-500 bg-white rounded-2xl shadow-sm border border-slate-200">
                <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <p class="text-lg font-medium text-slate-900">Belum Ada Jadwal Mengajar</p>
                <p class="mt-1">Anda tidak memiliki jadwal mengajar pada tahun akademik ini.</p>
            </div>
        <?php endif; ?>

        <?php foreach($data['jadwal'] as $j): ?>
        <a href="<?= BASEURL; ?>/nilai/detail/<?= $j['id']; ?>" class="group block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:border-green-300 transition-all duration-300 transform hover:-translate-y-1">
            <div class="h-32 bg-gradient-to-r from-emerald-500 to-teal-600 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 group-hover:bg-transparent transition-colors"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/20 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                <div class="absolute top-4 left-6 right-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm border border-white/30">
                        <?= $j['hari']; ?> (<?= $j['jam_mulai']; ?> - <?= $j['jam_selesai']; ?>)
                    </span>
                </div>
            </div>
            <div class="p-6 relative">
                <div class="w-12 h-12 bg-white rounded-xl shadow-md border border-slate-100 flex items-center justify-center absolute -top-6 left-6 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="mt-4">
                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-emerald-600 transition-colors"><?= $j['nama_mapel']; ?></h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">Kelas: <?= $j['nama_rombel']; ?> (<?= $j['nama_kelas']; ?>)</p>
                </div>
                <div class="mt-6 flex items-center justify-between text-sm text-slate-500 border-t border-slate-100 pt-4">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Presensi & Nilai
                    </span>
                    <span class="text-emerald-600 font-medium group-hover:underline">Kelola &rarr;</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
