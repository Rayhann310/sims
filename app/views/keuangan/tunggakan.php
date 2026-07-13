<?php $kategoriJson = json_encode($data['kategori'] ?? []); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
    modalManual: false, 
    modalImport: false,
    kategoriList: <?= htmlspecialchars($kategoriJson, ENT_QUOTES) ?>,
    selectedKategori: '',
    nominalTagihan: 0
}" x-init="$watch('selectedKategori', val => { 
    const k = kategoriList.find(x => x.id == val);
    if(k) nominalTagihan = k.nominal_default;
})">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Kelola tunggakan SPP/Tagihan (khususnya untuk siswa aktif maupun alumni).</p>
        </div>
        <div class="flex gap-2">
            <button @click="modalManual = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Input Manual
            </button>
            <button @click="modalImport = true" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Import Excel
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Daftar Tunggakan</h3>
        <p class="text-slate-600 text-sm mb-4">Semua tunggakan siswa tercatat pada menu <strong>Pembayaran & Tagihan</strong>. Menu ini khusus digunakan untuk menambahkan tagihan masa lalu (tunggakan) secara manual tanpa perlu setting rombel, atau menggunakan import Excel.</p>
        <p class="text-sm text-slate-600">Untuk melihat daftar tunggakan, silakan lihat di menu <a href="<?= BASEURL; ?>/keuangan/tagihan" class="text-indigo-600 font-medium hover:underline">Tagihan & Pembayaran</a> (filter status "Belum Lunas"). Siswa Alumni yang memiliki tunggakan juga akan tampil di sana atau dapat dicari.</p>
    </div>

    <!-- Modal Manual -->
    <div x-show="modalManual" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalManual" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalManual = false"></div>
            <div x-show="modalManual" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Input Tunggakan Manual</h3>
                    <button @click="modalManual = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/keuangan/prosesTunggakan" method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Siswa (Aktif / Alumni)</label>
                            <select name="siswa_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach($data['siswa'] as $s): ?>
                                    <option value="<?= $s['id']; ?>"><?= $s['nisn']; ?> - <?= $s['nama_lengkap']; ?> (<?= $s['status']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Tagihan</label>
                            <select name="kategori_id" x-model="selectedKategori" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="">-- Pilih Jenis Tagihan --</option>
                                <template x-for="k in kategoriList" :key="k.id">
                                    <option :value="k.id" x-text="k.nama_kategori"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nominal per Bulan (Rp)</label>
                            <input type="number" name="nominal" x-model="nominalTagihan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Dari Bulan</label>
                                <select name="dari_bulan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                    <?php $bulans = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; 
                                    foreach($bulans as $b): ?>
                                        <option value="<?= $b; ?>"><?= $b; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Dari Tahun</label>
                                <input type="number" name="dari_tahun" value="<?= date('Y'); ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Sampai Bulan</label>
                                <select name="sampai_bulan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                    <?php foreach($bulans as $b): ?>
                                        <option value="<?= $b; ?>"><?= $b; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Sampai Tahun</label>
                                <input type="number" name="sampai_tahun" value="<?= date('Y'); ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalManual = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Tunggakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div x-show="modalImport" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalImport" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalImport = false"></div>
            <div x-show="modalImport" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Import Tunggakan (Excel)</h3>
                    <button @click="modalImport = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/keuangan/previewImportTunggakan" method="post" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-600 mb-2">Silakan download template Excel terlebih dahulu, isi datanya, lalu upload kembali ke sini.</p>
                            <a href="<?= BASEURL; ?>/keuangan/downloadTemplateTunggakan" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                <i class="fas fa-download mr-1"></i> Download Template Excel
                            </a>
                        </div>
                        <div class="mt-4 border-2 border-dashed border-slate-300 rounded-lg p-6 text-center">
                            <i class="fas fa-file-excel text-4xl text-slate-400 mb-2"></i>
                            <p class="text-sm text-slate-600">Pilih file Excel (.xlsx)</p>
                            <input type="file" name="file_excel" accept=".xlsx,.xls" required class="mt-2 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalImport = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Preview Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
