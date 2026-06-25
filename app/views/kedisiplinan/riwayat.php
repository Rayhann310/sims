<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4 mb-2">
        <a href="<?= BASEURL; ?>/kedisiplinan/rekap" class="text-slate-400 hover:text-slate-600 transition-colors bg-white p-2 rounded-lg shadow-sm border border-slate-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Riwayat Kedisiplinan Siswa</h2>
    </div>

    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="p-4 border-l-4 border-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-500 bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-50 flex items-center gap-3 rounded-r-lg">
            <p class="text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-800 text-sm font-medium">
                <?= $_SESSION['flash']['pesan'] ?> <strong><?= $_SESSION['flash']['aksi'] ?></strong>.
            </p>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-6 items-start">
        <!-- Profil Siswa -->
        <div class="w-full md:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-2xl border-2 border-slate-200">
                    <?= substr($data['siswa']['nama_lengkap'], 0, 1); ?>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800"><?= $data['siswa']['nama_lengkap']; ?></h3>
                    <p class="text-sm text-slate-500">NIS: <?= $data['siswa']['nisn']; ?></p>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-4">
                <button onclick="document.getElementById('modalCatat').classList.remove('hidden')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Catat Kedisiplinan
                </button>
            </div>
        </div>

        <!-- Timeline Riwayat -->
        <div class="w-full md:w-2/3 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Timeline Catatan</h3>
            
            <?php if(empty($data['riwayat'])): ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-slate-500 text-sm">Belum ada catatan kedisiplinan untuk siswa ini.</p>
                </div>
            <?php else: ?>
                <div class="relative border-l border-slate-200 ml-3 space-y-8">
                    <?php foreach($data['riwayat'] as $r): ?>
                    <div class="relative pl-6">
                        <!-- Indikator Jenis -->
                        <?php if($r['jenis'] == 'Pelanggaran'): ?>
                            <span class="absolute -left-2 top-1 w-4 h-4 rounded-full bg-red-500 ring-4 ring-white"></span>
                        <?php else: ?>
                            <span class="absolute -left-2 top-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-white"></span>
                        <?php endif; ?>
                        
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-slate-800"><?= $r['nama_kategori']; ?></h4>
                                    <p class="text-xs text-slate-500 mt-0.5"><?= date('d M Y', strtotime($r['tanggal'])); ?> • Dicatat oleh <?= $r['pencatat']; ?></p>
                                </div>
                                <?php if($r['jenis'] == 'Pelanggaran'): ?>
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg">+<?= $r['poin_dicatat']; ?> Poin</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg">-<?= $r['poin_dicatat']; ?> Poin</span>
                                <?php endif; ?>
                            </div>
                            <?php if($r['keterangan']): ?>
                                <p class="text-sm text-slate-600 mt-2 bg-white p-3 rounded-lg border border-slate-100"><?= nl2br($r['keterangan']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Catat Kedisiplinan -->
<div id="modalCatat" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalCatat').classList.add('hidden')"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-slate-100">
            <div class="bg-white px-6 pt-5 pb-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Catat Kedisiplinan Baru</h3>
                    <button onclick="document.getElementById('modalCatat').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="<?= BASEURL; ?>/kedisiplinan/tambahCatatan" method="POST" class="space-y-4">
                    <input type="hidden" name="siswa_id" value="<?= $data['siswa']['id']; ?>">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" required value="<?= date('Y-m-d'); ?>" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori (Pelanggaran / Penghargaan)</label>
                        <select name="kategori_id" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach($data['kategori'] as $k): ?>
                                <option value="<?= $k['id']; ?>">[<?= $k['jenis'] ?>] <?= $k['nama_kategori']; ?> (<?= $k['tingkatan']; ?> - Poin: <?= $k['poin']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Poin Kustom (Opsional)</label>
                        <input type="number" name="poin_kustom" placeholder="Kosongkan untuk menggunakan poin default kategori" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm">
                        <p class="text-xs text-slate-400 mt-1">Isi jika Anda ingin memberikan poin yang berbeda dari poin standar kategori tersebut.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan Detail</label>
                        <textarea name="keterangan" rows="3" placeholder="Contoh: Siswa terlambat 15 menit alasan macet" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 text-sm"></textarea>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modalCatat').classList.add('hidden')" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
