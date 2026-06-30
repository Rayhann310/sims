<div class="space-y-6" x-data="{ editModalOpen: false, importModalOpen: false, currentId: '', currentNama: '', currentHp: '', currentSiswa: '' }"
     @open-edit-modal.window="editModalOpen = true" @open-import-modal.window="importModalOpen = true">
    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Manajemen Data Orang Tua / Wali</h2>
                <p class="text-sm text-slate-500">Kelola kontak orang tua/wali dari setiap peserta didik.</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto shrink-0">
                <a href="<?= BASEURL; ?>/orangtua/template" class="w-full sm:w-auto bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-emerald-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Template Import
                </a>
                <button type="button" @click="$dispatch('open-import-modal')" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Wali
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="px-6 py-4 bg-white border-b border-slate-200">
            <form action="<?= BASEURL; ?>/orangtua" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Kelas</label>
                    <select name="kelas" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kelas</option>
                        <?php foreach($data['filter_options']['kelas'] as $kls): ?>
                            <option value="<?= $kls; ?>" <?= ($data['filters']['kelas'] == $kls) ? 'selected' : ''; ?>><?= $kls; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="w-full sm:w-auto flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <?php if(array_filter($data['filters']) && $data['filters']['status'] !== 'Aktif'): ?>
                    <a href="<?= BASEURL; ?>/orangtua" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                        <i class="fas fa-times"></i> Reset
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Flash Message -->
        <?php if(isset($_SESSION['flash'])): ?>
            <div class="px-6 py-4 border-b border-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-200 bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-100 flex items-center justify-center text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-600 shrink-0">
                    <?php if($_SESSION['flash']['tipe'] == 'success'): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <?php else: ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <?php endif; ?>
                </div>
                <p class="text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-800 text-sm">
                    <strong><?= $_SESSION['flash']['pesan'] ?></strong> <?= $_SESSION['flash']['aksi'] ?>.
                </p>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Tabel -->
        <div class="overflow-x-auto" x-data="{ search: '' }">
            <div class="px-6 py-3 border-b border-slate-200 flex justify-end">
                <input type="text" x-model="search" placeholder="Cari nama siswa atau wali..." class="px-3 py-2 text-sm border border-slate-300 rounded-lg w-full sm:w-64 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4 font-semibold">Nama Siswa & Kelas</th>
                        <th class="px-6 py-4 font-semibold">Nama Wali</th>
                        <th class="px-6 py-4 font-semibold">No HP Wali</th>
                        <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach($data['siswa'] as $s): ?>
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '<?= strtolower(htmlspecialchars(($s['nama_lengkap'] ?? '') . ' ' . ($s['nama_wali'] ?? ''))) ?>'.includes(search.toLowerCase())">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800"><?= htmlspecialchars($s['nama_lengkap'] ?? ''); ?></div>
                            <div class="text-xs text-slate-500 mt-1">Kelas: <?= htmlspecialchars($s['nama_kelas'] ?? 'Belum Diatur'); ?></div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">
                            <?= htmlspecialchars(!empty($s['nama_wali']) ? $s['nama_wali'] : '-'); ?>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <?php if(!empty($s['no_hp_wali'])): ?>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $s['no_hp_wali']); ?>" target="_blank" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700">
                                    <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($s['no_hp_wali']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-slate-400 italic">Belum ada nomor</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 flex justify-center">
                            <button type="button" @click="currentId = '<?= $s['id'] ?? ''; ?>'; currentNama = '<?= addslashes($s['nama_wali'] ?? ''); ?>'; currentHp = '<?= addslashes($s['no_hp_wali'] ?? ''); ?>'; currentSiswa = '<?= addslashes($s['nama_lengkap'] ?? ''); ?>'; editModalOpen = true" class="px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors flex items-center gap-2">
                                <i class="fas fa-edit"></i> Edit Kontak
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="editModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold leading-6 text-slate-900">Edit Kontak Wali</h3>
                    <p class="text-sm text-slate-500 mt-1" x-text="'Wali dari: ' + currentSiswa"></p>
                </div>

                <form action="<?= BASEURL; ?>/orangtua/ubah" method="POST">
                    <input type="hidden" name="id" :value="currentId">
                    
                    <div class="bg-slate-50 px-6 py-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Wali</label>
                            <input type="text" name="nama_wali" x-model="currentNama" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">No HP Wali (Gunakan format 62...)</label>
                            <input type="text" name="no_hp_wali" x-model="currentHp" placeholder="Contoh: 628123456789" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-slate-500 mt-1">Pastikan nomor diawali dengan 62 (bukan 0) agar fitur WhatsApp berfungsi.</p>
                        </div>
                    </div>

                    <div class="bg-white px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:w-auto transition-colors">Simpan Kontak</button>
                        <button type="button" @click="editModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Import Data -->
    <div x-show="importModalOpen" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div x-show="importModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="importModalOpen = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="importModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold leading-6 text-slate-900">Import Data Orang Tua (Excel)</h3>
                    <p class="text-sm text-slate-500 mt-1">Upload file Excel sesuai dengan template yang disediakan. Sistem akan mencocokkan berpatokan pada NISN.</p>
                </div>

                <form action="<?= BASEURL; ?>/orangtua/import" method="POST" enctype="multipart/form-data">
                    <div class="bg-slate-50 px-6 py-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pilih File Excel (.xlsx / .xls / .csv)</label>
                        <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-200 rounded-lg p-2 bg-white">
                        <p class="text-xs text-slate-500 mt-2 flex items-start gap-1">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <span>Pastikan kolom berisi: NISN, Nama Siswa, Nama Wali, No HP Wali. <a href="<?= BASEURL; ?>/orangtua/template" class="text-blue-600 hover:underline font-medium">Download template</a></span>
                        </p>
                    </div>

                    <div class="bg-white px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 sm:w-auto transition-colors">Import Data</button>
                        <button type="button" @click="importModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
