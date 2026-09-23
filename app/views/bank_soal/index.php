<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="bankSoalTable()">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Kelola bank soal ujian CBT di sini.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button @click="importModal = true" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-file-import mr-2"></i> Import Soal
            </button>
            <a href="<?= BASEURL; ?>/BankSoal/tambah" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Manual
            </a>
        </div>
    </div>

    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Toolbar: Search, Filter, Bulk Actions -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        
        <!-- Left Side: Mass Actions -->
        <div class="flex items-center gap-3">
            <template x-if="selectedIds.length > 0">
                <div class="flex items-center gap-2" x-transition>
                    <span class="text-sm font-medium text-slate-600 mr-2"><span x-text="selectedIds.length"></span> terpilih</span>
                    
                    <form action="<?= BASEURL; ?>/BankSoal/editMassal" method="POST" class="inline">
                        <input type="hidden" name="selected_ids" :value="JSON.stringify(selectedIds)">
                        <button type="submit" class="px-3 py-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 text-sm font-bold rounded-lg transition-colors shadow-sm border border-amber-200">
                            <i class="fas fa-edit mr-1"></i> Edit Massal
                        </button>
                    </form>
                    
                    <form action="<?= BASEURL; ?>/BankSoal/hapusMassal" method="POST" class="inline" @submit.prevent="if(confirm('Yakin ingin menghapus '+selectedIds.length+' soal terpilih?')) $el.submit()">
                        <input type="hidden" name="selected_ids" :value="JSON.stringify(selectedIds)">
                        <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 text-sm font-bold rounded-lg transition-colors shadow-sm border border-red-200">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus Massal
                        </button>
                    </form>
                </div>
            </template>
        </div>

        <!-- Right Side: Filters -->
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Search -->
            <div class="relative w-full md:w-48">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari pertanyaan..." class="w-full pl-9 pr-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <!-- Filter Tipe -->
            <select x-model="filterTipe" class="w-full md:w-36 px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">Semua Tipe</option>
                <option value="PG">PG Biasa</option>
                <option value="PG_KOMPLEKS">PG Kompleks</option>
                <option value="ESSAY">Esai</option>
            </select>
            <!-- Per Page -->
            <select x-model="perPage" class="w-full md:w-24 px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="10">10 / hal</option>
                <option value="50">50 / hal</option>
                <option value="100">100 / hal</option>
                <option value="999999">Semua</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left w-10">
                            <input type="checkbox" @change="toggleSelectAll" :checked="isAllSelected" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pertanyaan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipe Soal</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Tingkat Kesulitan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <template x-if="paginatedSoal.length === 0">
                        <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Tidak ada soal yang sesuai dengan filter/pencarian.</td></tr>
                    </template>
                    
                    <template x-for="row in paginatedSoal" :key="row.id_soal">
                        <tr class="hover:bg-slate-50 transition-colors" :class="selectedIds.includes(row.id_soal) ? 'bg-indigo-50' : ''">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" :value="row.id_soal" x-model="selectedIds" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded" x-text="row.nama_mapel || 'Umum'"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 max-w-xs">
                                <div class="truncate" x-text="stripHtml(row.pertanyaan)"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium" x-text="row.tipe_soal || 'PG'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span x-show="row.tingkat_kesulitan === 'Mudah'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Mudah</span>
                                <span x-show="row.tingkat_kesulitan === 'Sulit'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Sulit</span>
                                <span x-show="row.tingkat_kesulitan !== 'Mudah' && row.tingkat_kesulitan !== 'Sulit'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Sedang</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="activeSoal = row; detailModal = true" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors mr-2">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <a :href="'<?= BASEURL; ?>/BankSoal/edit/' + row.id_soal" class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-md transition-colors mr-2">Edit</a>
                                <a :href="'<?= BASEURL; ?>/BankSoal/hapus/' + row.id_soal" @click.prevent="if(confirm('Yakin ingin menghapus soal ini?')) window.location.href = $el.href" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">Hapus</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Controls -->
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex items-center justify-between" x-show="totalPages > 1">
            <div class="text-sm text-slate-500">
                Menampilkan halaman <span class="font-bold text-slate-700" x-text="currentPage"></span> dari <span class="font-bold text-slate-700" x-text="totalPages"></span>
            </div>
            <div class="flex gap-2">
                <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 border border-slate-300 rounded-md bg-white text-sm hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1 border border-slate-300 rounded-md bg-white text-sm hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Import Soal -->
    <div x-show="importModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="importModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="importModal = false"></div>

            <div x-show="importModal" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Import Bank Soal</h3>
                    <button @click="importModal = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/BankSoal/importPreview" method="post" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div class="flex gap-2 mb-4 justify-center">
                            <a href="<?= BASEURL; ?>/BankSoal/templateWord" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors rounded-lg text-xs font-bold w-1/2 justify-center">
                                <i class="fas fa-file-word mr-1"></i> Template Word
                            </a>
                            <a href="<?= BASEURL; ?>/BankSoal/templateExcel" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors rounded-lg text-xs font-bold w-1/2 justify-center">
                                <i class="fas fa-file-excel mr-1"></i> Template Excel
                            </a>
                        </div>
                        
                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-100 mb-4">
                            <p class="text-xs text-amber-800">
                                <strong>Info:</strong> Gunakan Word (.docx) jika soal mengandung <strong>Gambar</strong>. Gunakan Excel (.xlsx) khusus untuk soal teks saja.
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                            <select name="id_mapel" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <?php foreach($data['mapel'] as $m): ?>
                                    <option value="<?= $m['id']; ?>"><?= htmlspecialchars($m['nama_mapel']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">File Soal (.docx atau .xlsx)</label>
                            <input type="file" name="file_soal" accept=".docx, .xlsx" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="importModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Preview Soal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Soal -->
    <div x-show="detailModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="detailModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="detailModal = false"></div>

            <div x-show="detailModal" x-transition class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Detail Soal</h3>
                    <button @click="detailModal = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <template x-if="activeSoal">
                    <div class="space-y-6 text-left">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block">Pertanyaan</span>
                            <div class="text-slate-800 prose prose-sm max-w-none" x-html="activeSoal.pertanyaan"></div>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 block">Jawaban & Kunci</span>
                            <template x-if="activeSoal.tipe_soal === 'PG' || activeSoal.tipe_soal === 'PG_KOMPLEKS' || !activeSoal.tipe_soal">
                                <div class="space-y-3">
                                    <template x-for="opt in ['a', 'b', 'c', 'd', 'e']">
                                        <div x-show="activeSoal['opsi_' + opt]" class="flex items-start p-3 rounded-lg border" :class="(activeSoal.kunci_jawaban && activeSoal.kunci_jawaban.toUpperCase().includes(opt.toUpperCase())) ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-white'">
                                            <div class="w-8 h-8 shrink-0 rounded flex items-center justify-center font-bold mr-3" :class="(activeSoal.kunci_jawaban && activeSoal.kunci_jawaban.toUpperCase().includes(opt.toUpperCase())) ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500'" x-text="opt.toUpperCase()"></div>
                                            <div class="prose prose-sm max-w-none text-slate-700 flex-1 pt-1 break-words overflow-x-auto" x-html="activeSoal['opsi_' + opt]"></div>
                                            
                                            <div x-show="activeSoal.kunci_jawaban && activeSoal.kunci_jawaban.toUpperCase().includes(opt.toUpperCase())" class="shrink-0 ml-3 text-emerald-500 flex flex-col justify-center h-8">
                                                <i class="fas fa-check-circle text-xl"></i>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            
                            <template x-if="activeSoal.tipe_soal === 'ESSAY'">
                                <div class="p-4 rounded-lg border border-emerald-200 bg-emerald-50">
                                    <div class="font-semibold text-emerald-800 mb-1">Kata Kunci Validasi:</div>
                                    <div class="text-emerald-700 font-mono text-sm" x-text="activeSoal.kunci_jawaban"></div>
                                </div>
                            </template>
                        </div>
                        
                        <div class="flex gap-4 pt-4 border-t border-slate-100 text-sm">
                            <div class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-md font-medium"><i class="fas fa-tag mr-1"></i> <span x-text="activeSoal.tipe_soal || 'PG'"></span></div>
                            <div class="bg-amber-50 text-amber-700 px-3 py-1 rounded-md font-medium"><i class="fas fa-signal mr-1"></i> <span x-text="activeSoal.tingkat_kesulitan || 'Sedang'"></span></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function bankSoalTable() {
    return {
        importModal: false,
        detailModal: false,
        activeSoal: null,
        soalList: <?= json_encode($data['soal'] ?? []) ?>,
        
        searchQuery: '',
        filterTipe: '',
        perPage: 10,
        currentPage: 1,
        selectedIds: [],
        
        init() {
            this.$watch('searchQuery', () => { this.currentPage = 1; });
            this.$watch('filterTipe', () => { this.currentPage = 1; });
            this.$watch('perPage', () => { this.currentPage = 1; });
        },
        
        stripHtml(html) {
            let tmp = document.createElement("DIV");
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || "";
        },
        
        get filteredSoal() {
            return this.soalList.filter(s => {
                let matchesSearch = true;
                if (this.searchQuery) {
                    let q = this.searchQuery.toLowerCase();
                    let pert = this.stripHtml(s.pertanyaan).toLowerCase();
                    matchesSearch = pert.includes(q);
                }
                
                let matchesTipe = true;
                if (this.filterTipe) {
                    let tipe = s.tipe_soal || 'PG';
                    matchesTipe = tipe === this.filterTipe;
                }
                
                return matchesSearch && matchesTipe;
            });
        },
        
        get totalPages() {
            return Math.ceil(this.filteredSoal.length / this.perPage);
        },
        
        get paginatedSoal() {
            let start = (this.currentPage - 1) * this.perPage;
            let end = start + parseInt(this.perPage);
            return this.filteredSoal.slice(start, end);
        },
        
        get isAllSelected() {
            if (this.paginatedSoal.length === 0) return false;
            return this.paginatedSoal.every(s => this.selectedIds.includes(s.id_soal));
        },
        
        toggleSelectAll() {
            let allSelected = this.isAllSelected;
            this.paginatedSoal.forEach(s => {
                if (allSelected) {
                    this.selectedIds = this.selectedIds.filter(id => id !== s.id_soal);
                } else {
                    if (!this.selectedIds.includes(s.id_soal)) {
                        this.selectedIds.push(s.id_soal);
                    }
                }
            });
        }
    }
}
</script>
