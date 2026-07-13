<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Preview Import Tunggakan</h1>
            <p class="text-slate-500 mt-2">Silakan periksa kembali data sebelum disimpan secara permanen.</p>
        </div>
        <a href="<?= BASEURL; ?>/keuangan/tunggakan" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if(empty($data['preview_data'])): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">Tidak ada data valid yang bisa diimpor.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($data['preview_data'] as $index => $row): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $index + 1; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?= htmlspecialchars($row['siswa_id']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= htmlspecialchars($row['kategori_id']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= htmlspecialchars($row['bulan']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= htmlspecialchars($row['tahun']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">Rp <?= number_format($row['nominal'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= htmlspecialchars($row['jatuh_tempo']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if(!empty($data['preview_data'])): ?>
    <form action="<?= BASEURL; ?>/keuangan/prosesImportTunggakan" method="post" class="flex justify-end">
        <!-- We serialize the array so we can pass it easily, or use sessions. Let's use base64 json to avoid storing in session to be stateless -->
        <input type="hidden" name="import_data" value="<?= base64_encode(json_encode($data['preview_data'])); ?>">
        <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <i class="fas fa-check mr-2"></i> Konfirmasi & Simpan
        </button>
    </form>
    <?php endif; ?>
</div>
