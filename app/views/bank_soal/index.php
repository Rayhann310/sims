<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ importModal: false }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Kelola bank soal ujian CBT di sini.</p>
        </div>
        <div class="flex space-x-3">
            <button @click="importModal = true" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-file-import mr-2"></i> Import Soal
            </button>
            <a href="<?= BASEURL; ?>/BankSoal/tambah" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Manual
            </a>
        </div>
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

    <!-- Modal Import Soal -->
    <div x-show="importModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="importModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="importModal = false"></div>

            <div x-show="importModal" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Import Bank Soal</h3>
                    <button @click="importModal = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/BankSoal/importPreview" method="post" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div class="flex gap-2 mb-4 justify-center">
                            <a href="<?= BASEURL; ?>/BankSoal/templateWord" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors rounded-lg text-xs font-bold w-1/2 justify-center">
                                <i class="fas fa-file-word mr-1"></i> Template Word
                            </a>
                            <a href="<?= BASEURL; ?>/BankSoal/templateExcel" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors rounded-lg text-xs font-bold w-1/2 justify-center">
                                <i class="fas fa-file-excel mr-1"></i> Template Excel
                            </a>
                        </div>
                        
                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-100 mb-4">
                            <p class="text-xs text-amber-800">
                                <strong>Info:</strong> Gunakan Word (.docx) jika soal mengandung <strong>Gambar</strong>. Gunakan Excel (.xlsx) khusus untuk soal teks saja.
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                            <select name="id_mapel" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <?php foreach($data['mapel'] as $m): ?>
                                    <option value="<?= $m['id']; ?>"><?= htmlspecialchars($m['nama_mapel']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">File Soal (.docx atau .xlsx)</label>
                            <input type="file" name="file_soal" accept=".docx, .xlsx" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="importModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Preview Soal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
