<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ modalOpen: false }">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data referensi Tingkat Kelas dan Jurusan (MIPA, IPS, dll).</p>
        </div>
        <button @click="modalOpen = true" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Kelas
        </button>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Kelas</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jurusan / Program</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php $no = 1; foreach($data['kelas'] as $k) : ?>
                    <tr class="hover:bg-slate-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $no++; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center text-blue-600 font-bold text-lg shadow-inner">
                                    <?= substr($k['nama_kelas'], 0, 1) ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900"><?= $k['nama_kelas']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                Tingkat <?= $k['tingkat']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            <?= !empty($k['jurusan']) ? $k['jurusan'] : '<span class="text-slate-400 italic">Umum</span>'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="$dispatch('edit-kelas', { id: '<?= $k['id'] ?>', nama_kelas: '<?= $k['nama_kelas'] ?>', tingkat: '<?= $k['tingkat'] ?>', jurusan: '<?= $k['jurusan'] ?>' })" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-md transition-colors mr-2">Edit</button>
                            <a href="<?= BASEURL; ?>/akademik/hapusKelas/<?= $k['id']; ?>" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors" onclick="return confirm('Yakin ingin menghapus kelas ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['kelas'])): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                            Belum ada master data kelas.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>

            <!-- Modal Panel -->
            <div x-show="modalOpen" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Tambah Kelas Baru</h3>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/akademik/tambahKelas" method="post">
                    <div class="space-y-4">
                        <div>
                            <label for="nama_kelas" class="block text-sm font-medium text-slate-700 mb-1">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" placeholder="Contoh: X MIPA 1, Duabelas 1" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="tingkat" class="block text-sm font-medium text-slate-700 mb-1">Tingkat</label>
                            <select name="tingkat" id="tingkat" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white" required>
                                <option value="10">10 (Kelas X)</option>
                                <option value="11">11 (Kelas XI)</option>
                                <option value="12">12 (Kelas XII)</option>
                            </select>
                        </div>
                        <div>
                            <label for="jurusan" class="block text-sm font-medium text-slate-700 mb-1">Jurusan / Program <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <select name="jurusan" id="jurusan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                                <option value="">-- Pilih Jurusan / Umum --</option>
                                <?php foreach($data['master_jurusan'] as $j): ?>
                                    <option value="<?= $j['nama_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-data="{ modalEditOpen: false, formData: {id: '', nama_kelas: '', jurusan: ''} }" 
         @edit-kelas.window="modalEditOpen = true; formData = $event.detail;" 
         x-show="modalEditOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalEditOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalEditOpen = false"></div>

            <!-- Modal Panel -->
            <div x-show="modalEditOpen" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Edit Kelas</h3>
                    <button @click="modalEditOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/akademik/editKelas" method="post">
                    <input type="hidden" name="id" x-model="formData.id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kelas</label>
                            <input type="text" name="nama_kelas" x-model="formData.nama_kelas" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat</label>
                            <select name="tingkat" x-model="formData.tingkat" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white" required>
                                <option value="10">10 (Kelas X)</option>
                                <option value="11">11 (Kelas XI)</option>
                                <option value="12">12 (Kelas XII)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan / Program</label>
                            <select name="jurusan" x-model="formData.jurusan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                                <option value="">-- Pilih Jurusan / Umum --</option>
                                <?php foreach($data['master_jurusan'] as $j): ?>
                                    <option value="<?= $j['nama_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalEditOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
