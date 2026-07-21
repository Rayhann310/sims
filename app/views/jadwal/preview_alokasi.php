<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Periksa kembali data alokasi yang akan diimport.</p>
        </div>
        <a href="<?= BASEURL; ?>/jadwal/pengaturan" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Batal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <?php 
        $total = count($data['previewData']);
        $valid = count(array_filter($data['previewData'], function($i) { return $i['is_valid']; }));
        $invalid = $total - $valid;
        ?>

        <div class="flex gap-4 mb-6">
            <div class="px-4 py-3 bg-blue-50 border border-blue-100 rounded-lg flex-1">
                <p class="text-sm text-blue-600 font-medium">Total Data</p>
                <p class="text-2xl font-bold text-blue-800"><?= $total ?></p>
            </div>
            <div class="px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-lg flex-1">
                <p class="text-sm text-emerald-600 font-medium">Data Valid</p>
                <p class="text-2xl font-bold text-emerald-800"><?= $valid ?></p>
            </div>
            <div class="px-4 py-3 bg-red-50 border border-red-100 rounded-lg flex-1">
                <p class="text-sm text-red-600 font-medium">Data Tidak Valid</p>
                <p class="text-2xl font-bold text-red-800"><?= $invalid ?></p>
            </div>
        </div>

        <?php if($valid > 0): ?>
            <form action="<?= BASEURL; ?>/jadwal/simpanImportAlokasi" method="POST" class="mb-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan <?= $valid ?> Data Valid
                </button>
            </form>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Mapel</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4 text-center">Jumlah JP</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['previewData'])): ?>
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                Tidak ada data yang ditemukan di file Excel.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($data['previewData'] as $row): ?>
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4"><?= $no++ ?></td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-800"><?= $row['nama_mapel'] ?></div>
                                    <div class="text-xs text-slate-500">ID: <?= $row['mapel_id'] ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-800"><?= $row['nama_kelas'] ?></div>
                                    <div class="text-xs text-slate-500">ID: <?= $row['kelas_id'] ?></div>
                                </td>
                                <td class="py-3 px-4 text-center font-medium"><?= $row['jumlah_jp'] ?></td>
                                <td class="py-3 px-4 text-center">
                                    <?php if($row['is_valid']): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            <i class="fas fa-check-circle"></i> Valid
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800" title="<?= $row['error_msg'] ?>">
                                            <i class="fas fa-times-circle"></i> Invalid
                                        </span>
                                        <div class="text-[10px] text-red-500 mt-1"><?= $row['error_msg'] ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
