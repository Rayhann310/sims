<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Minimalist -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola konfigurasi dan perbaikan sistem aplikasi SIAKAD.</p>
    </div>

    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="p-4 border-l-4 border-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-500 bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-50 flex items-center gap-3 rounded-r-lg">
            <p class="text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-800 text-sm font-medium">
                Sistem: <strong><?= $_SESSION['flash']['pesan'] ?></strong> <?= $_SESSION['flash']['aksi'] ?>.
            </p>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Self Healing Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Perbaikan Database (Self-Healing)
            </h3>
            <p class="text-sm text-slate-500 mt-1 max-w-xl">Jika ada tabel yang hilang, rusak, atau skema baru yang belum terpasang, jalankan fitur ini untuk memulihkan keseluruhan struktur database secara otomatis ke kondisi optimal.</p>
        </div>
        <form action="<?= BASEURL; ?>/pengaturan/repair" method="POST" class="shrink-0" onsubmit="return confirm('Sistem akan mengecek dan membuat ulang struktur tabel yang kurang. Lanjutkan?');">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Jalankan Perbaikan
            </button>
        </form>
    </div>

    <!-- UI Settings Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm" x-data="logoUpload()">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Pengaturan Antarmuka Aplikasi</h3>
        
        <form action="<?= BASEURL; ?>/pengaturan/update" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom 1 -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aplikasi</label>
                        <input type="text" name="nama_aplikasi" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2" value="<?= htmlspecialchars($data['pengaturan']['nama_aplikasi'] ?? 'Narasui') ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teks Logo Sidebar</label>
                        <input type="text" name="logo_teks" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2" maxlength="2" value="<?= htmlspecialchars($data['pengaturan']['logo_teks'] ?? 'N') ?>" required>
                        <p class="text-xs text-slate-500 mt-1">Muncul jika gambar logo kosong.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Token Fonnte (WA API)</label>
                        <input type="text" name="fonnte_token" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2" value="<?= htmlspecialchars($data['pengaturan']['fonnte_token'] ?? '') ?>">
                        <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak menggunakan WA bot.</p>
                    </div>
                </div>
                
                <!-- Kolom 2 -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Logo Sekolah (Gambar)</label>
                        <div class="flex flex-col gap-3">
                            <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center border border-slate-200 overflow-hidden shrink-0">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="w-full h-full object-contain">
                                </template>
                                <template x-if="!logoPreview">
                                    <i class="fas fa-image text-slate-400"></i>
                                </template>
                            </div>
                            <input type="file" @change="handleLogoChange" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <input type="hidden" name="logo_sekolah" :value="logoBase64">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Disarankan rasio 1:1, format PNG/JPG transparan.</p>
                    </div>
                </div>

                <!-- Kolom 3 -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teks Footer</label>
                        <textarea name="teks_footer" rows="2" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2" required><?= htmlspecialchars($data['pengaturan']['teks_footer'] ?? '') ?></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gambar Banner Landing Page (PPDB)</label>
                        <div class="flex flex-col gap-3">
                            <div class="w-full h-24 bg-slate-100 rounded-lg flex items-center justify-center border border-slate-200 overflow-hidden">
                                <template x-if="heroPreview">
                                    <img :src="heroPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!heroPreview">
                                    <i class="fas fa-image text-slate-400"></i>
                                </template>
                            </div>
                            <input type="file" @change="handleHeroChange" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <input type="hidden" name="gambar_hero_spmb" :value="heroBase64">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Disarankan rasio Landscape (16:9), format PNG/JPG.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Brosur PPDB (PDF/Gambar)</label>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2" x-show="brosurBase64">
                                <i class="fas fa-check-circle text-emerald-500"></i>
                                <span class="text-sm text-emerald-700 font-medium">File Brosur Tersimpan</span>
                            </div>
                            <input type="file" @change="handleBrosurChange" accept=".pdf,image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <input type="hidden" name="brosur_spmb" :value="brosurBase64">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Maksimal ukuran file 2MB.</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 pt-4 border-t border-slate-100">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('logoUpload', () => ({
        logoPreview: '<?= !empty($data['pengaturan']['logo_sekolah']) ? $data['pengaturan']['logo_sekolah'] : '' ?>',
        logoBase64: '<?= !empty($data['pengaturan']['logo_sekolah']) ? $data['pengaturan']['logo_sekolah'] : '' ?>',
        
        heroPreview: '<?= !empty($data['pengaturan']['gambar_hero_spmb']) ? $data['pengaturan']['gambar_hero_spmb'] : '' ?>',
        heroBase64: '<?= !empty($data['pengaturan']['gambar_hero_spmb']) ? $data['pengaturan']['gambar_hero_spmb'] : '' ?>',
        
        brosurBase64: '<?= !empty($data['pengaturan']['brosur_spmb']) ? $data['pengaturan']['brosur_spmb'] : '' ?>',
        
        handleLogoChange(event) {
            this.processImage(event, 200, (base64) => {
                this.logoPreview = base64;
                this.logoBase64 = base64;
            });
        },
        
        handleHeroChange(event) {
            this.processImage(event, 800, (base64) => {
                this.heroPreview = base64;
                this.heroBase64 = base64;
            });
        },
        
        handleBrosurChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran brosur maksimal 2MB.');
                event.target.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.brosurBase64 = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        
        processImage(event, maxDim, callback) {
            const file = event.target.files[0];
            if (!file) return;
            
            if (!file.type.match('image.*')) {
                alert('Tolong unggah file gambar (JPG/PNG).');
                event.target.value = '';
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 2MB.');
                event.target.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    
                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height *= maxDim / width));
                            width = maxDim;
                        } else {
                            width = Math.round((width *= maxDim / height));
                            height = maxDim;
                        }
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    callback(canvas.toDataURL('image/jpeg', 0.8));
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }));
});
</script>
