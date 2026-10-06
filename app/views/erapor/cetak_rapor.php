<div class="space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Cetak Rapor</h2>
                <p class="text-slate-500 mt-1">Cetak laporan hasil belajar siswa secara satuan atau per kelas.</p>
            </div>
            <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="<?= BASEURL; ?>/erapor/cetak" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <input type="hidden" name="type" value="preview">
            
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tahun Akademik</label>
                <select name="tahun" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none transition-all">
                    <?php foreach($data['tahun_list'] as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $data['selected_tahun'] == $t['id'] ? 'selected' : '' ?>>
                            <?= $t['nama_tahun'] ?> (Smt <?= $t['semester'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Kelas / Rombel</label>
                <select name="rombel" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none transition-all" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($data['rombel_list'] as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $data['selected_rombel'] == $r['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nama_rombel']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="px-6 py-2.5 bg-slate-800 text-white font-medium rounded-xl hover:bg-slate-900 transition-all shadow-sm flex items-center">
                    <i class="fas fa-search mr-2"></i> Tampilkan
                </button>
                <?php if(!empty($data['siswa_data'])): ?>
                <a href="<?= BASEURL; ?>/erapor/cetak?type=pdf&tahun=<?= $data['selected_tahun'] ?>&rombel=<?= $data['selected_rombel'] ?>" target="_blank" class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-xl hover:bg-rose-700 focus:ring-4 focus:ring-rose-500/20 transition-all shadow-sm flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Cetak Satu Kelas (PDF)
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if(!empty($data['siswa_data'])): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Siswa Kelas Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white">
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 w-16">No</th>
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100">NISN</th>
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100">Nama Siswa</th>
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no=1; foreach($data['siswa_data'] as $s): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-sm text-slate-600"><?= $no++; ?></td>
                        <td class="py-4 px-6 text-sm text-slate-600"><?= htmlspecialchars($s['nisn']) ?></td>
                        <td class="py-4 px-6">
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($s['nama_lengkap']) ?></p>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="<?= BASEURL; ?>/erapor/cetak?type=pdf&tahun=<?= $data['selected_tahun'] ?>&rombel=<?= $data['selected_rombel'] ?>&siswa=<?= $s['id'] ?>" target="_blank" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors text-sm font-medium inline-block">
                                <i class="fas fa-print mr-1"></i> Cetak
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
