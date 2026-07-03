<div class="max-w-4xl mx-auto space-y-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Pengaturan Profil</h2>
        <p class="text-sm text-slate-500 mt-1">Perbarui data diri dan kata sandi akun Anda di sini.</p>
    </div>

    <div>
        <?php Flasher::flash(); ?>
    </div>

    <form action="<?= BASEURL; ?>/profil/update" method="post" class="space-y-6">
        
        <!-- Informasi Akun -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Informasi Akun</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($data['user']['username']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['user']['nama_lengkap']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ganti Kata Sandi (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah kata sandi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                    <p class="text-xs text-slate-400 mt-2">Disarankan menggunakan minimal 8 karakter dengan kombinasi huruf dan angka.</p>
                </div>
            </div>
        </div>

        <!-- Informasi Personal Siswa -->
        <?php if($data['user']['role'] == 'siswa' && $data['profil_detail']): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-id-card"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Data Personal Siswa</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="<?= htmlspecialchars($data['profil_detail']['tempat_lahir'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($data['profil_detail']['tanggal_lahir'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">No. HP Siswa</label>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['profil_detail']['no_hp'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"><?= htmlspecialchars($data['profil_detail']['alamat'] ?? ''); ?></textarea>
                </div>
                <div class="md:col-span-2 mt-2 pt-4 border-t border-slate-100">
                    <h4 class="text-md font-bold text-slate-700 mb-4">Informasi Orang Tua / Wali</h4>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Wali</label>
                    <input type="text" name="nama_wali" value="<?= htmlspecialchars($data['profil_detail']['nama_wali'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">No. HP Wali (WhatsApp)</label>
                    <input type="text" name="no_hp_wali" value="<?= htmlspecialchars($data['profil_detail']['no_hp_wali'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Informasi Personal Guru -->
        <?php if(($data['user']['role'] == 'guru' || $data['user']['role'] == 'staf') && $data['profil_detail']): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Data Personal Guru</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">No. HP (WhatsApp)</label>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['profil_detail']['no_hp'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($data['profil_detail']['tanggal_lahir'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"><?= htmlspecialchars($data['profil_detail']['alamat'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Submit Button -->
        <div class="flex justify-end gap-3">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
