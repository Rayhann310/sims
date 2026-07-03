<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Buat jadwal ujian baru dan tentukan pengawas ruangannya.</p>
        </div>
        <a href="<?= BASEURL; ?>/JadwalUjian" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h3 class="text-lg font-bold text-slate-900">Form Input Jadwal & Pengawas</h3>
        </div>
        
        <form action="<?= BASEURL; ?>/JadwalUjian/simpan" method="POST" class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Ujian</label>
                <input type="text" name="nama_ujian" placeholder="Contoh: Ujian Tengah Semester Ganjil - Matematika" required 
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" required 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" required 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                    <input type="number" name="durasi_menit" placeholder="Contoh: 120" required 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select name="status" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="Draft">Draft (Belum Aktif)</option>
                        <option value="Aktif">Aktif (Tampil di siswa)</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Guru Pengawas Ruangan</label>
                <select name="id_guru_pengawas" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white select2">
                    <option value="">-- Pilih Guru Pengawas --</option>
                    <?php foreach($data['guru'] as $g) : ?>
                        <option value="<?= $g['id']; ?>"><?= $g['nama_lengkap']; ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-2 text-xs text-slate-500"><i class="fas fa-info-circle mr-1"></i>Hanya guru yang dipilih ini yang memiliki Hak Akses untuk melihat Token dan melakukan Unlock siswa di ruangan ini.</p>
            </div>
            
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="<?= BASEURL; ?>/JadwalUjian" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
