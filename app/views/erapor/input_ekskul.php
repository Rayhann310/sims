<div class="space-y-6 animate-fade-in-up" x-data="inputEkskul()">
    
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Ekstrakurikuler</h2>
                <p class="text-slate-500 mt-1">Input kegiatan ekstrakurikuler yang diikuti siswa.</p>
            </div>
            <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="<?= BASEURL; ?>/erapor/ekskul" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Kelas</label>
                <select name="rombel_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none bg-slate-50 focus:bg-white" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($data['rombel_list'] as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $data['selected_rombel'] == $r['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nama_rombel']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 transition-all shadow-sm flex items-center">
                    <i class="fas fa-search mr-2"></i> Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    <?php if(isset($data['siswa_list'])): ?>
    <!-- Table Siswa -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Siswa</h3>
            <p class="text-sm text-slate-500">Pilih siswa untuk menambah atau mengedit ekskul.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 w-16">No</th>
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 min-w-[200px]">NISN / Nama Siswa</th>
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no=1; foreach($data['siswa_list'] as $s): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4 md:px-6 text-sm text-slate-600"><?= $no++; ?></td>
                        <td class="py-4 px-4 md:px-6">
                            <p class="font-medium text-slate-800 whitespace-normal min-w-[150px]"><?= htmlspecialchars($s['nama_lengkap']) ?></p>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($s['nisn']) ?></p>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button @click="openModal(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['nama_lengkap'])) ?>')" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                <i class="fas fa-plus-circle mr-1"></i> Kelola Ekskul
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal Kelola Ekskul -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div x-show="modalOpen" @click.away="closeModal()" x-transition class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Kelola Ekstrakurikuler</h3>
                    <p class="text-sm text-slate-500" x-text="siswaName"></p>
                </div>
                <button @click="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <!-- Form Tambah -->
                <form @submit.prevent="saveEkskul()" class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-6">
                    <div class="sm:col-span-5">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Nama Ekskul</label>
                        <input type="text" x-model="form.nama_ekskul" required class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Nilai</label>
                        <select x-model="form.nilai" required class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-sm">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Keterangan</label>
                        <input type="text" x-model="form.keterangan" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-sm">
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </form>

                <!-- Daftar Ekskul Tersimpan -->
                <div class="border border-slate-100 rounded-xl overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="py-2 px-4 text-xs font-semibold text-slate-600 min-w-[150px]">Nama Ekskul</th>
                                <th class="py-2 px-4 text-xs font-semibold text-slate-600">Nilai</th>
                                <th class="py-2 px-4 text-xs font-semibold text-slate-600 min-w-[150px]">Keterangan</th>
                                <th class="py-2 px-4 text-xs font-semibold text-slate-600 text-center w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="item in ekskulList" :key="item.id">
                                <tr>
                                    <td class="py-2 px-4 text-sm text-slate-700 whitespace-normal" x-text="item.nama_ekskul"></td>
                                    <td class="py-2 px-4 text-sm text-slate-700 font-bold" x-text="item.nilai"></td>
                                    <td class="py-2 px-4 text-sm text-slate-700 whitespace-normal" x-text="item.keterangan"></td>
                                    <td class="py-2 px-4 text-center">
                                        <button @click="deleteEkskul(item.id)" class="text-red-500 hover:text-red-700 p-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="ekskulList.length === 0">
                                <td colspan="4" class="py-4 text-center text-sm text-slate-500">Belum ada ekstrakurikuler.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('inputEkskul', () => ({
        modalOpen: false,
        siswaId: null,
        siswaName: '',
        ekskulList: [],
        form: {
            nama_ekskul: '',
            nilai: 'B',
            keterangan: ''
        },
        openModal(id, name) {
            this.siswaId = id;
            this.siswaName = name;
            this.modalOpen = true;
            this.fetchEkskul();
        },
        closeModal() {
            this.modalOpen = false;
            this.siswaId = null;
            this.form = {nama_ekskul: '', nilai: 'B', keterangan: ''};
        },
        fetchEkskul() {
            fetch(`<?= BASEURL; ?>/erapor/getEkskulSiswaAjax?siswa_id=${this.siswaId}`)
                .then(res => res.json())
                .then(data => {
                    this.ekskulList = data;
                });
        },
        saveEkskul() {
            const formData = new FormData();
            formData.append('siswa_id', this.siswaId);
            formData.append('nama_ekskul', this.form.nama_ekskul);
            formData.append('nilai', this.form.nilai);
            formData.append('keterangan', this.form.keterangan);
            
            fetch('<?= BASEURL; ?>/erapor/saveEkskul', {
                method: 'POST',
                body: formData
            }).then(res => res.json()).then(data => {
                this.form = {nama_ekskul: '', nilai: 'B', keterangan: ''};
                this.fetchEkskul();
            });
        },
        deleteEkskul(id) {
            if(confirm('Hapus ekskul ini?')) {
                const formData = new FormData();
                formData.append('id', id);
                fetch('<?= BASEURL; ?>/erapor/hapusEkskul', {
                    method: 'POST',
                    body: formData
                }).then(() => this.fetchEkskul());
            }
        }
    }));
});
</script>
