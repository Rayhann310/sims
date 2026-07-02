<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
            Pendaftaran Peserta Didik Baru
        </h2>
        <?php if(!empty($data['gelombang_aktif'])): ?>
        <p class="mt-2 text-center text-sm text-slate-600">
            Gelombang: <span class="font-bold text-emerald-600"><?= htmlspecialchars($data['gelombang_aktif']['nama_gelombang']); ?></span>
        </p>
        <?php endif; ?>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-slate-200">
            
            <?php Flasher::flash(); ?>

            <?php if(!empty($data['gelombang_aktif'])): ?>
            <form class="space-y-6" action="<?= BASEURL; ?>/spmb/daftar" method="POST">
                <div>
                    <label for="nisn" class="block text-sm font-medium text-slate-700">NISN (Nomor Induk Siswa Nasional)</label>
                    <div class="mt-1">
                        <input id="nisn" name="nisn" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <p class="text-xs text-slate-500 mt-1">NISN akan digunakan sebagai Username untuk login.</p>
                    </div>
                </div>

                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <div class="mt-1">
                        <input id="nama_lengkap" name="nama_lengkap" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="asal_sekolah" class="block text-sm font-medium text-slate-700">Asal Sekolah (SMP/MTs)</label>
                    <div class="mt-1">
                        <input id="asal_sekolah" name="asal_sekolah" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="no_hp" class="block text-sm font-medium text-slate-700">No. HP / WhatsApp (Aktif)</label>
                    <div class="mt-1">
                        <input id="no_hp" name="no_hp" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password Akun</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <p class="text-xs text-slate-500 mt-1">Buat password untuk login sistem pendaftaran.</p>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                        Daftar Sekarang
                    </button>
                </div>
            </form>
            <?php else: ?>
                <div class="text-center py-6">
                    <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900">Pendaftaran Ditutup</h3>
                    <p class="mt-2 text-sm text-slate-500">Silakan kembali lagi nanti saat gelombang pendaftaran dibuka.</p>
                    <div class="mt-6">
                        <a href="<?= BASEURL; ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-slate-500">
                            Sudah punya akun?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="<?= BASEURL; ?>/login" class="w-full flex justify-center py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                        Login di sini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
