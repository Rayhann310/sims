<div class="space-y-6" 
     x-data="{ showModal: false, importModalOpen: false, editModalOpen: false, deleteModalOpen: false, detailModalOpen: false, ultahModalOpen: false, deleteUrl: '', currentSiswa: {} }"
     @open-edit-modal.window="editModalOpen = true"
     @open-detail-modal.window="detailModalOpen = true; currentSiswa = $event.detail;"
     @open-ultah-modal.window="ultahModalOpen = true"
     @open-delete-modal.window="deleteModalOpen = true; deleteUrl = $event.detail.url">
    
    <!-- Stats Grid 5 Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Stat Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400">Total Siswa</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['total']); ?></p>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-semibold border-t border-slate-100 pt-2">Thn Akd: <?= $data['stats']['tahun_akademik']; ?></p>
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
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-semibold border-t border-slate-100 pt-2">Thn Akd: <?= $data['stats']['tahun_akademik']; ?></p>
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
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-semibold border-t border-slate-100 pt-2">Thn Akd: <?= $data['stats']['tahun_akademik']; ?></p>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400">Siswa Lulus</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['alumni']); ?></p>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-semibold border-t border-slate-100 pt-2">Status: Alumni</p>
        </div>

        <!-- Stat Card 5 -->
        <div @click="openUltahModal()" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-center hover:shadow-md transition-all group cursor-pointer">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-birthday-cake text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ultah Hari Ini</h3>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-400 mb-1">Siswa</p>
                <p class="text-3xl font-bold text-slate-800 tracking-tight"><?= number_format($data['stats']['ultah']); ?></p>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header Tabel -->
    <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Data Siswa</h2>
            <p class="text-sm text-slate-500">Kelola informasi peserta didik sekolah.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Tombol Export Excel -->
            <a href="<?= BASEURL; ?>/siswa/export" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export
            </a>
            
            <!-- Dropdown Import -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-100 z-10" style="display: none;">
                    <a href="<?= BASEURL; ?>/siswa/template" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 rounded-t-lg">1. Unduh Template</a>
                    <button @click="importModalOpen = true; open = false" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 rounded-b-lg">2. Unggah Template</button>
                </div>
            </div>

            <button type="button" id="btnHapusMasal" class="hidden bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm items-center gap-2" onclick="submitHapusMasal()">
                <i class="fas fa-trash"></i> Hapus (<span id="countHapusMasal">0</span>)
            </button>
            <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm shrink-0">
                <i class="fas fa-plus"></i>
                Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="px-6 py-4 bg-white border-b border-slate-200">
        <form action="<?= BASEURL; ?>/siswa" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Kelas</label>
                <select name="kelas" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kelas</option>
                    <?php foreach($data['filter_options']['kelas'] as $kls): ?>
                        <option value="<?= $kls; ?>" <?= ($data['filters']['kelas'] == $kls) ? 'selected' : ''; ?>><?= $kls; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Gender</label>
                <select name="jk" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="L" <?= ($data['filters']['jk'] == 'L') ? 'selected' : ''; ?>>Laki-Laki</option>
                    <option value="P" <?= ($data['filters']['jk'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>

            <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="Aktif" <?= ($data['filters']['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Alumni" <?= ($data['filters']['status'] == 'Alumni') ? 'selected' : ''; ?>>Alumni</option>
                    <option value="Keluar" <?= ($data['filters']['status'] == 'Keluar') ? 'selected' : ''; ?>>Keluar</option>
                </select>
            </div>

            <div class="w-full sm:w-auto flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <?php if(array_filter($data['filters'])): ?>
                <a href="<?= BASEURL; ?>/siswa" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i> Reset
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="px-6 py-4 border-b border-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-200 bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-100 flex items-center justify-center text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-600 shrink-0">
                <?php if($_SESSION['flash']['tipe'] == 'success'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <?php else: ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <?php endif; ?>
            </div>
            <p class="text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-800 text-sm">
                Data Siswa <strong><?= $_SESSION['flash']['pesan'] ?></strong> <?= $_SESSION['flash']['aksi'] ?>.
            </p>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider border-b border-slate-200">
                    <th class="px-6 py-4 w-10 text-center" data-sortable="false">
                        <input type="checkbox" id="chk-all-siswa" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    </th>
                    <th class="px-6 py-4 font-semibold">NISN</th>
                    <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-4 font-semibold">L/P</th>
                    <th class="px-6 py-4 font-semibold">Kelas</th>
                    <th class="px-6 py-4 font-semibold">Akun Login</th>
                    <th class="px-6 py-4 font-semibold text-center w-32" data-sortable="false">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($data['siswa'] as $s): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-center">
                        <input type="checkbox" value="<?= $s['id']; ?>" class="chk-siswa rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($s['nisn']); ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if(!empty($s['foto'])): ?>
                                <img src="<?= $s['foto']; ?>" class="w-8 h-8 rounded-full object-cover shrink-0 cursor-pointer" onclick="openEditModalSiswa(<?= $s['id']; ?>)" title="Klik untuk edit foto">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0 cursor-pointer" onclick="openEditModalSiswa(<?= $s['id']; ?>)" title="Klik untuk edit foto">
                                    <?= substr($s['nama_lengkap'], 0, 1); ?>
                                </div>
                            <?php endif; ?>
                            <span class="font-medium text-slate-700"><?= htmlspecialchars($s['nama_lengkap']); ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= $s['jenis_kelamin'] == 'L' ? 'L' : 'P'; ?></td>
                    <td class="px-6 py-4 text-slate-600 font-medium"><?= htmlspecialchars($s['nama_kelas'] ?? 'Belum Diatur'); ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                            @<?= htmlspecialchars($s['username']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <button type="button" @click="openDetailModalSiswa(<?= $s['id']; ?>)" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" @click="openEditModalSiswa(<?= $s['id']; ?>)" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="<?= BASEURL; ?>/siswa/resetSandi/<?= $s['id']; ?>" onclick="return confirm('Reset kata sandi <?= htmlspecialchars($s['nama_lengkap'], ENT_QUOTES) ?> menjadi 123456?')" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Reset Kata Sandi">
                            <i class="fas fa-key"></i>
                        </a>
                        <button type="button" @click="$dispatch('open-delete-modal', { url: '<?= BASEURL; ?>/siswa/hapus/<?= $s['id']; ?>' })" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(empty($data['siswa'])): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Belum ada data siswa. Klik tombol "Tambah Siswa" untuk memulai.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <form id="formSubmitHapusMasal" action="<?= BASEURL; ?>/siswa/hapusMasal" method="POST" class="hidden"></form>
    </div> <!-- End Table Container -->

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Doughnut Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-emerald-500"></i>
                Distribusi Siswa per Kelas
            </h3>
            <div class="relative flex-grow flex items-center justify-center min-h-[300px]">
                <canvas id="siswaDoughnutChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-blue-500"></i>
                Jumlah Siswa per Kelas
            </h3>
            <div class="relative flex-grow flex items-center justify-center min-h-[300px]">
                <canvas id="siswaBarChart"></canvas>
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
                            <h3 class="text-xl font-bold leading-6 text-slate-900">Tambah Siswa Baru</h3>
                            <p class="text-sm text-slate-500 mt-1">Lengkapi data diri siswa. Akun login akan dibuatkan secara otomatis.</p>
                        </div>
                    </div>
                </div>

                <form action="<?= BASEURL; ?>/siswa/tambah" method="POST">
                    <div class="bg-slate-50 px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Profil -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-slate-700 border-b pb-2">Biodata Siswa</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">NISN</label>
                                <input type="text" name="nisn" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                                    <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tgl Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Detail & Akun -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-slate-700 border-b pb-2">Data Tambahan & Akun</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Wali</label>
                                <input type="text" name="nama_wali" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">No HP Wali (Mulai 62)</label>
                                <input type="text" name="no_hp_wali" placeholder="628..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                                <textarea name="alamat" rows="1" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white"></textarea>
                            </div>

                            <div class="pt-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Username Login</label>
                                <input type="text" name="username" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Password Sementara</label>
                                <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
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
                    <h3 class="text-lg font-bold text-slate-900">Import Data Siswa</h3>
                    <button @click="importModalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/siswa/import" method="post" enctype="multipart/form-data">
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
                    <h3 class="text-xl font-bold leading-6 text-slate-900">Edit Data Siswa</h3>
                </div>

                <form action="<?= BASEURL; ?>/siswa/ubah" method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <input type="hidden" name="foto" id="edit_foto_base64">
                    
                    <div class="bg-slate-50 px-6 py-6 space-y-4">
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="relative w-16 h-16 rounded-full overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                <img id="edit_foto_preview" src="" class="w-full h-full object-cover hidden">
                                <div id="edit_foto_initials" class="w-full h-full flex items-center justify-center text-xl font-bold text-emerald-600 bg-emerald-100">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Foto Profil (Opsional)</label>
                                <input type="file" id="edit_foto_input" accept="image/*" class="text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                <p class="text-xs text-slate-400 mt-1">Otomatis dikompres</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">NISN</label>
                            <input type="text" name="nisn" id="edit_nisn" readonly class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-200 text-slate-500 cursor-not-allowed">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-secondary focus:border-secondary">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                                <select name="jenis_kelamin" id="edit_jenis_kelamin" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-secondary focus:border-secondary">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-secondary focus:border-secondary">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Wali</label>
                                <input type="text" name="nama_wali" id="edit_nama_wali" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-secondary focus:border-secondary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">No HP Wali</label>
                                <input type="text" name="no_hp_wali" id="edit_no_hp_wali" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-secondary focus:border-secondary">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                            <textarea name="alamat" id="edit_alamat" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-secondary focus:border-secondary"></textarea>
                        </div>
                    </div>

                    <div class="bg-white px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 sm:w-auto transition-colors">Simpan Perubahan</button>
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
                                <p class="text-sm text-slate-500">Apakah Anda yakin ingin menghapus data siswa ini beserta akun login yang terkait? Tindakan ini tidak dapat dibatalkan.</p>
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
    <!-- Modal Detail Data Siswa -->
    <div x-show="detailModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div x-show="detailModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="detailModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="detailModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold leading-6 text-slate-900 flex items-center gap-2">
                        <i class="fas fa-address-card text-blue-500"></i>
                        Profil Detail Siswa
                    </h3>
                    <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="bg-slate-50 px-6 py-6">
                    <!-- Profile Header with Photo -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 mb-6 pb-6 border-b border-slate-200">
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg overflow-hidden shrink-0 bg-white flex items-center justify-center text-4xl font-bold text-blue-200">
                            <template x-if="currentSiswa.foto">
                                <img :src="currentSiswa.foto" class="w-full h-full object-cover" alt="Foto Siswa">
                            </template>
                            <template x-if="!currentSiswa.foto">
                                <span x-text="currentSiswa.nama_lengkap ? currentSiswa.nama_lengkap.charAt(0).toUpperCase() : '?'"></span>
                            </template>
                        </div>
                        <div class="text-center sm:text-left flex-1">
                            <h4 class="text-2xl font-bold text-slate-800 mb-1" x-text="currentSiswa.nama_lengkap"></h4>
                            <p class="text-sm font-medium text-blue-600 mb-3 bg-blue-50 inline-block px-3 py-1 rounded-full border border-blue-100" x-text="'NISN: ' + currentSiswa.nisn"></p>
                            
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-sm text-slate-600">
                                <span class="flex items-center gap-1"><i class="fas fa-venus-mars w-4 text-center text-slate-400"></i> <span x-text="currentSiswa.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan'"></span></span>
                                <span class="text-slate-300">•</span>
                                <span class="flex items-center gap-1"><i class="fas fa-calendar-alt w-4 text-center text-slate-400"></i> <span x-text="currentSiswa.tanggal_lahir ? new Date(currentSiswa.tanggal_lahir).toLocaleDateString('id-ID') : '-'"></span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details Info Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Akademik</h5>
                            <ul class="space-y-3">
                                <li class="flex flex-col">
                                    <span class="text-xs text-slate-500">Status Akademik</span>
                                    <span class="font-medium text-slate-800 flex items-center gap-2 mt-0.5">
                                        <span class="w-2 h-2 rounded-full" :class="{'bg-emerald-500': currentSiswa.status === 'Aktif', 'bg-blue-500': currentSiswa.status === 'Alumni', 'bg-red-500': currentSiswa.status === 'Keluar'}"></span>
                                        <span x-text="currentSiswa.status || 'Aktif'"></span>
                                    </span>
                                </li>
                                <li class="flex flex-col">
                                    <span class="text-xs text-slate-500">Akun Login (Username)</span>
                                    <span class="font-medium text-slate-800 mt-0.5" x-text="'@' + currentSiswa.username"></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Kontak & Domisili</h5>
                            <ul class="space-y-3">
                                <li class="flex flex-col">
                                    <span class="text-xs text-slate-500">Nama Wali</span>
                                    <span class="font-medium text-slate-800 mt-0.5" x-text="currentSiswa.nama_wali || '-'"></span>
                                </li>
                                <li class="flex flex-col">
                                    <span class="text-xs text-slate-500">Nomor Telepon Wali</span>
                                    <span class="font-medium text-slate-800 mt-0.5" x-text="currentSiswa.no_hp_wali || '-'"></span>
                                </li>
                                <li class="flex flex-col">
                                    <span class="text-xs text-slate-500">Alamat Rumah</span>
                                    <span class="font-medium text-slate-800 mt-0.5" x-text="currentSiswa.alamat || '-'"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" @click="detailModalOpen = false" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModalSiswa(id) {
    fetch('<?= BASEURL; ?>/siswa/detail/' + id)
    .then(response => response.json())
    .then(data => {
        window.dispatchEvent(new CustomEvent('open-detail-modal', { detail: data }));
    })
    .catch(err => console.error(err));
}

function openEditModalSiswa(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('<?= BASEURL; ?>/siswa/getubah', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_user_id').value = data.user_id;
        document.getElementById('edit_nisn').value = data.nisn;
        document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
        document.getElementById('edit_jenis_kelamin').value = data.jenis_kelamin;
        document.getElementById('edit_tanggal_lahir').value = data.tanggal_lahir;
        document.getElementById('edit_nama_wali').value = data.nama_wali;
        document.getElementById('edit_no_hp_wali').value = data.no_hp_wali;
        document.getElementById('edit_alamat').value = data.alamat;
        
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

function openUltahModal() {
    window.dispatchEvent(new CustomEvent('open-ultah-modal'));
    document.getElementById('ultahContainer').innerHTML = '<div class="flex justify-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i></div>';
    
    fetch('<?= BASEURL; ?>/siswa/getulangtahun')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('ultahContainer');
            if(data.length === 0) {
                container.innerHTML = '<div class="text-center text-slate-500 py-4">Belum ada siswa yang berulang tahun hari ini</div>';
                return;
            }
            
            let html = '';
            data.forEach(s => {
                let fotoHtml = s.foto ? `<img src="${s.foto}" class="w-10 h-10 rounded-full object-cover">` : `<div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">${s.nama_lengkap.substring(0,1).toUpperCase()}</div>`;
                html += `
                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        ${fotoHtml}
                        <div>
                            <p class="font-bold text-slate-800">${s.nama_lengkap}</p>
                            <p class="text-xs text-slate-500">NISN: ${s.nisn}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
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
        'rgba(16, 185, 129, 0.8)', // emerald
        'rgba(59, 130, 246, 0.8)', // blue
        'rgba(245, 158, 11, 0.8)', // amber
        'rgba(236, 72, 153, 0.8)', // pink
        'rgba(139, 92, 246, 0.8)', // purple
        'rgba(14, 165, 233, 0.8)', // sky
        'rgba(244, 63, 94, 0.8)'   // rose
    ];
    
    const borderColors = bgColors.map(color => color.replace('0.8', '1'));

    if(chartLabels.length > 0) {
        // Doughnut Chart
        const ctxDoughnut = document.getElementById('siswaDoughnutChart').getContext('2d');
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

        // Bar Chart
        const ctxBar = document.getElementById('siswaBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: chartData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: "'Inter', sans-serif" }
                        },
                        grid: { borderDash: [2, 4], color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { family: "'Inter', sans-serif" } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    } else {
        // Fallback jika tidak ada data kelas
        document.getElementById('siswaDoughnutChart').parentElement.innerHTML = '<p class="text-slate-400 text-sm">Tidak ada data kelas aktif</p>';
        document.getElementById('siswaBarChart').parentElement.innerHTML = '<p class="text-slate-400 text-sm">Tidak ada data kelas aktif</p>';
    }
});
// Script Hapus Masal
let selectedSiswaIds = new Set();
document.addEventListener('change', function(e) {
    if(e.target && e.target.classList.contains('chk-siswa')) {
        if(e.target.checked) {
            selectedSiswaIds.add(e.target.value);
        } else {
            selectedSiswaIds.delete(e.target.value);
        }
        updateHapusMasalButton();
    }
    if(e.target && e.target.id === 'chk-all-siswa') {
        const checkboxes = document.querySelectorAll('.chk-siswa');
        checkboxes.forEach(chk => {
            chk.checked = e.target.checked;
            if(e.target.checked) selectedSiswaIds.add(chk.value);
            else selectedSiswaIds.delete(chk.value);
        });
        updateHapusMasalButton();
    }
});

function updateHapusMasalButton() {
    const btn = document.getElementById('btnHapusMasal');
    const countSpan = document.getElementById('countHapusMasal');
    if(selectedSiswaIds.size > 0) {
        btn.classList.remove('hidden');
        btn.classList.add('flex');
        countSpan.textContent = selectedSiswaIds.size;
    } else {
        btn.classList.add('hidden');
        btn.classList.remove('flex');
    }
}

function submitHapusMasal() {
    if(confirm(`Yakin ingin menghapus ${selectedSiswaIds.size} siswa terpilih beserta seluruh data terkait?`)) {
        const form = document.getElementById('formSubmitHapusMasal');
        form.innerHTML = ''; // clear
        selectedSiswaIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        form.submit();
    }
}
</script>

