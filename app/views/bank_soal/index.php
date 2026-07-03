<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Kelola bank soal ujian CBT di sini.</p>
        </div>
        <a href="<?= BASEURL; ?>/BankSoal/tambah" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Soal
        </a>
    </div>

    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pertanyaan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipe Soal</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Tingkat Kesulitan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if(empty($data['soal'])): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada soal di Bank Soal.</td></tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($data['soal'] as $row): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $i++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded"><?= htmlspecialchars($row['nama_mapel'] ?? 'Umum'); ?></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 max-w-xs truncate" title="<?= htmlspecialchars($row['pertanyaan']); ?>">
                                <?= strip_tags($row['pertanyaan']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $row['tipe_soal']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?php if($row['tingkat_kesulitan'] == 'Mudah'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Mudah</span>
                                <?php elseif($row['tingkat_kesulitan'] == 'Sulit'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Sulit</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Sedang</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASEURL; ?>/BankSoal/hapus/<?= $row['id_soal']; ?>" 
                                   class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors"
                                   onclick="return confirm('Yakin ingin menghapus soal ini?');">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
