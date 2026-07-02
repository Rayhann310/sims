<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="naikKelasData()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
        <p class="text-sm text-slate-500 mt-1">Pindahkan siswa secara masal dari Rombel asal ke Rombel tujuan (Tahun Akademik baru).</p>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <form action="<?= BASEURL; ?>/akademik/prosesNaikKelas" method="post" class="space-y-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- SUMBER: Rombel Asal -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">1. Pilih Rombel Asal</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Akademik Asal</label>
                        <select x-model="source_ta" @change="fetchSourceRombel()" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">-- Pilih Tahun Akademik --</option>
                            <?php foreach($data['tahun_akademik'] as $ta): ?>
                                <option value="<?= $ta['id'] ?>"><?= $ta['nama_tahun'] ?> - <?= $ta['semester'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Rombel Asal</label>
                        <select x-model="source_rombel" @change="fetchStudents()" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white" :disabled="!source_ta || loading_source_rombel">
                            <option value="">-- Pilih Rombel --</option>
                            <template x-for="r in source_rombels" :key="r.id">
                                <option :value="r.id" x-text="r.nama_rombel + ' (' + r.nama_kelas + ')'"></option>
                            </template>
                        </select>
                        <p x-show="loading_source_rombel" class="text-xs text-blue-500 mt-1">Loading...</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-medium text-slate-700 mb-2">Daftar Siswa (<span x-text="students.length"></span>)</h4>
                    <div class="border border-slate-200 rounded-lg max-h-80 overflow-y-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left" data-sortable="false">
                                        <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded">
                                    </th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase">NISN</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Nama Siswa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                <template x-for="s in students" :key="s.anggota_id">
                                    <tr class="hover:bg-slate-50 cursor-pointer">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <input type="checkbox" name="siswa_ids[]" :value="s.id" x-model="selectedStudents" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-slate-900" x-text="s.nisn"></td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-slate-700" x-text="s.nama_siswa"></td>
                                    </tr>
                                </template>
                                <tr x-show="students.length === 0">
                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-500">Belum ada siswa dipilih atau rombel kosong.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TUJUAN: Rombel Tujuan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">2. Pilih Rombel Tujuan (Baru)</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Akademik Tujuan</label>
                            <select x-model="dest_ta" @change="fetchDestRombel()" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none bg-white">
                                <option value="">-- Pilih Tahun Akademik --</option>
                                <?php foreach($data['tahun_akademik'] as $ta): ?>
                                    <option value="<?= $ta['id'] ?>"><?= $ta['nama_tahun'] ?> - <?= $ta['semester'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Rombel Tujuan</label>
                            <select name="dest_rombel_id" x-model="dest_rombel" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none bg-white" :disabled="loading_dest_rombel">
                                <option value="">-- Pilih Rombel Tujuan --</option>
                                <option value="ALUMNI" class="font-bold text-blue-600">🎓 Luluskan (Jadikan Alumni)</option>
                                <template x-for="r in dest_rombels" :key="r.id">
                                    <option :value="r.id" x-text="r.nama_rombel + ' (' + r.nama_kelas + ')'"></option>
                                </template>
                            </select>
                            <p x-show="loading_dest_rombel" class="text-xs text-green-500 mt-1">Loading...</p>
                        </div>

                        <div x-show="dest_rombel === 'ALUMNI'" x-transition class="bg-blue-50 p-4 rounded-lg border border-blue-200 mt-2">
                            <label class="block text-sm font-medium text-blue-800 mb-1">Tahun Lulus <span class="text-rose-500">*</span></label>
                            <input type="number" name="tahun_lulus" value="<?= date('Y') ?>" placeholder="Contoh: 2026" class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <p class="text-xs text-blue-600 mt-1">Isi tahun lulus agar data alumni mudah difilter (misal: 2006).</p>
                        </div>
                    </div>
                    
                    <div class="mt-8 bg-amber-50 rounded-lg p-4 border border-amber-200">
                        <div class="flex">
                            <svg class="w-5 h-5 text-amber-600 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="text-sm text-amber-800">
                                <p class="font-medium">Perhatian:</p>
                                <p class="mt-1">Siswa yang diceklis di sebelah kiri akan disalin (didaftarkan) ke rombel tujuan di atas. Data lama siswa di rombel asal tidak akan dihapus (sebagai riwayat).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200 text-right">
                    <button type="submit" :disabled="selectedStudents.length === 0 || !dest_rombel" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors shadow-sm w-full md:w-auto">
                        <span x-show="dest_rombel !== 'ALUMNI'">Proses Naik Kelas (<span x-text="selectedStudents.length"></span> Siswa)</span>
                        <span x-show="dest_rombel === 'ALUMNI'">🎓 Proses Lulus/Alumni (<span x-text="selectedStudents.length"></span> Siswa)</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('naikKelasData', () => ({
        source_ta: '',
        source_rombel: '',
        dest_ta: '',
        dest_rombel: '',
        
        source_rombels: [],
        dest_rombels: [],
        students: [],
        
        selectedStudents: [],
        selectAll: false,
        
        loading_source_rombel: false,
        loading_dest_rombel: false,

        async fetchSourceRombel() {
            this.source_rombel = '';
            this.source_rombels = [];
            this.students = [];
            this.selectedStudents = [];
            this.selectAll = false;
            
            if(!this.source_ta) return;
            
            this.loading_source_rombel = true;
            try {
                const res = await fetch(`<?= BASEURL; ?>/akademik/apiGetRombel/${this.source_ta}`);
                this.source_rombels = await res.json();
            } catch (err) {
                console.error(err);
            } finally {
                this.loading_source_rombel = false;
            }
        },
        
        async fetchDestRombel() {
            this.dest_rombel = '';
            this.dest_rombels = [];
            
            if(!this.dest_ta) return;
            
            this.loading_dest_rombel = true;
            try {
                const res = await fetch(`<?= BASEURL; ?>/akademik/apiGetRombel/${this.dest_ta}`);
                this.dest_rombels = await res.json();
            } catch (err) {
                console.error(err);
            } finally {
                this.loading_dest_rombel = false;
            }
        },
        
        async fetchStudents() {
            this.students = [];
            this.selectedStudents = [];
            this.selectAll = false;
            
            if(!this.source_rombel) return;
            
            try {
                const res = await fetch(`<?= BASEURL; ?>/akademik/apiGetAnggota/${this.source_rombel}`);
                this.students = await res.json();
            } catch (err) {
                console.error(err);
            }
        },
        
        toggleSelectAll() {
            if(this.selectAll) {
                this.selectedStudents = this.students.map(s => s.id); // siswa_id in table is s.id. wait, API returns s.* (id of siswa).
            } else {
                this.selectedStudents = [];
            }
        }
    }));
});
</script>
