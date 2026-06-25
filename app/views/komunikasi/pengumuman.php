<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ modalTambah: false }">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Informasi penting dan pengumuman terbaru sekolah.</p>
        </div>
        
        <?php if($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'guru'): ?>
        <button @click="modalTambah = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Pengumuman
        </button>
        <?php endif; ?>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="space-y-6">
        <?php if(empty($data['pengumuman'])): ?>
            <div class="py-12 text-center text-slate-500 bg-white rounded-2xl shadow-sm border border-slate-200">
                <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <p class="text-lg font-medium text-slate-900">Belum Ada Pengumuman</p>
                <p class="mt-1">Papan pengumuman masih kosong saat ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($data['pengumuman'] as $p): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg mr-3">
                            <?= substr($p['nama_lengkap'], 0, 1); ?>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900"><?= $p['nama_lengkap']; ?></h3>
                            <div class="flex items-center text-xs text-slate-500">
                                <span class="uppercase tracking-wider"><?= $p['role']; ?></span>
                                <span class="mx-2">&bull;</span>
                                <span><?= date('d M Y, H:i', strtotime($p['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-xl font-bold text-slate-900 mb-3"><?= $p['judul']; ?></h2>
                <div class="prose prose-sm prose-slate max-w-none text-slate-700">
                    <p><?= nl2br($p['isi']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Modal Tambah -->
    <?php if($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'guru'): ?>
    <div x-show="modalTambah" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalTambah" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalTambah = false"></div>
            <div x-show="modalTambah" x-transition class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Buat Pengumuman Baru</h3>
                    <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/komunikasi/tambahPengumuman" method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Pengumuman</label>
                            <input type="text" name="judul" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Isi Pengumuman</label>
                            <textarea name="isi" rows="6" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalTambah = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Posting Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
