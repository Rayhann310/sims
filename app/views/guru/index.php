<div x-data="{ 
    showModal: false, 
    importModalOpen: false, 
    editModalOpen: false, 
    deleteModalOpen: false, 
    detailModalOpen: false, 
    waliKelasModalOpen: false,
    ultahModalOpen: false,
    deleteUrl: '', 
    currentGuru: {} 
}" 
     @open-edit-modal.window="editModalOpen = true"
     @open-detail-modal.window="detailModalOpen = true; currentGuru = $event.detail;"
     @open-delete-modal.window="deleteModalOpen = true; deleteUrl = $event.detail.url"
     @open-walikelas-modal.window="waliKelasModalOpen = true"
     @open-ultah-modal.window="ultahModalOpen = true"
     class="space-y-6">
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Stat Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400">Total Guru</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['total']); ?></p>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-semibold border-t border-slate-100 pt-2">Status: Aktif</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-male text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400">Laki-Laki</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['laki']); ?></p>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-pink-50 text-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-female text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400">Perempuan</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['perempuan']); ?></p>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div @click="openWaliKelasModal()" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group cursor-pointer">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                <p class="text-sm font-medium text-slate-400 mb-1">Wali Kelas</p>
                <p class="text-3xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['wali_kelas']); ?></p>
            </div>
        </div>
        </div>

        <!-- Stat Card 5 -->
        <div @click="openUltahModal()" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group cursor-pointer">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-pink-50 text-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-birthday-cake text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ultah Hari Ini</h3>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-400 mb-1">Guru</p>
                <p class="text-3xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['ultah_hari_ini']); ?></p>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header Tabel -->
    <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Data Guru</h2>
            <p class="text-sm text-slate-500">Kelola informasi pendidik/guru sekolah.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Dropdown Import -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-100 z-10" style="display: none;">
                    <a href="<?= BASEURL; ?>/guru/template" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 rounded-t-lg">1. Unduh Template</a>
                    <button @click="importModalOpen = true; open = false" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 rounded-b-lg">2. Unggah Template</button>
                </div>
            </div>

            <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm shrink-0">
                <i class="fas fa-plus"></i>
                Tambah Guru
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="px-6 py-4 bg-white border-b border-slate-200">
        <form action="<?= BASEURL; ?>/guru" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Gender</label>
                <select name="jk" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Gender</option>
                    <option value="L" <?= ($data['filters']['jk'] == 'L') ? 'selected' : ''; ?>>Laki-Laki</option>
                    <option value="P" <?= ($data['filters']['jk'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>

            <div class="w-full sm:w-auto flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <?php if(array_filter($data['filters'])): ?>
                <a href="<?= BASEURL; ?>/guru" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i> Reset
                </a>
                <?php endif; ?>
                <button type="button" onclick="submitHapusMassal()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 ml-auto shadow-sm">
                    <i class="fas fa-trash-alt"></i> Hapus Terpilih
                </button>
            </div>
        </form>
    </div>

    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="px-6 py-4 border-b border-<?= $_SESSION['flash']['tipe'] == 'success' ? 'emerald' : 'red' ?>-200 bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'emerald' : 'red' ?>-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'emerald' : 'red' ?>-100 flex items-center justify-center text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'emerald' : 'red' ?>-600 shrink-0">
                <?php if($_SESSION['flash']['tipe'] == 'success'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <?php else: ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <?php endif; ?>
            </div>
            <p class="text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'emerald' : 'red' ?>-800 text-sm">
                Data Guru <strong><?= $_SESSION['flash']['pesan'] ?></strong> <?= $_SESSION['flash']['aksi'] ?>.
            </p>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <form id="formHapusMassal" action="<?= BASEURL; ?>/guru/hapus_massal" method="POST">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider border-b border-slate-200">
                    <th class="px-6 py-4 font-semibold w-12 text-center">
                        <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4" onclick="toggleSelectAll(this)">
                    </th>
                    <th class="px-6 py-4 font-semibold">NIP</th>
                    <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-4 font-semibold">L/P</th>
                    <th class="px-6 py-4 font-semibold">No. HP</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($data['guru'] as $g): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-center">
                        <input type="checkbox" name="guru_ids[]" value="<?= $g['id']; ?>" class="guru-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4">
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($g['nip']); ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if(!empty($g['foto'])): ?>
                                <img src="<?= $g['foto']; ?>" class="w-8 h-8 rounded-full object-cover shrink-0 cursor-pointer" onclick="openEditModalGuru(<?= $g['id']; ?>)" title="Klik untuk edit foto">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shrink-0 cursor-pointer" onclick="openEditModalGuru(<?= $g['id']; ?>)" title="Klik untuk edit foto">
                                    <?= substr($g['nama_lengkap'], 0, 1); ?>
                                </div>
                            <?php endif; ?>
                            <span class="font-medium text-slate-700"><?= htmlspecialchars($g['nama_lengkap']); ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= $g['jenis_kelamin'] == 'L' ? 'L' : 'P'; ?></td>
                    <td class="px-6 py-4 text-slate-600 font-medium"><?= htmlspecialchars($g['no_hp'] ?? '-'); ?></td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <button type="button" @click="openDetailModalGuru(<?= $g['id']; ?>)" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" @click="openEditModalGuru(<?= $g['id']; ?>)" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="<?= BASEURL; ?>/guru/resetSandi/<?= $g['id']; ?>" onclick="return confirm('Reset sandi guru ini menjadi 123456?')" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Reset Sandi">
                            <i class="fas fa-key"></i>
                        </a>
                        <button type="button" @click="$dispatch('open-delete-modal', { url: '<?= BASEURL; ?>/guru/hapus/<?= $g['id']; ?>' })" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(empty($data['guru'])): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Belum ada data guru. Klik tombol "Tambah Guru" untuk memulai.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </form>
    </div>
    </div> <!-- End Table Container -->

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Doughnut Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:col-span-2 lg:col-span-1 mx-auto w-full max-w-lg">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2 justify-center">
                <i class="fas fa-chart-pie text-emerald-500"></i>
                Distribusi Guru per Gender
            </h3>
            <div class="relative flex-grow flex items-center justify-center min-h-[300px]">
                <canvas id="guruDoughnutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-slate-900">Tambah Guru Baru</h3>
                            <p class="text-sm text-slate-500 mt-1">Lengkapi data diri guru. Akun login akan dibuatkan secara otomatis.</p>
                        </div>
                    </div>
                </div>

                <form action="<?= BASEURL; ?>/guru/tambah" method="POST">
                    <div class="bg-slate-50 px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Profil -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-slate-700 border-b pb-2">Biodata Guru</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">NIP/NUPTK</label>
                                <input type="text" name="nip" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                                <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Jabatan <span class="text-xs text-slate-400 font-normal">(bisa pilih lebih dari satu)</span></label>
                                <div class="grid grid-cols-1 gap-1.5 max-h-40 overflow-y-auto border border-slate-200 rounded-lg p-3 bg-white">
                                    <?php if(empty($data['jabatan_list'])): ?>
                                    <p class="text-xs text-slate-400 italic">Belum ada jabatan. <a href="<?= BASEURL ?>/jabatan" class="text-blue-500 underline">Tambah di sini</a>.</p>
                                    <?php else: ?>
                                    <?php foreach($data['jabatan_list'] as $jab): ?>
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 px-2 py-1 rounded">
                                        <input type="checkbox" name="jabatan_id[]" value="<?= $jab['id'] ?>" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-sm text-slate-700"><?= htmlspecialchars($jab['nama_jabatan']) ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Detail & Akun -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-slate-700 border-b pb-2">Data Tambahan & Akun</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">No. HP</label>
                                <input type="text" name="no_hp" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                                <textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Username Login</label>
                                    <input type="text" name="username" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                                    <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:w-auto transition-colors">Simpan Data</button>
                        <button type="button" @click="showModal = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div x-show="importModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="importModalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="importModalOpen = false"></div>

            <div x-show="importModalOpen" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Import Data Guru</h3>
                    <button @click="importModalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/guru/import" method="post" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-md">
                            <p class="text-sm text-blue-700">Pastikan Anda telah mengunduh template terbaru dan mengisinya dengan format yang benar. Tipe file harus .xls atau .xlsx.</p>
                        </div>
                        <div>
                            <label for="file_excel" class="block text-sm font-medium text-slate-700 mb-1">Pilih File Excel</label>
                            <input type="file" name="file_excel" id="file_excel" accept=".xls,.xlsx" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="importModalOpen = false" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="editModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold leading-6 text-slate-900">Edit Data Guru</h3>
                </div>

                <form action="<?= BASEURL; ?>/guru/ubah" method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <input type="hidden" name="foto" id="edit_foto_base64">
                    
                    <div class="bg-slate-50 px-6 py-6 space-y-4">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="relative w-16 h-16 rounded-full overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                <img id="edit_foto_preview" src="" class="w-full h-full object-cover hidden">
                                <div id="edit_foto_initials" class="w-full h-full flex items-center justify-center text-xl font-bold text-blue-600 bg-blue-100">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Foto Profil (Opsional)</label>
                                <input type="file" id="edit_foto_input" accept="image/*" class="text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-slate-400 mt-1">Otomatis dikompres</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">NIP/NUPTK</label>
                            <input type="text" name="nip" id="edit_nip" readonly class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-200 text-slate-500 cursor-not-allowed">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="edit_jenis_kelamin" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. HP</label>
                            <input type="text" name="no_hp" id="edit_no_hp" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                            <textarea name="alamat" id="edit_alamat" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Jabatan <span class="text-xs text-slate-400 font-normal">(bisa pilih lebih dari satu)</span></label>
                            <div id="edit_jabatan_checkboxes" class="grid grid-cols-1 gap-1.5 max-h-40 overflow-y-auto border border-slate-200 rounded-lg p-3 bg-white">
                                <?php if(empty($data['jabatan_list'])): ?>
                                <p class="text-xs text-slate-400 italic">Belum ada jabatan.</p>
                                <?php else: ?>
                                <?php foreach($data['jabatan_list'] as $jab): ?>
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 px-2 py-1 rounded">
                                    <input type="checkbox" name="jabatan_id[]" value="<?= $jab['id'] ?>" class="edit-jabatan-cb rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-700"><?= htmlspecialchars($jab['nama_jabatan']) ?></span>
                                </label>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 sm:w-auto transition-colors">Simpan Perubahan</button>
                        <button type="button" @click="editModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="deleteModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="deleteModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-slate-900">Hapus Data</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">Apakah Anda yakin ingin menghapus data guru ini beserta akun login yang terkait? Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <a :href="deleteUrl" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Hapus</a>
                    <button type="button" @click="deleteModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Data -->
    <div x-show="detailModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div x-show="detailModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="detailModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="detailModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold leading-6 text-slate-900 flex items-center gap-2">
                        <i class="fas fa-id-badge text-emerald-500"></i>
                        Profil Detail Guru
                    </h3>
                    <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                 <div class="bg-slate-50 px-6 py-6 max-h-[75vh] overflow-y-auto space-y-6">
                    <!-- Profile Header with Photo -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 pb-5 border-b border-slate-200">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-white shadow-lg overflow-hidden shrink-0 bg-emerald-100 flex items-center justify-center text-4xl font-bold text-emerald-400">
                            <template x-if="currentGuru.foto">
                                <img :src="currentGuru.foto" class="w-full h-full object-cover" alt="Foto Guru">
                            </template>
                            <template x-if="!currentGuru.foto">
                                <span x-text="currentGuru.nama_lengkap ? currentGuru.nama_lengkap.charAt(0).toUpperCase() : '?'"></span>
                            </template>
                        </div>
                        <div class="text-center sm:text-left flex-1">
                            <h4 class="text-xl font-bold text-slate-800 mb-1" x-text="currentGuru.nama_lengkap"></h4>
                            <p class="text-xs font-medium text-emerald-600 bg-emerald-50 inline-block px-3 py-1 rounded-full border border-emerald-100 mb-2" x-text="'NIP: ' + (currentGuru.nip || '-')"></p>
                            <!-- Jabatan Badges -->
                            <div class="flex flex-wrap gap-2 mt-2">
                                <template x-if="currentGuru.jabatan_list && currentGuru.jabatan_list.length > 0">
                                    <template x-for="jab in currentGuru.jabatan_list" :key="jab.jabatan_id">
                                        <span x-text="jab.nama_jabatan" class="inline-flex items-center gap-1 text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 px-3 py-1 rounded-full">
                                        </span>
                                    </template>
                                </template>
                                <template x-if="!currentGuru.jabatan_list || currentGuru.jabatan_list.length === 0">
                                    <span class="text-xs text-slate-400 italic">Belum ada jabatan</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-sm">
                            <p class="text-xs text-slate-400 mb-1">Jenis Kelamin</p>
                            <p class="font-semibold text-slate-700 text-sm" x-text="currentGuru.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan'"></p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-sm">
                            <p class="text-xs text-slate-400 mb-1">Tanggal Lahir</p>
                            <p class="font-semibold text-slate-700 text-sm" x-text="currentGuru.tanggal_lahir ? new Date(currentGuru.tanggal_lahir).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}) : '-'"></p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-sm">
                            <p class="text-xs text-slate-400 mb-1">No. HP</p>
                            <p class="font-semibold text-slate-700 text-sm" x-text="currentGuru.no_hp || '-'"></p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-sm">
                            <p class="text-xs text-slate-400 mb-1">Username</p>
                            <p class="font-semibold text-slate-700 text-sm font-mono" x-text="'@' + (currentGuru.username || '-')"></p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-sm col-span-2">
                            <p class="text-xs text-slate-400 mb-1">Alamat</p>
                            <p class="font-semibold text-slate-700 text-sm" x-text="currentGuru.alamat || '-'"></p>
                        </div>
                    </div>

                    <!-- Tabel Mata Pelajaran -->
                    <div>
                        <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-book text-blue-400"></i> Mata Pelajaran yang Diajarkan
                        </h5>
                        <template x-if="currentGuru.mapel_list && currentGuru.mapel_list.length > 0">
                            <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <table class="w-full text-xs">
                                    <thead class="bg-slate-100 text-slate-500 uppercase tracking-wider">
                                        <tr>
                                            <th class="text-left px-4 py-2.5">Mata Pelajaran</th>
                                            <th class="text-left px-4 py-2.5">Kode</th>
                                            <th class="text-left px-4 py-2.5">Rombel / Kelas</th>
                                            <th class="text-left px-4 py-2.5">Tahun Akademik</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        <template x-for="mp in currentGuru.mapel_list" :key="mp.id + '_' + mp.nama_rombel">
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-2.5 font-semibold text-slate-800" x-text="mp.nama_mapel"></td>
                                                <td class="px-4 py-2.5 text-slate-500 font-mono" x-text="mp.kode_mapel"></td>
                                                <td class="px-4 py-2.5 text-slate-600" x-text="mp.nama_rombel + ' (' + mp.nama_kelas + ')'"></td>
                                                <td class="px-4 py-2.5 text-slate-500" x-text="mp.nama_tahun + ' - ' + mp.semester"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                        <template x-if="!currentGuru.mapel_list || currentGuru.mapel_list.length === 0">
                            <div class="text-center py-6 text-slate-400 bg-white rounded-xl border border-slate-200">
                                <i class="fas fa-book-open text-3xl mb-2 block text-slate-300"></i>
                                Belum ada mata pelajaran terdaftar
                            </div>
                        </template>
                    </div>
                </div>
                <div class="bg-white px-4 py-3 flex justify-end border-t border-slate-200">
                    <button type="button" @click="detailModalOpen = false" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Wali Kelas -->
    <div x-show="waliKelasModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div x-show="waliKelasModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="waliKelasModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="waliKelasModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold leading-6 text-slate-900 flex items-center gap-2">
                        <i class="fas fa-chalkboard-teacher text-orange-500"></i>
                        Daftar Wali Kelas Aktif
                    </h3>
                    <button type="button" @click="waliKelasModalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="bg-slate-50 px-6 py-6 max-h-[60vh] overflow-y-auto">
                    <div id="waliKelasContainer" class="space-y-3">
                        <div class="flex justify-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i></div>
                    </div>
                </div>
                <div class="bg-white px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                    <button type="button" @click="waliKelasModalOpen = false" class="inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ulang Tahun -->
    <div x-show="ultahModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div x-show="ultahModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="ultahModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="ultahModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold leading-6 text-slate-900 flex items-center gap-2">
                        <i class="fas fa-birthday-cake text-pink-500"></i>
                        Guru yang Berulang Tahun Hari Ini
                    </h3>
                    <button type="button" @click="ultahModalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="bg-slate-50 px-6 py-6 max-h-[60vh] overflow-y-auto">
                    <div id="ultahContainer" class="space-y-3">
                        <div class="flex justify-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i></div>
                    </div>
                </div>
                <div class="bg-white px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                    <button type="button" @click="ultahModalOpen = false" class="inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModalGuru(id) {
    fetch('<?= BASEURL; ?>/guru/detail/' + id)
    .then(response => response.json())
    .then(data => {
        window.dispatchEvent(new CustomEvent('open-detail-modal', { detail: data }));
    })
    .catch(err => console.error(err));
}

function openEditModalGuru(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('<?= BASEURL; ?>/guru/getubah', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_user_id').value = data.user_id;
        document.getElementById('edit_nip').value = data.nip;
        document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
        document.getElementById('edit_jenis_kelamin').value = data.jenis_kelamin;
        document.getElementById('edit_tanggal_lahir').value = data.tanggal_lahir;
        document.getElementById('edit_no_hp').value = data.no_hp;
        document.getElementById('edit_alamat').value = data.alamat;
        
        // Set jabatan checkboxes (rangkap jabatan)
        const jabatanCbs = document.querySelectorAll('.edit-jabatan-cb');
        jabatanCbs.forEach(cb => { cb.checked = false; });
        if(data.jabatan_ids && Array.isArray(data.jabatan_ids)) {
            data.jabatan_ids.forEach(jid => {
                const cb = document.querySelector(`.edit-jabatan-cb[value="${jid}"]`);
                if(cb) cb.checked = true;
            });
        }

        const fotoPreview = document.getElementById('edit_foto_preview');
        const fotoInitials = document.getElementById('edit_foto_initials');
        document.getElementById('edit_foto_base64').value = '';
        document.getElementById('edit_foto_input').value = '';

        if(data.foto) {
            fotoPreview.src = data.foto;
            fotoPreview.classList.remove('hidden');
            fotoInitials.classList.add('hidden');
        } else {
            fotoInitials.innerHTML = data.nama_lengkap ? data.nama_lengkap.substring(0, 1).toUpperCase() : '';
            fotoPreview.classList.add('hidden');
            fotoInitials.classList.remove('hidden');
        }

        // Buka modal Alpine dari luar
        window.dispatchEvent(new CustomEvent('open-edit-modal'));
    });
}

function openWaliKelasModal() {
    window.dispatchEvent(new CustomEvent('open-walikelas-modal'));
    document.getElementById('waliKelasContainer').innerHTML = '<div class="flex justify-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i></div>';
    
    fetch('<?= BASEURL; ?>/guru/getwalikelas')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('waliKelasContainer');
            if(data.length === 0) {
                container.innerHTML = '<div class="text-center text-slate-500 py-4">Belum ada data wali kelas</div>';
                return;
            }
            
            let html = '';
            data.forEach(g => {
                let fotoHtml = g.foto ? `<img src="${g.foto}" class="w-10 h-10 rounded-full object-cover">` : `<div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">${g.nama_lengkap.substring(0,1).toUpperCase()}</div>`;
                html += `
                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        ${fotoHtml}
                        <div>
                            <p class="font-bold text-slate-800">${g.nama_lengkap}</p>
                            <p class="text-xs text-slate-500">NIP: ${g.nip}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                            Kelas ${g.kelas}
                        </span>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
        });
}

function openUltahModal() {
    window.dispatchEvent(new CustomEvent('open-ultah-modal'));
    document.getElementById('ultahContainer').innerHTML = '<div class="flex justify-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i></div>';
    
    fetch('<?= BASEURL; ?>/guru/getulangtahun')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('ultahContainer');
            if(data.length === 0) {
                container.innerHTML = '<div class="text-center text-slate-500 py-4">Belum ada guru yang berulang tahun hari ini</div>';
                return;
            }
            
            let html = '';
            data.forEach(g => {
                let fotoHtml = g.foto ? `<img src="${g.foto}" class="w-10 h-10 rounded-full object-cover">` : `<div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">${g.nama_lengkap.substring(0,1).toUpperCase()}</div>`;
                html += `
                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        ${fotoHtml}
                        <div>
                            <p class="font-bold text-slate-800">${g.nama_lengkap}</p>
                            <p class="text-xs text-slate-500">NIP: ${g.nip}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800 border border-pink-200">
                            <i class="fas fa-gift mr-1"></i> Hari Ini
                        </span>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
        });
}

// Self-healing: Image Compression
document.getElementById('edit_foto_input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            const canvas = document.createElement('canvas');
            const MAX_WIDTH = 250;
            const MAX_HEIGHT = 250;
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.7);
            
            // Set ke preview dan hidden input
            document.getElementById('edit_foto_preview').src = dataUrl;
            document.getElementById('edit_foto_preview').classList.remove('hidden');
            document.getElementById('edit_foto_initials').classList.add('hidden');
            document.getElementById('edit_foto_base64').value = dataUrl;
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});

// Chart.js Initialization
document.addEventListener('DOMContentLoaded', function() {
    const chartLabels = <?= $data['chart_labels']; ?>;
    const chartData = <?= $data['chart_data']; ?>;
    
    // Warna untuk chart
    const bgColors = [
        'rgba(59, 130, 246, 0.8)', // blue (Laki-laki)
        'rgba(236, 72, 153, 0.8)'  // pink (Perempuan)
    ];
    
    const borderColors = bgColors.map(color => color.replace('0.8', '1'));

    if(chartLabels.length > 0) {
        // Doughnut Chart
        const ctxDoughnut = document.getElementById('guruDoughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: "'Inter', sans-serif" },
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('guruDoughnutChart').parentElement.innerHTML = '<p class="text-slate-400 text-sm">Tidak ada data guru</p>';
    }
});

function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.guru-checkbox');
    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}

function submitHapusMassal() {
    const checkboxes = document.querySelectorAll('.guru-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih setidaknya satu data guru untuk dihapus.');
        return;
    }
    if (confirm('Apakah Anda yakin ingin menghapus ' + checkboxes.length + ' data guru yang dipilih secara permanen?')) {
        document.getElementById('formHapusMassal').submit();
    }
}
</script>
