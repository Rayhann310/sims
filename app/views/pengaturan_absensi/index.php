<?php
$g       = $data['global'];
$modeAbs = $g['mode_absen_siswa'] ?? 'Masuk Saja';
?>

<div class="space-y-6" x-data="{ activeTab: 'siswa', guruModalOpen: false, modeAbsen: '<?= htmlspecialchars($modeAbs) ?>' }">

    <!-- ===== HEADER ===== -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h1 class="text-2xl font-bold text-slate-800">Pengaturan Absensi</h1>
        <p class="text-sm text-slate-500 mt-1">Atur mode kehadiran siswa dan jam absensi guru secara terpisah.</p>
    </div>

    <!-- Flash -->
    <?php if(isset($_SESSION['flash'])): ?>
    <div class="px-5 py-4 rounded-xl border flex items-center gap-3
        <?= $_SESSION['flash']['tipe'] == 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-red-50 border-red-200 text-red-800' ?>">
        <i class="fas <?= $_SESSION['flash']['tipe'] == 'success' ? 'fa-check-circle text-emerald-500' : 'fa-exclamation-circle text-red-500' ?>"></i>
        <p class="text-sm font-medium">Pengaturan <strong><?= $_SESSION['flash']['pesan'] ?></strong> <?= $_SESSION['flash']['aksi'] ?>.</p>
    </div>
    <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- ===== TABS ===== -->
    <div class="flex border-b border-slate-200 bg-white rounded-t-2xl px-4">
        <button @click="activeTab = 'siswa'"
                :class="activeTab === 'siswa' ? 'border-b-2 border-indigo-600 text-indigo-700 font-bold' : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-3.5 text-sm transition-colors flex items-center gap-2">
            <i class="fas fa-user-check"></i> Absensi Siswa
        </button>
        <button @click="activeTab = 'guru'"
                :class="activeTab === 'guru' ? 'border-b-2 border-indigo-600 text-indigo-700 font-bold' : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-3.5 text-sm transition-colors flex items-center gap-2">
            <i class="fas fa-chalkboard-teacher"></i> Absensi Guru
        </button>
    </div>

    <!-- ======================= TAB SISWA ======================= -->
    <div x-show="activeTab === 'siswa'" x-cloak>
        <form action="<?= BASEURL ?>/PengaturanAbsensi/updateGlobal" method="POST" class="space-y-5">

            <!-- Mode Absensi Siswa -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 text-base mb-1 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list-check text-sm"></i>
                    </span>
                    Mode Pencatatan Kehadiran Siswa
                </h3>
                <p class="text-xs text-slate-500 ml-10 mb-5">Pilih bagaimana siswa melakukan absensi harian.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Masuk Saja -->
                    <label @click="modeAbsen = 'Masuk Saja'"
                           :class="modeAbsen === 'Masuk Saja' ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-200' : 'border-slate-200 bg-white hover:border-emerald-300'"
                           class="relative cursor-pointer rounded-xl border-2 p-4 transition-all duration-150 block">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                 :class="modeAbsen === 'Masuk Saja' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400'">
                                <i class="fas fa-sign-in-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Masuk Saja</p>
                                <p class="text-xs text-slate-500 mt-0.5">Siswa scan QR satu kali saat datang pagi.</p>
                            </div>
                        </div>
                        <div x-show="modeAbsen === 'Masuk Saja'"
                             class="absolute top-2.5 right-2.5 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>
                    </label>

                    <!-- Masuk & Pulang -->
                    <label @click="modeAbsen = 'Masuk & Pulang'"
                           :class="modeAbsen === 'Masuk & Pulang' ? 'border-violet-500 bg-violet-50 ring-2 ring-violet-200' : 'border-slate-200 bg-white hover:border-violet-300'"
                           class="relative cursor-pointer rounded-xl border-2 p-4 transition-all duration-150 block">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                 :class="modeAbsen === 'Masuk & Pulang' ? 'bg-violet-500 text-white' : 'bg-slate-100 text-slate-400'">
                                <i class="fas fa-exchange-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Masuk &amp; Pulang</p>
                                <p class="text-xs text-slate-500 mt-0.5">Scan pertama = masuk, scan kedua = pulang.</p>
                            </div>
                        </div>
                        <div x-show="modeAbsen === 'Masuk & Pulang'"
                             class="absolute top-2.5 right-2.5 w-5 h-5 bg-violet-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>
                    </label>

                    <!-- Per Mata Pelajaran -->
                    <label @click="modeAbsen = 'Per Mata Pelajaran'"
                           :class="modeAbsen === 'Per Mata Pelajaran' ? 'border-sky-500 bg-sky-50 ring-2 ring-sky-200' : 'border-slate-200 bg-white hover:border-sky-300'"
                           class="relative cursor-pointer rounded-xl border-2 p-4 transition-all duration-150 block">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                 :class="modeAbsen === 'Per Mata Pelajaran' ? 'bg-sky-500 text-white' : 'bg-slate-100 text-slate-400'">
                                <i class="fas fa-chalkboard-teacher text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Per Mata Pelajaran</p>
                                <p class="text-xs text-slate-500 mt-0.5">Guru mencatat absensi di tiap kelas.</p>
                            </div>
                        </div>
                        <div x-show="modeAbsen === 'Per Mata Pelajaran'"
                             class="absolute top-2.5 right-2.5 w-5 h-5 bg-sky-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>
                    </label>
                </div>

                <!-- Hidden field mode_absen_siswa -->
                <input type="hidden" name="mode_absen_siswa" :value="modeAbsen">
            </div>

            <!-- Jam Absensi Siswa -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 text-base mb-1 flex items-center gap-2">
                    <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-sm"></i>
                    </span>
                    Jam Absensi Siswa
                </h3>
                <p class="text-xs text-slate-500 ml-10 mb-5">Berlaku untuk mode Masuk Saja dan Masuk & Pulang.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5"
                     :class="modeAbsen === 'Per Mata Pelajaran' ? 'opacity-40 pointer-events-none select-none' : ''">

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                            <i class="fas fa-sun text-amber-400"></i>
                            Batas Jam Masuk Siswa
                        </label>
                        <input type="time" name="batas_jam_masuk_siswa"
                               value="<?= substr($g['batas_jam_masuk_siswa'] ?? '07:00:00', 0, 5) ?>"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-sm">
                        <p class="text-xs text-slate-400">Waktu maksimal siswa dianggap tepat waktu.</p>
                    </div>

                    <div class="space-y-1.5" :class="modeAbsen !== 'Masuk & Pulang' ? 'opacity-50' : ''">
                        <label class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                            <i class="fas fa-moon text-violet-400"></i>
                            Batas Jam Pulang Siswa
                        </label>
                        <input type="time" name="batas_jam_pulang_siswa"
                               value="<?= substr($g['batas_jam_pulang_siswa'] ?? '14:00:00', 0, 5) ?>"
                               :disabled="modeAbsen !== 'Masuk & Pulang'"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-sm disabled:cursor-not-allowed">
                        <p class="text-xs text-slate-400">Hanya aktif pada mode Masuk &amp; Pulang.</p>
                    </div>
                </div>
            </div>

            <!-- Mode evaluasi (Per JP) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 text-base mb-1 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-layer-group text-sm"></i>
                    </span>
                    Evaluasi Rekap Harian (Per Jam Pelajaran)
                </h3>
                <p class="text-xs text-slate-500 ml-10 mb-5">Digunakan saat mode Per Mata Pelajaran — menentukan ambang batas hadir.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700">Mode Evaluasi Harian</label>
                        <select name="mode_siswa"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm">
                            <option value="Normal" <?= ($g['mode_siswa'] ?? '') === 'Normal' ? 'selected' : '' ?>>Normal (Catat Langsung)</option>
                            <option value="Per Jam Pelajaran" <?= ($g['mode_siswa'] ?? '') === 'Per Jam Pelajaran' ? 'selected' : '' ?>>Per Jam Pelajaran (Akumulasi)</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700">Min. Jam Pelajaran untuk Hadir</label>
                        <input type="number" name="min_jam_pelajaran_siswa"
                               value="<?= $g['min_jam_pelajaran_siswa'] ?? 4 ?>" min="1" max="20"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm">
                        <p class="text-xs text-slate-400">JP minimal agar siswa dianggap Hadir (mode Per JP).</p>
                    </div>
                </div>
            </div>

            <!-- Field guru (masih dipakai update global) — hidden -->
            <input type="hidden" name="batas_jam_masuk_guru"    value="<?= $g['batas_jam_masuk_guru'] ?? '07:00:00' ?>">
            <input type="hidden" name="batas_jam_keluar_guru"   value="<?= $g['batas_jam_keluar_guru'] ?? '15:00:00' ?>">
            <input type="hidden" name="toleransi_terlambat_guru" value="<?= $g['toleransi_terlambat_guru'] ?? 15 ?>">

            <div class="flex justify-end">
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-200 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengaturan Siswa
                </button>
            </div>
        </form>
    </div>

    <!-- ======================= TAB GURU ======================= -->
    <div x-show="activeTab === 'guru'" x-cloak class="space-y-5">

        <!-- Default Jam Guru -->
        <form action="<?= BASEURL ?>/PengaturanAbsensi/updateGlobal" method="POST">
            <!-- Hidden fields untuk siswa — tetap kirim supaya tidak di-reset -->
            <input type="hidden" name="mode_absen_siswa"         value="<?= htmlspecialchars($g['mode_absen_siswa'] ?? 'Masuk Saja') ?>">
            <input type="hidden" name="mode_siswa"               value="<?= htmlspecialchars($g['mode_siswa'] ?? 'Normal') ?>">
            <input type="hidden" name="min_jam_pelajaran_siswa"  value="<?= $g['min_jam_pelajaran_siswa'] ?? 4 ?>">
            <input type="hidden" name="batas_jam_masuk_siswa"    value="<?= $g['batas_jam_masuk_siswa'] ?? '07:00:00' ?>">
            <input type="hidden" name="batas_jam_pulang_siswa"   value="<?= $g['batas_jam_pulang_siswa'] ?? '14:00:00' ?>">

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 text-base mb-1 flex items-center gap-2">
                    <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-sm"></i>
                    </span>
                    Jam Absensi Guru (Default Semua Guru)
                </h3>
                <p class="text-xs text-slate-500 ml-10 mb-5">Berlaku untuk semua guru kecuali yang memiliki pengaturan khusus di bawah.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                            <i class="fas fa-sun text-amber-400"></i> Batas Jam Masuk Guru
                        </label>
                        <input type="time" name="batas_jam_masuk_guru"
                               value="<?= substr($g['batas_jam_masuk_guru'] ?? '07:00:00', 0, 5) ?>"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm">
                        <p class="text-xs text-slate-400">Guru dianggap terlambat setelah jam ini.</p>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                            <i class="fas fa-moon text-slate-400"></i> Batas Jam Pulang Guru
                        </label>
                        <input type="time" name="batas_jam_keluar_guru"
                               value="<?= substr($g['batas_jam_keluar_guru'] ?? '15:00:00', 0, 5) ?>"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm">
                        <p class="text-xs text-slate-400">Guru boleh pulang mulai jam ini.</p>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                            <i class="fas fa-hourglass-half text-rose-400"></i> Toleransi Terlambat (Menit)
                        </label>
                        <input type="number" name="toleransi_terlambat_guru"
                               value="<?= $g['toleransi_terlambat_guru'] ?? 15 ?>" min="0" max="120"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm">
                        <p class="text-xs text-slate-400">Menit toleransi sebelum dicatat terlambat.</p>
                    </div>
                </div>

                <div class="flex justify-end mt-5">
                    <button type="submit"
                            class="px-7 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl shadow-md shadow-orange-200 transition-colors flex items-center gap-2 text-sm">
                        <i class="fas fa-save"></i> Simpan Jam Default Guru
                    </button>
                </div>
            </div>
        </form>

        <!-- Pengecualian Jam Per Guru -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <div>
                    <h3 class="font-bold text-slate-800">Pengecualian Jadwal Per Guru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Override jam absensi untuk guru tertentu.</p>
                </div>
                <button @click="guruModalOpen = true"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                    <i class="fas fa-plus text-xs"></i> Tambah
                </button>
            </div>

            <!-- Tabel tanpa simple-datatables -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wide">
                            <th class="px-5 py-3 font-semibold">Nama Guru</th>
                            <th class="px-5 py-3 font-semibold">NIP</th>
                            <th class="px-5 py-3 font-semibold">Jam Masuk</th>
                            <th class="px-5 py-3 font-semibold">Jam Pulang</th>
                            <th class="px-5 py-3 font-semibold">Toleransi</th>
                            <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach($data['guru'] as $gu): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-semibold text-slate-800"><?= htmlspecialchars($gu['nama_lengkap']) ?></td>
                            <td class="px-5 py-3.5 text-slate-500 text-xs font-mono"><?= htmlspecialchars($gu['nip']) ?></td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-sun text-[10px]"></i>
                                    <?= substr($gu['batas_jam_masuk'], 0, 5) ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-moon text-[10px]"></i>
                                    <?= substr($gu['batas_jam_keluar'], 0, 5) ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600"><?= $gu['toleransi_terlambat'] ?> menit</td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="<?= BASEURL ?>/PengaturanAbsensi/deleteGuru/<?= $gu['guru_id'] ?>"
                                   onclick="return confirm('Hapus pengecualian untuk <?= htmlspecialchars($gu['nama_lengkap']) ?>?')"
                                   class="w-8 h-8 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($data['guru'])): ?>
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">
                                <i class="fas fa-users-slash text-2xl mb-2 block"></i>
                                Belum ada pengecualian jadwal guru.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== MODAL TAMBAH OVERRIDE GURU ===== -->
    <div x-show="guruModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="guruModalOpen = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg">Atur Jadwal Khusus Guru</h3>
                <button @click="guruModalOpen = false"
                        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center">
                    <i class="fas fa-times text-slate-500 text-sm"></i>
                </button>
            </div>

            <form action="<?= BASEURL ?>/PengaturanAbsensi/setGuru" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Guru</label>
                    <select name="guru_id" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm">
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach($data['list_guru'] as $lg): ?>
                        <option value="<?= $lg['id'] ?>"><?= htmlspecialchars($lg['nama_lengkap']) ?> (<?= htmlspecialchars($lg['nip']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-sun text-amber-400 mr-1"></i> Jam Masuk
                        </label>
                        <input type="time" name="batas_jam_masuk" value="07:00"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-moon text-slate-400 mr-1"></i> Jam Pulang
                        </label>
                        <input type="time" name="batas_jam_keluar" value="15:00"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-hourglass-half text-rose-400 mr-1"></i> Toleransi Terlambat (Menit)
                    </label>
                    <input type="number" name="toleransi_terlambat" value="15" min="0" max="120"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-slate-50 text-sm" required>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="guruModalOpen = false"
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
