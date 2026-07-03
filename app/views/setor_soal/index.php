<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Pilih jadwal ujian dan kirimkan soal-soal Anda ke operator ujian.</p>
        </div>
    </div>

    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- PANDUAN ALUR SISTEM (TUTORIAL GURU) -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-bold text-indigo-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Alur Penyetoran Soal Ujian (Panduan Guru)
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 relative">
            <!-- Garis penghubung untuk tampilan desktop -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-indigo-200 -z-10" style="transform: translateY(-50%);"></div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border border-indigo-100 relative text-center">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold mx-auto mb-2 relative z-10">1</div>
                <h4 class="font-bold text-slate-800 text-sm">Buat Bank Soal</h4>
                <p class="text-xs text-slate-500 mt-1">Buat soal-soal di menu <a href="<?= BASEURL; ?>/BankSoal" class="text-indigo-600 hover:underline">Bank Soal</a> sesuai Mata Pelajaran Anda.</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border border-indigo-100 relative text-center">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold mx-auto mb-2 relative z-10">2</div>
                <h4 class="font-bold text-slate-800 text-sm">Temukan Jadwal</h4>
                <p class="text-xs text-slate-500 mt-1">Cari Jadwal Ujian pada tabel di bawah ini yang sesuai dengan Mata Pelajaran Anda.</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border border-indigo-100 relative text-center ring-2 ring-indigo-400">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold mx-auto mb-2 relative z-10">3</div>
                <h4 class="font-bold text-slate-800 text-sm">Setor Soal</h4>
                <p class="text-xs text-slate-500 mt-1">Klik tombol <span class="text-indigo-600 font-semibold">Setor Soal</span> lalu centang soal-soal yang akan diujikan ke siswa.</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border border-indigo-100 relative text-center">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mx-auto mb-2 relative z-10">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Selesai!</h4>
                <p class="text-xs text-slate-500 mt-1">Soal sudah otomatis masuk ke ujian siswa. Anda tinggal menunggu waktu ujian tiba.</p>
            </div>
        </div>
    </div>

    <!-- TABEL JADWAL UJIAN -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Daftar Jadwal Ujian</h3>
            <span class="text-xs text-slate-500">Pilih jadwal ujian mata pelajaran Anda</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Pelaksanaan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if(empty($data['jadwal'])): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada jadwal ujian.</td></tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($data['jadwal'] as $row): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $i++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900"><?= $row['nama_ujian']; ?></div>
                                <div class="text-xs text-slate-500 mt-1">Mapel Ujian: <?= $row['id_mapel']; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">Mulai: <span class="font-medium"><?= date('d/m/Y H:i', strtotime($row['waktu_mulai'])); ?></span></div>
                                <div class="text-sm text-slate-900 mt-1">Selesai: <span class="font-medium"><?= date('d/m/Y H:i', strtotime($row['waktu_selesai'])); ?></span></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?= $row['durasi_menit']; ?> Menit</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASEURL; ?>/SetorSoal/kelola/<?= $row['id_jadwal']; ?>" 
                                   class="inline-flex items-center text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition-colors shadow-sm">
                                   <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                   Setor Soal
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
