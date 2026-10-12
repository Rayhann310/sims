<div class="p-6">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Kelola kategori dan rincian biaya pendaftaran SPMB.</p>
        </div>
        <button onclick="document.getElementById('modalTambahKategori').classList.remove('hidden')" class="bg-primary hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Kategori Baru
        </button>
    </div>

    <?php Flasher::flash(); ?>

    <div class="space-y-6">
        <?php if(empty($data['kategori'])): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 mb-1">Belum Ada Kategori Biaya</h3>
                <p class="text-slate-500">Silakan tambahkan kategori biaya baru untuk mulai mengelola rincian biaya.</p>
            </div>
        <?php else: ?>
            <?php foreach($data['kategori'] as $k): ?>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Kategori Header -->
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($k['nama_kategori']); ?></h3>
                            <?php if(!empty($k['deskripsi'])): ?>
                                <p class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($k['deskripsi']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <button onclick="openModalRincian(<?= $k['id']; ?>, '<?= htmlspecialchars(addslashes($k['nama_kategori'])); ?>')" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded text-sm font-medium transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Rincian
                            </button>
                            <a href="<?= BASEURL; ?>/adminspmb/hapusKategoriBiaya/<?= $k['id']; ?>" onclick="return confirm('Yakin ingin menghapus kategori ini beserta seluruh rinciannya?');" class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded text-sm font-medium transition-colors">
                                Hapus
                            </a>
                        </div>
                    </div>

                    <!-- Rincian Biaya Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-white border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Nama Rincian</th>
                                    <th class="px-6 py-3 font-semibold text-right">Nominal (Rp)</th>
                                    <th class="px-6 py-3 font-semibold text-center w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php 
                                    $total = 0;
                                    if(empty($k['rincian'])): 
                                ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-slate-500 italic">Belum ada rincian biaya di kategori ini.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($k['rincian'] as $r): 
                                        $total += $r['nominal'];
                                    ?>
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-6 py-3 font-medium text-slate-700"><?= htmlspecialchars($r['nama_rincian']); ?></td>
                                            <td class="px-6 py-3 text-right text-slate-600"><?= number_format($r['nominal'], 0, ',', '.'); ?></td>
                                            <td class="px-6 py-3 text-center">
                                                <a href="<?= BASEURL; ?>/adminspmb/hapusRincianBiaya/<?= $r['id']; ?>" onclick="return confirm('Hapus rincian ini?');" class="text-red-500 hover:text-red-700 p-1">
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-slate-50">
                                        <td class="px-6 py-3 font-bold text-slate-900 text-right">Total Keseluruhan</td>
                                        <td class="px-6 py-3 font-bold text-emerald-600 text-right text-base"><?= number_format($total, 0, ',', '.'); ?></td>
                                        <td></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div id="modalTambahKategori" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" aria-hidden="true" onclick="document.getElementById('modalTambahKategori').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="<?= BASEURL; ?>/adminspmb/tambahKategoriBiaya" method="POST">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-slate-900 mb-4" id="modal-title">Tambah Kategori Biaya Baru</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: SMA Reguler, SMA + Pondok">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Deskripsi singkat..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Kategori
                    </button>
                    <button type="button" onclick="document.getElementById('modalTambahKategori').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Rincian -->
<div id="modalTambahRincian" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" aria-hidden="true" onclick="document.getElementById('modalTambahRincian').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="<?= BASEURL; ?>/adminspmb/tambahRincianBiaya" method="POST">
                <input type="hidden" name="kategori_id" id="rincian_kategori_id">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-slate-900 mb-1" id="modal-title">Tambah Rincian Biaya</h3>
                    <p class="text-sm text-slate-500 mb-4">Kategori: <span id="rincian_kategori_nama" class="font-semibold text-slate-700"></span></p>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Rincian (Item)</label>
                            <input type="text" name="nama_rincian" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: SPP Bulan Pertama, Uang Seragam, dll">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nominal Biaya (Rp)</label>
                            <input type="number" name="nominal" required min="0" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: 1500000">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Rincian
                    </button>
                    <button type="button" onclick="document.getElementById('modalTambahRincian').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModalRincian(kategoriId, namaKategori) {
    document.getElementById('rincian_kategori_id').value = kategoriId;
    document.getElementById('rincian_kategori_nama').innerText = namaKategori;
    document.getElementById('modalTambahRincian').classList.remove('hidden');
}
</script>
