<?php $kategoriJson = json_encode($data['kategori'] ?? []); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
    modalGenerate: false, 
    modalBayar: false, 
    activeTagihanId: '', 
    sisaTagihan: 0,
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
            <p class="text-slate-500 mt-2">Kelola tagihan SPP siswa dan proses pembayarannya.</p>
        </div>
        <button @click="modalGenerate = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Generate Tagihan Masal
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6" x-data="{ filterKategori: '', filterStatus: '' }">
        <form id="filterForm" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-1/3">
                <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Tagihan</label>
                <select x-model="filterKategori" @change="applyTableFilter()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semua Jenis</option>
                    <template x-for="k in kategoriList" :key="k.id">
                        <option :value="k.nama_kategori" x-text="k.nama_kategori"></option>
                    </template>
                </select>
            </div>
            <div class="w-full sm:w-1/3">
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select x-model="filterStatus" @change="applyTableFilter()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semua Status</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Tagihan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bulan / Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nominal Tagihan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Telah Dibayar</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if(empty($data['tagihan'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data tagihan. Silakan generate terlebih dahulu.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($data['tagihan'] as $t): 
                        $sisa = $t['nominal'] - $t['total_dibayar'];
                        $jenisTagihan = !empty($t['nama_kategori']) ? $t['nama_kategori'] : 'SPP Bulanan';
                        $statusTagihan = ($t['status'] == 'Lunas') ? 'Lunas' : 'Belum Lunas';
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors tagihan-row" data-kategori="<?= htmlspecialchars($jenisTagihan) ?>" data-status="<?= $statusTagihan ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-900"><?= $t['nama_lengkap']; ?></div>
                            <div class="text-sm text-slate-500">NISN: <?= $t['nisn']; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-indigo-600"><?= !empty($t['nama_kategori']) ? $t['nama_kategori'] : 'SPP Bulanan'; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900"><?= $t['bulan']; ?> <?= $t['tahun']; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-900">Rp <?= number_format($t['nominal'], 0, ',', '.'); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-emerald-600 font-medium">Rp <?= number_format($t['total_dibayar'], 0, ',', '.'); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if($t['status'] == 'Lunas'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Lunas</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Belum Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <?php if($t['status'] != 'Lunas'): ?>
                            <div class="flex items-center justify-end gap-2">
                                <button @click="activeTagihanId = <?= $t['id']; ?>; sisaTagihan = <?= $sisa; ?>; modalBayar = true" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors">Bayar</button>
                                <?php if(!empty($t['no_hp_wali'])): ?>
                                <a href="<?= BASEURL; ?>/keuangan/kirimTagihanWA/<?= $t['id']; ?>" class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-md transition-colors inline-flex items-center" title="Tagih via WA">
                                    <i class="fab fa-whatsapp"></i> Tagih
                                </a>
                                <?php endif; ?>
                                <?php if($t['total_dibayar'] > 0): ?>
                                <a href="<?= BASEURL; ?>/keuangan/batalBayar/<?= $t['id']; ?>" onclick="return confirm('Yakin ingin membatalkan pembayaran cicilan ini? Data kas akan dihapus dan notifikasi akan dikirim ke WA orang tua.')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors inline-flex items-center" title="Batal Bayar">
                                    <i class="fas fa-times"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <div class="flex items-center justify-end gap-2">
                                <?php if(!empty($t['no_hp_wali'])): ?>
                                <a href="<?= BASEURL; ?>/keuangan/kirimWA/<?= $t['id']; ?>" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-md transition-colors inline-flex items-center gap-1" title="Kirim Resi WA">
                                    <i class="fab fa-whatsapp"></i> Resi
                                </a>
                                <?php endif; ?>
                                <a href="<?= BASEURL; ?>/keuangan/cetakKwitansi/<?= $t['id']; ?>" target="_blank" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors inline-flex items-center gap-1" title="Cetak Kwitansi">
                                    <i class="fas fa-print"></i> Kwitansi
                                </a>
                                <a href="<?= BASEURL; ?>/keuangan/batalBayar/<?= $t['id']; ?>" onclick="return confirm('Yakin ingin membatalkan pelunasan ini? Data kas akan dihapus dan notifikasi pembatalan akan dikirim ke WA orang tua.')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors inline-flex items-center gap-1" title="Batal Bayar">
                                    <i class="fas fa-times-circle"></i> Batal
                                </a>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Generate -->
    <div x-show="modalGenerate" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalGenerate" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalGenerate = false"></div>
            <div x-show="modalGenerate" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Generate Tagihan Masal</h3>
                    <button @click="modalGenerate = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/keuangan/generateTagihan" method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Tagihan (Tarif)</label>
                            <select name="kategori_id" x-model="selectedKategori" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="">-- Pilih Jenis Tagihan --</option>
                                <template x-for="k in kategoriList" :key="k.id">
                                    <option :value="k.id" x-text="k.nama_kategori"></option>
                                </template>
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Pilih Master Tarif, nominal akan otomatis terisi.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Ajaran</label>
                            <select name="tahun_ajaran" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <?php 
                                $current_year = date('Y');
                                for($i = $current_year - 1; $i <= $current_year + 2; $i++): 
                                    $thn = $i . '/' . ($i+1);
                                ?>
                                    <option value="<?= $thn; ?>" <?= ($i == $current_year) ? 'selected' : ''; ?>><?= $thn; ?></option>
                                <?php endfor; ?>
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Otomatis generate untuk bulan Juli s/d Juni tahun berikutnya.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nominal per Bulan (Rp)</label>
                            <input type="number" name="nominal" x-model="nominalTagihan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Jatuh Tempo Tiap Bulan</label>
                            <input type="number" name="tanggal_jatuh_tempo" min="1" max="31" value="10" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalGenerate = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Bayar -->
    <div x-show="modalBayar" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalBayar" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalBayar = false"></div>
            <div x-show="modalBayar" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Proses Pembayaran</h3>
                    <button @click="modalBayar = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/keuangan/bayar" method="post">
                    <input type="hidden" name="tagihan_id" :value="activeTagihanId">
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg mb-4">
                            <p class="text-sm text-slate-500">Sisa Tagihan:</p>
                            <p class="text-2xl font-bold text-slate-900">Rp <span x-text="new Intl.NumberFormat('id-ID').format(sisaTagihan)"></span></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nominal Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" :max="sisaTagihan" :value="sisaTagihan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-lg font-medium">
                            <p class="text-xs text-slate-500 mt-1">Bisa dikurangi jika ingin mencicil.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Metode Pembayaran</label>
                            <select name="metode" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="Cash">Cash (Tunai)</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan (Opsional)</label>
                            <input type="text" name="keterangan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalBayar = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Proses Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function applyTableFilter() {
    const kategori = document.querySelector('[x-model="filterKategori"]').value;
    const status = document.querySelector('[x-model="filterStatus"]').value;
    
    const rows = document.querySelectorAll('.tagihan-row');
    rows.forEach(row => {
        let show = true;
        if (kategori && row.dataset.kategori !== kategori) show = false;
        if (status && row.dataset.status !== status) show = false;
        
        row.style.display = show ? '' : 'none';
    });
}
</script>
