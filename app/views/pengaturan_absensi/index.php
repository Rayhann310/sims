<div class="space-y-6" x-data="{ activeTab: 'global', guruModalOpen: false }">

    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h1 class="text-2xl font-bold text-slate-800">Pengaturan Absensi</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola mode kehadiran siswa dan aturan jam masuk/keluar guru.</p>
    </div>

    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="px-6 py-4 rounded-xl border border-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-200 bg-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-50 flex items-center gap-3">
            <p class="text-<?= $_SESSION['flash']['tipe'] == 'success' ? 'green' : 'red' ?>-800 text-sm">
                Data <strong><?= $_SESSION['flash']['pesan'] ?></strong> <?= $_SESSION['flash']['aksi'] ?>.
            </p>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-slate-200">
        <button @click="activeTab = 'global'" :class="activeTab == 'global' ? 'border-b-2 border-indigo-600 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-3 text-sm transition-colors">
            Pengaturan Global
        </button>
        <button @click="activeTab = 'guru'" :class="activeTab == 'guru' ? 'border-b-2 border-indigo-600 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-3 text-sm transition-colors">
            Pengecualian Jam Guru
        </button>
    </div>

    <!-- Tab Global -->
    <div x-show="activeTab == 'global'" class="space-y-6">
        <form action="<?= BASEURL; ?>/PengaturanAbsensi/updateGlobal" method="POST">

            <!-- ===== SECTION: Mode Absensi Siswa ===== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-sm"><i class="fas fa-user-check"></i></span>
                    Mode Absensi Siswa
                </h3>
                <p class="text-xs text-slate-500 mb-5 ml-10">Tentukan bagaimana siswa mencatat kehadiran harian mereka.</p>

                <!-- Mode Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" x-data="{ selected: '<?= htmlspecialchars($data['global']['mode_absen_siswa'] ?? 'Masuk Saja') ?>' }">

                    <!-- Masuk Saja -->
                    <label @click="selected = 'Masuk Saja'"
                           class="relative cursor-pointer rounded-xl border-2 p-4 transition-all duration-200 block"
                           :class="selected === 'Masuk Saja' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white hover:border-emerald-300'">
                        <input type="radio" name="mode_absen_siswa" value="Masuk Saja" class="sr-only" :checked="selected === 'Masuk Saja'" required>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mt-0.5 shrink-0"
                                 :class="selected === 'Masuk Saja' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <i class="fas fa-sign-in-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Masuk Saja</p>
                                <p class="text-xs text-slate-500 mt-0.5">Siswa scan QR sekali saat datang. Cocok untuk absensi sederhana.</p>
                            </div>
                        </div>
                        <!-- Check badge -->
                        <div x-show="selected === 'Masuk Saja'" class="absolute top-2.5 right-2.5 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>
                    </label>

                    <!-- Masuk & Pulang -->
                    <label @click="selected = 'Masuk & Pulang'"
                           class="relative cursor-pointer rounded-xl border-2 p-4 transition-all duration-200 block"
                           :class="selected === 'Masuk & Pulang' ? 'border-violet-500 bg-violet-50' : 'border-slate-200 bg-white hover:border-violet-300'">
                        <input type="radio" name="mode_absen_siswa" value="Masuk & Pulang" class="sr-only" :checked="selected === 'Masuk & Pulang'">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mt-0.5 shrink-0"
                                 :class="selected === 'Masuk & Pulang' ? 'bg-violet-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <i class="fas fa-exchange-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Masuk &amp; Pulang</p>
                                <p class="text-xs text-slate-500 mt-0.5">Scan pertama = masuk, scan kedua = pulang. Rekam dua waktu kehadiran.</p>
                            </div>
                        </div>
                        <div x-show="selected === 'Masuk & Pulang'" class="absolute top-2.5 right-2.5 w-5 h-5 bg-violet-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>
                    </label>

                    <!-- Per Mata Pelajaran -->
                    <label @click="selected = 'Per Mata Pelajaran'"
                           class="relative cursor-pointer rounded-xl border-2 p-4 transition-all duration-200 block"
                           :class="selected === 'Per Mata Pelajaran' ? 'border-sky-500 bg-sky-50' : 'border-slate-200 bg-white hover:border-sky-300'">
                        <input type="radio" name="mode_absen_siswa" value="Per Mata Pelajaran" class="sr-only" :checked="selected === 'Per Mata Pelajaran'">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mt-0.5 shrink-0"
                                 :class="selected === 'Per Mata Pelajaran' ? 'bg-sky-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <i class="fas fa-chalkboard-teacher text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Per Mata Pelajaran</p>
                                <p class="text-xs text-slate-500 mt-0.5">Guru mencatat absensi di tiap kelas. Scanner siswa mandiri dinonaktifkan.</p>
                            </div>
                        </div>
                        <div x-show="selected === 'Per Mata Pelajaran'" class="absolute top-2.5 right-2.5 w-5 h-5 bg-sky-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>
                    </label>

                    <!-- Hidden inputs agar value terkirim saat radio tidak di-click langsung -->
                    <input type="hidden" name="mode_absen_siswa" :value="selected">
                </div>

                <!-- Batas Jam Siswa (hanya tampil jika bukan Per Mata Pelajaran) -->
                <div x-data="{ selected: '<?= htmlspecialchars($data['global']['mode_absen_siswa'] ?? 'Masuk Saja') ?>' }"
                     x-show="selected !== 'Per Mata Pelajaran'"
                     class="border-t border-slate-100 pt-5">
                    <!-- Perlu sync dengan radio di atas — kita gunakan event -->
                </div>

                <div class="border-t border-slate-100 pt-5 mt-2"
                     x-data="{ modeAbsen: '<?= htmlspecialchars($data['global']['mode_absen_siswa'] ?? 'Masuk Saja') ?>' }"
                     x-init="
                         // Sinkronisasi dengan pilihan radio di atas lewat perubahan hidden input
                         document.querySelectorAll('input[name=mode_absen_siswa]').forEach(el => {
                             el.addEventListener('change', e => { if(e.target.type !== 'hidden') modeAbsen = e.target.value; });
                         });
                         $watch('modeAbsen', val => {
                             // Update hidden input value
                             document.querySelector('input[name=mode_absen_siswa][type=hidden]').value = val;
                         });
                     ">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Jam Batas Absensi Siswa <span class="text-slate-400 font-normal">(berlaku untuk mode Masuk Saja &amp; Masuk &amp; Pulang)</span></h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" :class="modeAbsen === 'Per Mata Pelajaran' ? 'opacity-40 pointer-events-none' : ''">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                <i class="fas fa-sun text-amber-400 mr-1"></i> Batas Jam Masuk Siswa
                            </label>
                            <input type="time" name="batas_jam_masuk_siswa" value="<?= $data['global']['batas_jam_masuk_siswa'] ?? '07:00' ?>"
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-sm">
                            <p class="text-xs text-slate-400 mt-1">Waktu maksimal siswa dianggap tepat waktu saat masuk.</p>
                        </div>
                        <div x-show="modeAbsen === 'Masuk & Pulang'">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                <i class="fas fa-moon text-violet-400 mr-1"></i> Batas Jam Pulang Siswa
                            </label>
                            <input type="time" name="batas_jam_pulang_siswa" value="<?= $data['global']['batas_jam_pulang_siswa'] ?? '14:00' ?>"
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-sm">
                            <p class="text-xs text-slate-400 mt-1">Waktu minimal siswa boleh scan pulang.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION: Mode Per Jam Pelajaran (untuk laporan) ===== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm"><i class="fas fa-clock"></i></span>
                    Evaluasi Kehadiran Harian (Per Jam Pelajaran)
                </h3>
                <p class="text-xs text-slate-500 mb-5 ml-10">Digunakan saat mode "Per Mata Pelajaran" — menentukan kapan siswa dianggap hadir.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Mode Evaluasi Harian (Lama)</label>
                        <select name="mode_siswa" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                            <option value="Normal" <?= ($data['global']['mode_siswa'] ?? '') == 'Normal' ? 'selected' : '' ?>>Normal (Catat langsung)</option>
                            <option value="Per Jam Pelajaran" <?= ($data['global']['mode_siswa'] ?? '') == 'Per Jam Pelajaran' ? 'selected' : '' ?>>Per Jam Pelajaran (Akumulasi)</option>
                        </select>
                        <p class="text-xs text-slate-400 mt-1.5">Pengaturan evaluasi rekap harian dari absensi per pelajaran.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Min. Jam Pelajaran (Hadir)</label>
                        <input type="number" name="min_jam_pelajaran_siswa" value="<?= $data['global']['min_jam_pelajaran_siswa'] ?>"
                               min="1" max="20"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        <p class="text-xs text-slate-400 mt-1.5">Berapa JP minimal agar dianggap Hadir (mode Per Jam Pelajaran).</p>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION: Aturan Guru ===== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                    <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-sm"><i class="fas fa-chalkboard"></i></span>
                    Aturan Waktu Guru (Default)
                </h3>
                <p class="text-xs text-slate-500 mb-5 ml-10">Jam batas masuk, pulang, dan toleransi keterlambatan untuk semua guru.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Batas Jam Masuk</label>
                        <input type="time" name="batas_jam_masuk_guru" value="<?= $data['global']['batas_jam_masuk_guru'] ?>"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Batas Jam Pulang</label>
                        <input type="time" name="batas_jam_keluar_guru" value="<?= $data['global']['batas_jam_keluar_guru'] ?>"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Toleransi Terlambat (Menit)</label>
                        <input type="number" name="toleransi_terlambat_guru" value="<?= $data['global']['toleransi_terlambat_guru'] ?>"
                               min="0" max="120"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-200 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Semua Pengaturan
                </button>
            </div>

        </form>
    </div>

    <!-- Tab Guru Override -->
    <div x-show="activeTab == 'guru'" class="bg-white rounded-2xl shadow-sm border border-slate-200" style="display: none;">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Pengecualian Jadwal Guru</h3>
                <p class="text-sm text-slate-500">Atur jam khusus untuk guru tertentu yang jadwalnya berbeda.</p>
            </div>
            <button @click="guruModalOpen = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Pengecualian
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-sm">
                        <th class="px-6 py-3 font-semibold">Nama Guru / NIP</th>
                        <th class="px-6 py-3 font-semibold">Jam Masuk</th>
                        <th class="px-6 py-3 font-semibold">Jam Pulang</th>
                        <th class="px-6 py-3 font-semibold">Toleransi</th>
                        <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach($data['guru'] as $g): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800"><?= $g['nama_lengkap'] ?></p>
                            <p class="text-xs text-slate-500"><?= $g['nip'] ?></p>
                        </td>
                        <td class="px-6 py-4 font-mono text-sm"><?= $g['batas_jam_masuk'] ?></td>
                        <td class="px-6 py-4 font-mono text-sm"><?= $g['batas_jam_keluar'] ?></td>
                        <td class="px-6 py-4 text-sm"><?= $g['toleransi_terlambat'] ?> menit</td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= BASEURL; ?>/PengaturanAbsensi/deleteGuru/<?= $g['guru_id'] ?>" onclick="return confirm('Hapus pengecualian untuk guru ini?')" class="text-red-500 hover:text-red-700 p-2">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['guru'])): ?>
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-500">Belum ada data pengecualian guru.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Override Guru -->
    <div x-show="guruModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div x-show="guruModalOpen" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="guruModalOpen = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 border border-slate-200">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Atur Pengecualian Jadwal Guru</h3>
                <button @click="guruModalOpen = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <form action="<?= BASEURL; ?>/PengaturanAbsensi/setGuru" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Guru</label>
                    <select name="guru_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach($data['list_guru'] as $lg): ?>
                        <option value="<?= $lg['id'] ?>"><?= $lg['nama_lengkap'] ?> (<?= $lg['nip'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Masuk</label>
                        <input type="time" name="batas_jam_masuk" value="07:00:00" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Pulang</label>
                        <input type="time" name="batas_jam_keluar" value="15:00:00" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Toleransi Terlambat (Menit)</label>
                    <input type="number" name="toleransi_terlambat" value="15" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500" required>
                </div>
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Simpan Pengecualian</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script: sinkronisasi card radio dengan hidden input -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('label[data-mode]');
        // Sinkronisasi via Alpine via events sudah ditangani di x-init
        // Pastikan form tidak kirim dua kali hidden input
        const hiddenInputs = document.querySelectorAll('input[name="mode_absen_siswa"][type="hidden"]');
        if (hiddenInputs.length > 1) {
            // Hapus yang duplikat, sisakan yang terakhir (dari x-data Alpine)
            for (let i = 0; i < hiddenInputs.length - 1; i++) {
                hiddenInputs[i].remove();
            }
        }
    });
    </script>
</div>
