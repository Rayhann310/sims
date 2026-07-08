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
    <div x-show="activeTab == 'global'" class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <form action="<?= BASEURL; ?>/PengaturanAbsensi/updateGlobal" method="POST" class="p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Mode Absensi Siswa</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mode Kehadiran</label>
                    <select name="mode_siswa" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        <option value="Normal" <?= $data['global']['mode_siswa'] == 'Normal' ? 'selected' : '' ?>>Normal (Scan Pagi Sekali)</option>
                        <option value="Per Jam Pelajaran" <?= $data['global']['mode_siswa'] == 'Per Jam Pelajaran' ? 'selected' : '' ?>>Per Jam Pelajaran (Oleh Guru di Kelas)</option>
                    </select>
                    <p class="text-xs text-slate-500 mt-2">Pilih "Per Jam Pelajaran" jika ingin guru yang menscan kehadiran siswa di tiap kelas.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Min. Jam Pelajaran (Hadir)</label>
                    <input type="number" name="min_jam_pelajaran_siswa" value="<?= $data['global']['min_jam_pelajaran_siswa'] ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    <p class="text-xs text-slate-500 mt-2">Berapa kelas minimal yang harus diikuti siswa agar dianggap Hadir pada hari tersebut (jika Mode Per Jam Pelajaran).</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Aturan Waktu Guru (Default)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Batas Jam Masuk</label>
                    <input type="time" name="batas_jam_masuk_guru" value="<?= $data['global']['batas_jam_masuk_guru'] ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Batas Jam Pulang</label>
                    <input type="time" name="batas_jam_keluar_guru" value="<?= $data['global']['batas_jam_keluar_guru'] ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Toleransi Terlambat (Menit)</label>
                    <input type="number" name="toleransi_terlambat_guru" value="<?= $data['global']['toleransi_terlambat_guru'] ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100 mt-6">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors">
                    Simpan Pengaturan Global
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
</div>
