<div x-data="{
    addModalOpen: false,
    editModalOpen: false,
    deleteModalOpen: false,
    deleteId: null,
    editData: {},
    openEdit(j) {
        this.editData = j;
        this.editModalOpen = true;
    },
    confirmDelete(id) {
        this.deleteId = id;
        this.deleteModalOpen = true;
    }
}" class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Master Jabatan Guru</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar jabatan yang dapat diberikan kepada guru.</p>
        </div>
        <button @click="addModalOpen = true" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fas fa-plus"></i>
            Tambah Jabatan
        </button>
    </div>

    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="p-4 border-l-4 <?= $_SESSION['flash']['tipe'] == 'success' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-red-500 bg-red-50 text-red-800' ?> rounded-r-lg text-sm font-medium flex items-center gap-2">
            <i class="fas <?= $_SESSION['flash']['tipe'] == 'success' ? 'fa-check-circle text-emerald-500' : 'fa-times-circle text-red-500' ?>"></i>
            <?= $_SESSION['flash']['pesan'] ?> <?= $_SESSION['flash']['aksi'] ?>.
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-semibold tracking-wider">
                    <tr>
                        <th class="text-left px-6 py-4">No</th>
                        <th class="text-left px-6 py-4">Nama Jabatan</th>
                        <th class="text-left px-6 py-4">Deskripsi</th>
                        <th class="text-left px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(!empty($data['jabatan'])): ?>
                        <?php foreach($data['jabatan'] as $i => $j): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-500"><?= $i + 1 ?></td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                                    <?= htmlspecialchars($j['nama_jabatan']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($j['deskripsi'] ?: '-') ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button @click="openEdit(<?= htmlspecialchars(json_encode($j)) ?>)" class="flex items-center gap-1.5 text-xs font-semibold text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-pen"></i> Edit
                                    </button>
                                    <button @click="confirmDelete(<?= $j['id'] ?>)" class="flex items-center gap-1.5 text-xs font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-briefcase text-4xl mb-3 block text-slate-300"></i>
                                Belum ada data jabatan. Klik tombol <strong>Tambah Jabatan</strong> untuk memulai.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="addModalOpen" x-transition class="relative bg-white rounded-2xl shadow-xl w-full max-w-md border border-slate-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-plus-circle mr-2 text-emerald-500"></i>Tambah Jabatan</h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                </div>
                <form action="<?= BASEURL ?>/jabatan/tambah" method="POST" class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jabatan" required placeholder="cth. Kepala Sekolah" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat jabatan (opsional)" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="editModalOpen" x-transition class="relative bg-white rounded-2xl shadow-xl w-full max-w-md border border-slate-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-pen mr-2 text-amber-500"></i>Edit Jabatan</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                </div>
                <form action="<?= BASEURL ?>/jabatan/ubah" method="POST" class="px-6 py-5 space-y-4">
                    <input type="hidden" name="id" :value="editData.id">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jabatan" required :value="editData.nama_jabatan" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" :value="editData.deskripsi" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="deleteModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="deleteModalOpen" x-transition class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm border border-slate-200 p-6 text-center">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trash text-red-500 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Jabatan?</h3>
                <p class="text-sm text-slate-500 mb-6">Jabatan yang masih dipakai oleh guru tidak dapat dihapus.</p>
                <div class="flex justify-center gap-3">
                    <button @click="deleteModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Batal</button>
                    <a :href="`<?= BASEURL ?>/jabatan/hapus/${deleteId}`" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

</div>
