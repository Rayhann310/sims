<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-800">Rekap Kedisiplinan Siswa</h2>
        <p class="text-sm text-slate-500 mt-1">Pantau total poin pelanggaran dikurangi penghargaan dari seluruh siswa.</p>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ currentTab: '<?= !empty($data['siswa']) ? ($data['siswa'][0]['nama_kelas'] ?? 'Tanpa Kelas') : 'Semua' ?>' }">
        
        <!-- Tab Navigation -->
        <div class="bg-slate-50 p-2 border-b border-slate-200 overflow-x-auto flex gap-2">
            <?php 
                $kelas_list = [];
                foreach($data['siswa'] as $s) {
                    $kelas_nama = $s['nama_kelas'] ?? 'Tanpa Kelas';
                    if (!in_array($kelas_nama, $kelas_list)) {
                        $kelas_list[] = $kelas_nama;
                    }
                }
            ?>
            <?php foreach($kelas_list as $kls): ?>
                <button @click="currentTab = '<?= $kls ?>'" 
                        :class="currentTab === '<?= $kls ?>' ? 'bg-white text-emerald-600 shadow-sm font-semibold' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'"
                        class="px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap">
                    <?= $kls ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white text-slate-600 font-medium border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Rombel</th>
                        <th class="px-6 py-4">Total Poin</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach($data['siswa'] as $s): ?>
                    <tr x-show="currentTab === '<?= $s['nama_kelas'] ?? 'Tanpa Kelas' ?>'" class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4"><?= $s['nis']; ?></td>
                        <td class="px-6 py-4 font-medium"><?= $s['nama_lengkap'] ?? 'Siswa (Tanpa Nama)'; ?></td>
                        <td class="px-6 py-4"><?= $s['nama_rombel'] ? $s['nama_kelas'] . ' - ' . $s['nama_rombel'] : '-'; ?></td>
                        <td class="px-6 py-4 font-bold">
                            <?php if($s['total_poin'] <= 150): ?>
                                <span class="text-red-600 bg-red-50 px-2.5 py-1 rounded-lg font-bold border border-red-200"><?= $s['total_poin']; ?> Poin</span>
                            <?php elseif($s['total_poin'] < 200): ?>
                                <span class="text-orange-500 bg-orange-50 px-2.5 py-1 rounded-lg font-bold border border-orange-200"><?= $s['total_poin']; ?> Poin</span>
                            <?php elseif($s['total_poin'] > 200): ?>
                                <span class="text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg font-bold border border-emerald-200"><?= $s['total_poin']; ?> Poin</span>
                            <?php else: ?>
                                <span class="text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg font-bold border border-slate-200">200 Poin</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= BASEURL; ?>/kedisiplinan/riwayat/<?= $s['id']; ?>" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors text-xs font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail Riwayat
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
