<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Kelola label tag untuk memudahkan pencarian dan pengelompokan topik.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= BASEURL; ?>/artikel" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Artikel
            </a>
            <button onclick="openModalTambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fas fa-plus"></i> Tambah Tag
            </button>
        </div>
    </div>

    <?php Flasher::flash(); ?>

    <!-- Table Tag -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-center w-16">No</th>
                        <th class="px-6 py-3">Nama Tag</th>
                        <th class="px-6 py-3">Slug (URL)</th>
                        <th class="px-6 py-3 text-center">Digunakan di Artikel</th>
                        <th class="px-6 py-3 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['tag_list'])): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                Belum ada tag yang didaftarkan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($data['tag_list'] as $tag): ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-3 text-center font-medium text-slate-500"><?= $no++; ?></td>
                                <td class="px-6 py-3 font-semibold text-slate-800">
                                    <span class="inline-block px-2.5 py-1 rounded bg-slate-100 text-slate-700 border border-slate-200 font-mono text-xs">
                                        #<?= htmlspecialchars($tag['nama_tag']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-slate-500 font-mono text-xs">
                                    <?= htmlspecialchars($tag['slug']); ?>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                        <?= number_format($tag['total_artikel']); ?> artikel
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="openModalEdit(<?= htmlspecialchars(json_encode($tag)); ?>)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Tag">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <a href="<?= BASEURL; ?>/artikel/hapusTag/<?= $tag['id']; ?>" onclick="return confirm('Hapus tag ini?');" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Tag">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Tag (Tambah & Edit) -->
<div id="modalTag" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
            <form id="formTag" method="POST" action="<?= BASEURL; ?>/artikel/tambahTag">
                <input type="hidden" name="id" id="tag_id">
                
                <div class="bg-white px-6 pt-5 pb-4">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3" id="modalTitle">Tambah Tag Baru</h3>
                    
                    <div class="space-y-4 mt-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Tag <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_tag" id="tag_nama" required placeholder="Contoh: Pendidikan, Olahraga, Beasiswa" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-3 flex flex-row-reverse gap-2">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModalTambah() {
    document.getElementById('modalTitle').innerText = 'Tambah Tag Baru';
    document.getElementById('formTag').action = '<?= BASEURL; ?>/artikel/tambahTag';
    document.getElementById('tag_id').value = '';
    document.getElementById('tag_nama').value = '';
    document.getElementById('modalTag').classList.remove('hidden');
}

function openModalEdit(data) {
    document.getElementById('modalTitle').innerText = 'Edit Tag';
    document.getElementById('formTag').action = '<?= BASEURL; ?>/artikel/updateTag';
    document.getElementById('tag_id').value = data.id;
    document.getElementById('tag_nama').value = data.nama_tag;
    document.getElementById('modalTag').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalTag').classList.add('hidden');
}
</script>
