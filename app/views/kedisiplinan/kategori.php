<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="p-4 border-l-4 border-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-500 bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-50 flex items-center gap-3 rounded-r-lg">
            <p class="text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-800 text-sm font-medium">
                Kategori <strong><?= $_SESSION['flash']['pesan'] ?></strong> <?= $_SESSION['flash']['aksi'] ?>.
            </p>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Master Kategori Kedisiplinan</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola jenis pelanggaran (poin bertambah) & penghargaan (poin berkurang).</p>
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kategori
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Tingkatan</th>
                        <th class="px-6 py-4">Poin Default</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php $no=1; foreach($data['kategori'] as $k): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4"><?= $no++; ?></td>
                        <td class="px-6 py-4 font-medium"><?= $k['nama_kategori']; ?></td>
                        <td class="px-6 py-4">
                            <?php if($k['jenis'] == 'Pelanggaran'): ?>
                                <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-red-600 bg-red-100 rounded-full">Pelanggaran</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-100 rounded-full">Penghargaan</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded"><?= $k['tingkatan']; ?></span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800"><?= $k['poin']; ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= BASEURL; ?>/kedisiplinan/hapusKategori/<?= $k['id']; ?>" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors inline-block" onclick="return confirm('Yakin ingin menghapus kategori ini?');" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalTambah').classList.add('hidden')"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full border border-slate-100">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Tambah Kategori</h3>
                    <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="<?= BASEURL; ?>/kedisiplinan/tambahKategori" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori / Catatan</label>
                        <input type="text" name="nama_kategori" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis</label>
                        <select name="jenis" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                            <option value="Pelanggaran">Pelanggaran (Poin Menambah)</option>
                            <option value="Penghargaan">Penghargaan / Kebaikan (Poin Mengurangi)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tingkatan</label>
                        <select name="tingkatan" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                            <option value="Ringan">Ringan</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Berat">Berat</option>
                            <option value="Prestasi">Prestasi Khusus</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Poin Default</label>
                        <input type="number" name="poin" required min="1" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
