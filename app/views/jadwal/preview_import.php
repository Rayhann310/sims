<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Preview Import Jadwal</h1>
            <p class="text-slate-500 mt-1">Kelas: <span class="font-bold text-slate-700"><?= $data['rombel']['nama_rombel'] ?></span></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?= BASEURL; ?>/jadwal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-colors">Batal</a>
            <form action="<?= BASEURL; ?>/jadwal/simpanImport" method="POST" class="inline-block">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors shadow-sm"><i class="fas fa-save mr-2"></i>Simpan Import</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-3 font-semibold text-slate-700 w-16 text-center">No</th>
                        <th class="p-3 font-semibold text-slate-700">Hari</th>
                        <th class="p-3 font-semibold text-slate-700">Waktu</th>
                        <th class="p-3 font-semibold text-slate-700">Mata Pelajaran</th>
                        <th class="p-3 font-semibold text-slate-700">Guru Pengajar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no = 1; foreach($data['jadwal'] as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-3 text-center text-slate-500"><?= $no++ ?></td>
                        <td class="p-3 font-medium text-slate-800"><?= htmlspecialchars($row['hari']) ?></td>
                        <td class="p-3 text-slate-600">
                            <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-xs font-mono">
                                <i class="far fa-clock mr-1 text-slate-400"></i>
                                <?= htmlspecialchars($row['jam_mulai']) ?> - <?= htmlspecialchars($row['jam_selesai']) ?>
                            </span>
                        </td>
                        <td class="p-3 text-slate-800 font-medium text-blue-900"><?= htmlspecialchars($row['nama_mapel']) ?></td>
                        <td class="p-3 text-slate-600"><i class="fas fa-chalkboard-teacher text-slate-400 mr-2"></i><?= htmlspecialchars($row['nama_guru']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
        <p class="text-sm text-amber-800"><i class="fas fa-info-circle mr-2"></i><strong>Penting:</strong> Jadwal yang memiliki jam bentrok dengan jadwal manual yang sudah ada akan otomatis dilewati saat disimpan.</p>
    </div>
</div>
