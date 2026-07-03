<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-2"><?= $data['judul']; ?></h1>
        <p class="text-slate-500">Pilih ujian yang tersedia untuk kelas Anda dan mulailah mengerjakan.</p>
    </div>

    <!-- Alert / Info -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-8 shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-blue-800">Informasi Penting!</h3>
                <div class="mt-1 text-sm text-blue-700">
                    <p>Pastikan koneksi internet Anda stabil sebelum memulai ujian. Jika Anda keluar dari layar ujian atau berpindah tab, akun Anda akan <b>TERKUNCI</b> secara otomatis dan Anda memerlukan token dari Pengawas untuk melanjutkannya.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Ujian -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Daftar Ujian Tersedia
            </h2>
        </div>
        
        <div class="p-6">
            <?php if(empty($data['jadwal'])): ?>
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Tidak Ada Ujian Aktif</h3>
                    <p class="text-slate-500">Saat ini tidak ada jadwal ujian yang ditugaskan untuk kelas Anda.</p>
                </div>
            <?php else: ?>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <?php foreach($data['jadwal'] as $row) : ?>
                        <div class="group relative bg-white border border-slate-200 rounded-xl p-5 hover:border-emerald-500 hover:shadow-md transition-all duration-300 flex flex-col h-full">
                            
                            <div class="mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 mb-3">
                                    <?= htmlspecialchars($row['nama_mapel'] ?? 'Mata Pelajaran Umum'); ?>
                                </span>
                                <h3 class="text-lg font-bold text-slate-800 leading-tight group-hover:text-emerald-600 transition-colors">
                                    <?= htmlspecialchars($row['nama_ujian']); ?>
                                </h3>
                            </div>
                            
                            <div class="space-y-3 mb-6 flex-1">
                                <div class="flex items-start gap-2.5 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <div>
                                        <p class="font-medium text-slate-700">Dimulai:</p>
                                        <p><?= date('d M Y, H:i', strtotime($row['waktu_mulai'])); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2.5 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <p class="font-medium text-slate-700">Durasi:</p>
                                        <p><?= $row['durasi_menit']; ?> Menit</p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if(isset($row['status_ujian']) && $row['status_ujian'] == '3'): ?>
                                <div class="w-full inline-flex justify-between items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-500 text-sm font-bold rounded-lg border border-slate-200 cursor-not-allowed">
                                    <span>Telah Selesai</span>
                                    <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs">Nilai: <?= number_format((float)$row['nilai'], 1) ?></span>
                                </div>
                            <?php else: ?>
                                <a href="<?= BASEURL; ?>/UjianSiswa/mulai/<?= $row['id_jadwal']; ?>" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors focus:ring-4 focus:ring-emerald-200">
                                    <?php echo (isset($row['status_ujian']) && $row['status_ujian'] != '0') ? 'Lanjutkan Ujian' : 'Buka Ujian'; ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
