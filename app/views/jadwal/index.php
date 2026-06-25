<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="jadwalData()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Kelola Jadwal Pelajaran untuk tiap Rombongan Belajar.</p>
        </div>
        <?php if ($_SESSION['user']['role'] == 'admin') : ?>
        <div class="flex space-x-2">
            <button @click="openModal()" :disabled="!rombel_id" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Jadwal
            </button>
            <button @click="openTambahModal()" :disabled="!rombel_id" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i>
                Tambah Jadwal
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">Filter Jadwal</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Akademik</label>
                <select x-model="ta_id" @change="fetchRombel()" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">-- Pilih Tahun Akademik --</option>
                    <?php foreach($data['tahun_akademik'] as $ta): ?>
                        <option value="<?= $ta['id'] ?>"><?= $ta['nama_tahun'] ?> - <?= $ta['semester'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Rombel</label>
                <select x-model="rombel_id" @change="fetchJadwal()" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white" :disabled="!ta_id">
                    <option value="">-- Pilih Rombel --</option>
                    <template x-for="r in rombels" :key="r.id">
                        <option :value="r.id" x-text="r.nama_rombel + ' (' + r.nama_kelas + ')'"></option>
                    </template>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base font-semibold text-slate-800">Daftar Jadwal <span x-show="rombel_id" class="text-blue-600">(Pilih rombel untuk melihat)</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Hari</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Guru Pengampu</th>
                        <?php if ($_SESSION['user']['role'] == 'admin') : ?>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <template x-for="j in jadwal" :key="j.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900" x-text="j.hari"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><span x-text="j.jam_mulai"></span> - <span x-text="j.jam_selesai"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900" x-text="j.nama_mapel"></div>
                                <div class="text-xs text-slate-500" x-text="'Kode: ' + j.kode_mapel"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                <span x-text="j.nama_guru" :class="j.nama_guru === '<?= addslashes($_SESSION['user']['nama_lengkap']) ?>' ? 'font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-200' : ''"></span>
                            </td>
                            <?php if ($_SESSION['user']['role'] == 'admin') : ?>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a :href="'<?= BASEURL; ?>/jadwal/hapus/' + j.id" onclick="return confirm('Yakin ingin menghapus jadwal ini?');" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    </template>
                    <tr x-show="jadwal.length === 0 && rombel_id">
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            Belum ada jadwal untuk rombel ini.
                        </td>
                    </tr>
                    <tr x-show="!rombel_id">
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            Silakan pilih Tahun Akademik dan Rombel terlebih dahulu.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Manual -->
    <div x-show="tambahModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="tambahModalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="tambahModalOpen = false"></div>

            <div x-show="tambahModalOpen" x-transition class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Tambah Jadwal Pelajaran</h3>
                    <button @click="tambahModalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/jadwal/tambah" method="post" @submit="cekKonflik($event)">
                    <input type="hidden" name="rombel_id" :value="rombel_id">
                    
                    <div class="space-y-4">
                        <div x-show="errorMessage" class="bg-red-50 text-red-700 p-3 rounded-lg border border-red-200 text-sm mb-4" x-text="errorMessage" style="display: none;"></div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                            <select name="mapel_id" x-model="formData.mapel_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Mapel --</option>
                                <?php foreach($data['mapel_list'] as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?> (<?= $m['kode_mapel'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Guru Pengampu</label>
                            <select name="guru_id" x-model="formData.guru_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Guru --</option>
                                <?php foreach($data['guru_list'] as $g): ?>
                                    <option value="<?= $g['id'] ?>"><?= $g['nama_lengkap'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Hari</label>
                            <select name="hari" x-model="formData.hari" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Jam Mulai</label>
                                <input type="time" name="jam_mulai" x-model="formData.jam_mulai" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Jam Selesai</label>
                                <input type="time" name="jam_selesai" x-model="formData.jam_selesai" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="tambahModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm" :disabled="isSubmitting">
                            <span x-show="!isSubmitting">Simpan Jadwal</span>
                            <span x-show="isSubmitting"><i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>

            <div x-show="modalOpen" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Import Jadwal Massal</h3>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/jadwal/import" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="rombel_id" :value="rombel_id">
                    
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <p class="text-sm text-blue-800">
                                Pastikan format Excel Anda memiliki urutan kolom (tanpa header):<br>
                                <strong>Mapel ID | Guru ID | Hari | Jam Mulai | Jam Selesai</strong><br>
                                Baris pertama (header) akan diabaikan.
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">File Excel (.xlsx)</label>
                            <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors shadow-sm">Upload & Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('jadwalData', () => ({
        ta_id: '',
        rombel_id: '',
        rombels: [],
        jadwal: [],
        modalOpen: false,
        tambahModalOpen: false,
        isSubmitting: false,
        errorMessage: '',
        formData: {
            mapel_id: '',
            guru_id: '',
            hari: '',
            jam_mulai: '',
            jam_selesai: ''
        },

        async fetchRombel() {
            this.rombel_id = '';
            this.rombels = [];
            this.jadwal = [];
            
            if(!this.ta_id) return;
            
            try {
                const res = await fetch(`<?= BASEURL; ?>/akademik/apiGetRombel/${this.ta_id}`);
                this.rombels = await res.json();
            } catch (err) {
                console.error(err);
            }
        },
        
        async fetchJadwal() {
            this.jadwal = [];
            
            if(!this.rombel_id) return;
            
            try {
                const res = await fetch(`<?= BASEURL; ?>/jadwal/getJadwalAjax/${this.rombel_id}`);
                this.jadwal = await res.json();
            } catch (err) {
                console.error(err);
            }
        },

        openModal() {
            if(this.rombel_id) {
                this.modalOpen = true;
            } else {
                alert('Silakan pilih Tahun Akademik dan Rombel terlebih dahulu.');
            }
        },

        openTambahModal() {
            if(this.rombel_id) {
                this.tambahModalOpen = true;
                this.errorMessage = '';
                this.formData = { mapel_id: '', guru_id: '', hari: '', jam_mulai: '', jam_selesai: '' };
            } else {
                alert('Silakan pilih Tahun Akademik dan Rombel terlebih dahulu.');
            }
        },

        async cekKonflik(e) {
            e.preventDefault();
            this.isSubmitting = true;
            this.errorMessage = '';

            const fd = new FormData(e.target);
            try {
                const res = await fetch(`<?= BASEURL; ?>/jadwal/cekKonflik`, {
                    method: 'POST',
                    body: fd
                });
                const data = await res.json();
                
                if (data.konflik_rombel) {
                    this.errorMessage = `Bentrok jam rombel! Kelas ini sudah ada pelajaran ${data.konflik_rombel.nama_mapel} di jam ${data.konflik_rombel.jam_mulai} - ${data.konflik_rombel.jam_selesai}`;
                    this.isSubmitting = false;
                    return;
                }
                
                if (data.konflik_guru) {
                    this.errorMessage = `Bentrok jam guru! Guru ini sedang mengajar di kelas ${data.konflik_guru.nama_rombel} di jam ${data.konflik_guru.jam_mulai} - ${data.konflik_guru.jam_selesai}`;
                    this.isSubmitting = false;
                    return;
                }

                // Jika lolos konflik, submit form secara natural
                e.target.submit();
            } catch (err) {
                this.errorMessage = "Terjadi kesalahan saat memeriksa bentrok jadwal.";
                this.isSubmitting = false;
            }
        }
    }));
});
</script>
