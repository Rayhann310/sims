<?php
// Build menu groups
$groups = [];
foreach($data['menu_list'] as $key => $menu) {
    $groups[$menu['group']][$key] = $menu;
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Atur menu sidebar yang dapat diakses oleh masing-masing jabatan guru. Perubahan tersimpan secara otomatis.</p>
        </div>
        <form action="<?= BASEURL; ?>/hakAkses/reset" method="POST" onsubmit="return confirm('PERINGATAN: Anda yakin ingin me-reset (menghapus) SEMUA hak akses? Semua pengguna tidak akan memiliki akses menu khusus sampai Anda mengaturnya kembali.');">
            <button type="submit" class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-sm font-semibold rounded-lg transition-colors border border-rose-200 inline-flex items-center shadow-sm">
                <i class="fas fa-trash-restore mr-2"></i> Reset Semua Hak Akses
            </button>
        </form>
    </div>

    <!-- Flash -->
    <div class="mb-6"><?php Flasher::flash(); ?></div>

    <?php if(empty($data['jabatans'])): ?>
    <div class="bg-white rounded-xl border border-slate-200 p-10 text-center text-slate-500">
        <i class="fas fa-shield-alt text-4xl text-slate-300 mb-4 block"></i>
        Belum ada jabatan guru. Silakan buat jabatan terlebih dahulu di menu <a href="<?= BASEURL; ?>/jabatan" class="text-blue-600 underline">Master Jabatan</a>.
    </div>
    <?php else: ?>

    <div class="space-y-6">
        <?php foreach($data['jabatans'] as $jabatan): 
            $jabatan_id = $jabatan['id'];
            $lookup = $data['lookup'][$jabatan_id] ?? [];
        ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden"
             x-data="hakAksesJabatan(<?= $jabatan_id ?>)">
            
            <!-- Header Jabatan -->
            <div class="px-6 py-4 bg-gradient-to-r from-emerald-700 to-emerald-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-id-badge text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg"><?= htmlspecialchars($jabatan['nama_jabatan']); ?></h2>
                        <p class="text-emerald-100/80 text-xs"><?= htmlspecialchars($jabatan['deskripsi'] ?: 'Tidak ada deskripsi'); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-emerald-100 text-sm">
                    <i class="fas fa-toggle-on text-xs"></i>
                    <span x-text="activeCount + ' menu aktif'"></span>
                </div>
            </div>

            <!-- Menu Groups -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach($groups as $groupName => $menus): ?>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-5 h-px bg-slate-300 inline-block"></span>
                        <?= htmlspecialchars($groupName) ?>
                    </h3>
                    <div class="space-y-2">
                        <?php foreach($menus as $menuKey => $menu): 
                            $isActive = !empty($lookup[$menuKey]) ? 1 : 0;
                        ?>
                        <div class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group"
                             x-data="{ enabled: <?= $isActive ?> }">
                            <div class="flex items-center gap-2 min-w-0 flex-1 mr-3">
                                <span class="text-sm font-medium text-slate-700 truncate">
                                    <?= htmlspecialchars($menu['label']) ?>
                                </span>
                                <span class="text-[10px] text-slate-400 truncate hidden group-hover:inline">
                                    <?= htmlspecialchars($menu['url']) ?>
                                </span>
                            </div>

                            <!-- Toggle Switch -->
                            <button type="button"
                                @click="enabled = !enabled; toggleMenu('<?= $menuKey ?>', enabled)"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="enabled ? 'bg-emerald-600' : 'bg-slate-300'"
                                :title="enabled ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan'">
                                <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                      :class="enabled ? 'translate-x-5' : 'translate-x-0'">
                                </span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Toast Notification -->
<div x-data="toastNotif()" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     @toggle-saved.window="showToast($event.detail.success)"
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium"
     :class="success ? 'bg-emerald-600' : 'bg-red-500'"
     style="display:none;">
    <i :class="success ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
    <span x-text="success ? 'Hak akses berhasil diperbarui' : 'Gagal menyimpan, coba lagi'"></span>
</div>

<script>
const HAKAKSES_BASEURL = '<?= BASEURL ?>';

function hakAksesJabatan(jabatanId) {
    return {
        jabatanId: jabatanId,
        get activeCount() {
            // Count enabled toggles within this element
            return this.$el.querySelectorAll('button[\\:class].bg-emerald-600').length;
        },
        toggleMenu(menuKey, isActive) {
            const formData = new FormData();
            formData.append('jabatan_id', this.jabatanId);
            formData.append('menu_key', menuKey);
            formData.append('is_active', isActive ? 1 : 0);

            fetch(HAKAKSES_BASEURL + '/hakAkses/apiToggle', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                window.dispatchEvent(new CustomEvent('toggle-saved', { detail: { success: data.status } }));
            })
            .catch(() => {
                window.dispatchEvent(new CustomEvent('toggle-saved', { detail: { success: false } }));
            });
        }
    }
}

function toastNotif() {
    return {
        show: false,
        success: true,
        timer: null,
        showToast(success) {
            this.success = success;
            this.show = true;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => { this.show = false; }, 2500);
        }
    }
}
</script>
