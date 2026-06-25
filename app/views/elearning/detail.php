<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'materi', modalMateri: false, modalTugas: false }">
    <!-- Header/Breadcrumb -->
    <div class="mb-6">
        <a href="<?= BASEURL; ?>/elearning" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Kelas
        </a>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan akses materi serta tugas untuk kelas ini.</p>
            </div>
            
            <?php if($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'guru'): ?>
            <div class="flex gap-2">
                <button @click="modalMateri = true" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Materi
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
        <div class="flex border-b border-slate-200">
            <button @click="activeTab = 'materi'" :class="{'border-blue-600 text-blue-600 bg-blue-50': activeTab === 'materi', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'materi'}" class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                Materi Pelajaran
            </button>
            <button @click="activeTab = 'tugas'" :class="{'border-blue-600 text-blue-600 bg-blue-50': activeTab === 'tugas', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'tugas'}" class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                Tugas Terstruktur
            </button>
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
                    <!-- Task Implementation akan ditambahkan di fase selanjutnya -->
                    <div class="text-center py-10 text-slate-500">Daftar Tugas (Belum ada data UI)</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Tambah Materi -->
    <div x-show="modalMateri" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalMateri" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalMateri = false"></div>

            <div x-show="modalMateri" x-transition class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Upload Materi Baru</h3>
                    <button @click="modalMateri = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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
</div>
