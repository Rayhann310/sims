<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Edit informasi jadwal ujian CBT.</p>
        </div>
        <a href="<?= BASEURL; ?>/JadwalUjian" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h3 class="text-lg font-bold text-slate-900">Form Edit Jadwal</h3>
        </div>
        
        <form action="<?= BASEURL; ?>/JadwalUjian/update" method="POST" class="p-6 space-y-6">
            <input type="hidden" name="id_jadwal" value="<?= $data['jadwal']['id_jadwal']; ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Ujian</label>
                    <input type="text" name="nama_ujian" value="<?= htmlspecialchars($data['jadwal']['nama_ujian']); ?>" required 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                    <select name="id_mapel" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        <?php foreach($data['mapel'] as $m) : ?>
                            <option value="<?= $m['id']; ?>" <?= ($data['jadwal']['id_mapel'] == $m['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($m['nama_mapel']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" value="<?= date('Y-m-d\TH:i', strtotime($data['jadwal']['waktu_mulai'])); ?>" required 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" value="<?= date('Y-m-d\TH:i', strtotime($data['jadwal']['waktu_selesai'])); ?>" required 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                    <input type="number" name="durasi_menit" value="<?= $data['jadwal']['durasi_menit']; ?>" required 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select name="status" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="Draft" <?= ($data['jadwal']['status'] == 'Draft') ? 'selected' : ''; ?>>Draft (Belum Aktif)</option>
                        <option value="Aktif" <?= ($data['jadwal']['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif (Tampil di siswa)</option>
                        <option value="Selesai" <?= ($data['jadwal']['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Kelas / Rombel</label>
                    <select name="id_rombel" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">-- Semua / Kosong --</option>
                        <?php foreach($data['rombel'] as $r): ?>
                            <option value="<?= $r['id']; ?>" <?= ($data['jadwal']['id_rombel'] == $r['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($r['nama_rombel']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Edit spesifik rombel untuk jadwal ini.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Guru Pengawas Ruangan</label>
                    <select name="id_guru_pengawas" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white select2">
                        <option value="">-- Pilih Guru Pengawas --</option>
                        <?php foreach($data['guru'] as $g) : ?>
                            <option value="<?= $g['id']; ?>" <?= ($data['jadwal']['id_guru_pengawas'] == $g['id']) ? 'selected' : ''; ?>>
                                <?= $g['nama_lengkap']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="<?= BASEURL; ?>/JadwalUjian" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
