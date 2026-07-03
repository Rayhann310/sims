<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Pilih soal-soal yang akan diujikan pada jadwal ujian ini.</p>
        </div>
        <a href="<?= BASEURL; ?>/JadwalUjian" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-6">
        <h3 class="text-lg font-bold text-indigo-900 mb-2">Informasi Ujian</h3>
        <ul class="text-sm text-indigo-800 space-y-1">
            <li><strong>Nama Ujian:</strong> <?= htmlspecialchars($data['jadwal']['nama_ujian']); ?></li>
            <li><strong>Mata Pelajaran:</strong> <?= htmlspecialchars($data['jadwal']['nama_mapel']); ?></li>
            <li><strong>Waktu Pelaksanaan:</strong> <?= date('d/m/Y H:i', strtotime($data['jadwal']['waktu_mulai'])); ?> - <?= date('d/m/Y H:i', strtotime($data['jadwal']['waktu_selesai'])); ?></li>
        </ul>
    </div>

    <form action="<?= BASEURL; ?>/JadwalUjian/simpanSoal" method="POST">
        <input type="hidden" name="id_jadwal" value="<?= $data['jadwal']['id_jadwal']; ?>">
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900">Daftar Soal Tersedia</h3>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    Total: <?= count($data['soal']); ?> Soal
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">Pilih</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipe & Kesulitan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pertanyaan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pembuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php if(empty($data['soal'])): ?>
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada soal untuk mata pelajaran ini di Bank Soal.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['soal'] as $row): 
                                $isChecked = in_array($row['id_soal'], $data['terpilih']) ? 'checked' : '';
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="soal_ids[]" value="<?= $row['id_soal']; ?>" <?= $isChecked; ?>
                                           class="w-4 h-4 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900"><?= $row['tipe_soal']; ?></div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <?php if($row['tingkat_kesulitan'] == 'Mudah'): ?>
                                            <span class="text-emerald-600 font-medium">Mudah</span>
                                        <?php elseif($row['tingkat_kesulitan'] == 'Sedang'): ?>
                                            <span class="text-amber-500 font-medium">Sedang</span>
                                        <?php else: ?>
                                            <span class="text-red-600 font-medium">Sulit</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900">
                                    <div class="line-clamp-2"><?= strip_tags($row['pertanyaan']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <?= htmlspecialchars($row['nama_pembuat']); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                <a href="<?= BASEURL; ?>/JadwalUjian" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Pilihan Soal</button>
            </div>
        </div>
    </form>
</div>
