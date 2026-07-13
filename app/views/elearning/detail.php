<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="elearningDetail()">
    <!-- Header/Breadcrumb -->
    <div class="mb-6">
        <a href="<?= BASEURL; ?>/elearning" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Kelas
        </a>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan akses materi, tugas, diskusi, serta absensi kelas ini.</p>
            </div>
            
            <?php if($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'guru'): ?>
            <div class="flex gap-2">
                <button x-show="activeTab === 'materi'" @click="modalMateri = true" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Materi
                </button>
                <button x-show="activeTab === 'tugas'" @click="modalTugas = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm" style="display: none;">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Tugas
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="flex border-b border-slate-200 overflow-x-auto">
            <button @click="changeTab('materi')" :class="{'border-blue-600 text-blue-600 bg-blue-50': activeTab === 'materi', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'materi'}" class="flex-1 min-w-[120px] py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors whitespace-nowrap">
                Materi Pelajaran
            </button>
            <button @click="changeTab('tugas')" :class="{'border-blue-600 text-blue-600 bg-blue-50': activeTab === 'tugas', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'tugas'}" class="flex-1 min-w-[120px] py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors whitespace-nowrap">
                Tugas Terstruktur
            </button>
            <button @click="changeTab('diskusi')" :class="{'border-blue-600 text-blue-600 bg-blue-50': activeTab === 'diskusi', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'diskusi'}" class="flex-1 min-w-[120px] py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors whitespace-nowrap">
                Diskusi Kelas
            </button>
            <?php if($_SESSION['user']['role'] == 'guru' || $_SESSION['user']['role'] == 'admin'): ?>
            <button @click="changeTab('absensi')" :class="{'border-blue-600 text-blue-600 bg-blue-50': activeTab === 'absensi', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'absensi'}" class="flex-1 min-w-[120px] py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors whitespace-nowrap">
                Absensi
            </button>
            <?php endif; ?>
        </div>

        <!-- Materi Content -->
        <div x-show="activeTab === 'materi'" class="p-6">
            <?php if(empty($data['materi'])): ?>
                <div class="text-center py-10">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-slate-500 font-medium">Belum ada materi yang diunggah.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach($data['materi'] as $m): ?>
                    <div class="border border-slate-200 rounded-lg p-5 hover:bg-slate-50 transition-colors flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-slate-800"><?= $m['judul']; ?></h4>
                            <p class="text-sm text-slate-500 mt-1 mb-2"><?= date('d M Y, H:i', strtotime($m['created_at'])); ?></p>
                            <p class="text-slate-600 text-sm"><?= nl2br($m['deskripsi']); ?></p>
                            
                            <?php if($m['file_path']): ?>
                            <div class="mt-4">
                                <a href="<?= BASEURL; ?>/<?= $m['file_path']; ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh Lampiran
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tugas Content -->
        <div x-show="activeTab === 'tugas'" class="p-6" style="display: none;">
            <?php if(empty($data['tugas'])): ?>
                <div class="text-center py-10">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-slate-500 font-medium">Belum ada tugas yang diberikan.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach($data['tugas'] as $t): ?>
                    <div class="border border-slate-200 rounded-lg p-5 hover:bg-slate-50 transition-colors flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                            <i class="fas fa-tasks text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="text-lg font-bold text-slate-800"><?= $t['judul']; ?></h4>
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full border border-red-200">
                                    Tenggat: <?= date('d M Y, H:i', strtotime($t['tenggat_waktu'])); ?>
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 mt-1 mb-2">Dibuat: <?= date('d M Y, H:i', strtotime($t['created_at'])); ?></p>
                            <p class="text-slate-600 text-sm"><?= nl2br($t['deskripsi']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Diskusi Content -->
        <div x-show="activeTab === 'diskusi'" class="p-0 flex flex-col h-[600px]" style="display: none;">
            <div class="flex-1 overflow-y-auto p-6 bg-slate-50 space-y-4">
                <?php if(empty($data['diskusi'])): ?>
                    <div class="text-center py-10">
                        <i class="far fa-comments text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500 font-medium">Belum ada diskusi di kelas ini. Jadilah yang pertama menyapa!</p>
                    </div>
                <?php else: ?>
                    <?php foreach($data['diskusi'] as $d): ?>
                        <div class="flex <?= $d['user_id'] == $_SESSION['user']['id'] ? 'justify-end' : 'justify-start' ?>">
                            <div class="flex max-w-[80%] <?= $d['user_id'] == $_SESSION['user']['id'] ? 'flex-row-reverse' : 'flex-row' ?> gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-300 flex items-center justify-center shrink-0 overflow-hidden text-slate-600 font-bold uppercase">
                                    <?php if($d['foto']): ?>
                                        <img src="<?= BASEURL; ?>/<?= $d['foto'] ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?= substr($d['nama_lengkap'], 0, 1) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="flex flex-col <?= $d['user_id'] == $_SESSION['user']['id'] ? 'items-end' : 'items-start' ?>">
                                    <span class="text-xs text-slate-500 mb-1 font-medium">
                                        <?= $d['nama_lengkap'] ?> 
                                        <?php if($d['role'] == 'guru'): ?>
                                            <span class="text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded ml-1">Guru</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="<?= $d['user_id'] == $_SESSION['user']['id'] ? 'bg-blue-600 text-white rounded-l-2xl rounded-br-2xl' : 'bg-white border border-slate-200 text-slate-800 rounded-r-2xl rounded-bl-2xl' ?> p-4 shadow-sm">
                                        <p class="text-sm whitespace-pre-wrap"><?= htmlspecialchars($d['pesan']) ?></p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 mt-1"><?= date('d M Y H:i', strtotime($d['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="border-t border-slate-200 p-4 bg-white">
                <form action="<?= BASEURL; ?>/elearning/tambahDiskusi" method="post" class="flex gap-2">
                    <input type="hidden" name="jadwal_id" value="<?= $data['jadwal_id']; ?>">
                    <input type="text" name="pesan" required placeholder="Tulis pesan diskusi di sini..." class="flex-1 px-4 py-2 border border-slate-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm">
                    <button type="submit" class="w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-colors shrink-0 shadow-sm">
                        <i class="fas fa-paper-plane text-sm -ml-0.5 mt-0.5"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Absensi Content -->
        <?php if($_SESSION['user']['role'] == 'guru' || $_SESSION['user']['role'] == 'admin'): ?>
        <div x-show="activeTab === 'absensi'" class="p-6" style="display: none;">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Kehadiran Siswa</h3>
                    <p class="text-sm text-slate-500">Pilih tanggal pertemuan untuk mengisi atau melihat absensi.</p>
                </div>
                <div class="flex gap-2 items-center">
                    <label class="text-sm font-medium text-slate-700">Tanggal:</label>
                    <input type="date" x-model="absensiDate" @change="fetchAbsensi()" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <template x-if="!absensiDate">
                <div class="text-center py-10 border-2 border-dashed border-slate-200 rounded-xl">
                    <i class="far fa-calendar-alt text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 font-medium">Silakan pilih tanggal pertemuan terlebih dahulu.</p>
                </div>
            </template>

            <template x-if="absensiDate">
                <div>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl mb-4">
                        <table class="min-w-full divide-y divide-slate-200 no-datatable">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">NIS</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                <template x-for="siswa in daftarAbsensi" :key="siswa.siswa_id">
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-500" x-text="siswa.nisn"></td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-slate-900" x-text="siswa.nama_lengkap"></td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex justify-center gap-3">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" :name="'status['+siswa.siswa_id+']'" value="Hadir" x-model="siswa.status_kehadiran" class="w-4 h-4 text-green-600 border-slate-300 focus:ring-green-500">
                                                    <span class="ml-1 text-sm text-slate-700">Hadir</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" :name="'status['+siswa.siswa_id+']'" value="Izin" x-model="siswa.status_kehadiran" class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                                    <span class="ml-1 text-sm text-slate-700">Izin</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" :name="'status['+siswa.siswa_id+']'" value="Sakit" x-model="siswa.status_kehadiran" class="w-4 h-4 text-orange-500 border-slate-300 focus:ring-orange-500">
                                                    <span class="ml-1 text-sm text-slate-700">Sakit</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" :name="'status['+siswa.siswa_id+']'" value="Alpa" x-model="siswa.status_kehadiran" class="w-4 h-4 text-red-600 border-slate-300 focus:ring-red-500">
                                                    <span class="ml-1 text-sm text-slate-700">Alpa</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="daftarAbsensi.length === 0">
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-slate-500">Tidak ada data siswa ditemukan di rombel ini.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end">
                        <button @click="simpanAbsensi()" :disabled="isSaving" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <span x-show="!isSaving"><i class="fas fa-save mr-2"></i> Simpan Absensi</span>
                            <span x-show="isSaving"><i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal Tambah Materi -->
    <div x-show="modalMateri" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalMateri" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalMateri = false"></div>

            <div x-show="modalMateri" x-transition class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Upload Materi Baru</h3>
                    <button @click="modalMateri = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/elearning/tambahMateri" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="jadwal_id" value="<?= $data['jadwal_id']; ?>">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Materi</label>
                            <input type="text" name="judul" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi / Penjelasan Singkat</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Upload File (PDF/Doc/PPT) *Opsional</label>
                            <input type="file" name="file_materi" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalMateri = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Simpan Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tugas -->
    <div x-show="modalTugas" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalTugas" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalTugas = false"></div>

            <div x-show="modalTugas" x-transition class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Buat Tugas Baru</h3>
                    <button @click="modalTugas = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/elearning/tambahTugas" method="post">
                    <input type="hidden" name="jadwal_id" value="<?= $data['jadwal_id']; ?>">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Tugas</label>
                            <input type="text" name="judul" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi / Soal Tugas</label>
                            <textarea name="deskripsi" rows="3" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tenggat Waktu</label>
                            <input type="datetime-local" name="tenggat_waktu" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalTugas = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Simpan Tugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('elearningDetail', () => ({
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'materi',
        modalMateri: false,
        modalTugas: false,
        absensiDate: '<?= date('Y-m-d') ?>',
        daftarAbsensi: [],
        isSaving: false,
        jadwalId: <?= $data['jadwal_id'] ?>,

        init() {
            if(this.activeTab === 'absensi') {
                this.fetchAbsensi();
            }
            // Auto scroll to bottom in chat if active tab is diskusi
            if(this.activeTab === 'diskusi') {
                this.$nextTick(() => {
                    const chatBox = document.querySelector('.overflow-y-auto');
                    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
                });
            }
        },

        changeTab(tab) {
            this.activeTab = tab;
            // Update URL without reloading
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);

            if(tab === 'absensi' && this.daftarAbsensi.length === 0) {
                this.fetchAbsensi();
            }
            if(tab === 'diskusi') {
                this.$nextTick(() => {
                    const chatBox = document.querySelector('.overflow-y-auto');
                    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
                });
            }
        },

        async fetchAbsensi() {
            if(!this.absensiDate) return;
            try {
                const res = await fetch(`<?= BASEURL ?>/elearning/getAbsensiAjax/${this.jadwalId}/${this.absensiDate}`);
                const data = await res.json();
                
                if (data.error) {
                    console.error("Error from server:", data.error);
                    alert("Terjadi kesalahan sistem: " + data.error);
                    return;
                }
                
                // Initialize default to Alpa if not set
                this.daftarAbsensi = data.map(siswa => ({
                    ...siswa,
                    status_kehadiran: siswa.status_kehadiran || 'Alpa'
                }));
            } catch (error) {
                console.error("Error fetching absensi:", error);
                alert("Terjadi kesalahan mengambil data absensi");
            }
        },

        async simpanAbsensi() {
            if(!this.absensiDate || this.daftarAbsensi.length === 0) return;
            
            this.isSaving = true;
            
            const formData = new FormData();
            formData.append('jadwal_id', this.jadwalId);
            formData.append('tanggal', this.absensiDate);
            
            this.daftarAbsensi.forEach(siswa => {
                formData.append(`absensi[${siswa.siswa_id}]`, siswa.status_kehadiran);
            });

            try {
                const res = await fetch(`<?= BASEURL ?>/elearning/simpanAbsensi`, {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();
                
                if(result.status === 'success') {
                    alert('Absensi berhasil disimpan!');
                } else {
                    alert('Gagal menyimpan absensi');
                }
            } catch (error) {
                console.error("Error saving absensi:", error);
                alert("Terjadi kesalahan saat menyimpan absensi");
            } finally {
                this.isSaving = false;
            }
        }
    }));
});
</script>
