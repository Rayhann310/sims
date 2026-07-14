<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">
                Akumulasi kehadiran siswa dalam satu kelas — Mode: <strong><?= $data['mode']; ?></strong>
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="<?= BASEURL; ?>/LaporanAbsen" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors border border-slate-200 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <?php if ($data['rombel_id']): ?>
            <a href="<?= BASEURL; ?>/LaporanAbsen/cetakRekapKelas?rombel_id=<?= $data['rombel_id'] ?>&mode_filter=<?= $data['mode_filter'] ?>&bulan=<?= $data['bulan'] ?>" target="_blank"
               class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-sm font-semibold rounded-lg transition-colors border border-rose-200 inline-flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="<?= BASEURL; ?>/LaporanAbsen/rekapKelas" class="space-y-4">
            <!-- Mode Filter Toggle -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Rentang Periode</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="mode_filter" value="bulan"
                               <?= $data['mode_filter'] === 'bulan' ? 'checked' : '' ?>
                               class="text-emerald-600" onchange="this.form.submit()">
                        <span class="text-sm font-medium text-slate-700">Per Bulan</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="mode_filter" value="semester"
                               <?= $data['mode_filter'] === 'semester' ? 'checked' : '' ?>
                               class="text-emerald-600" onchange="this.form.submit()">
                        <span class="text-sm font-medium text-slate-700">Satu Semester (<?= htmlspecialchars($data['semester']['label']) ?>)</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 items-end">
                <?php if ($data['mode_filter'] === 'bulan'): ?>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Bulan</label>
                    <input type="month" name="bulan" value="<?= $data['bulan'] ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm bg-slate-50">
                </div>
                <?php else: ?>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Periode</label>
                    <input type="text" value="<?= htmlspecialchars($data['semester']['mulai']) ?> s/d <?= htmlspecialchars($data['semester']['sampai']) ?>"
                           class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-100 text-slate-500" readonly>
                </div>
                <?php endif; ?>

                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas / Rombel <span class="text-red-500">*</span></label>
                    <select name="rombel_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm bg-slate-50">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach($data['rombels'] as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= $data['rombel_id'] == $r['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['nama_rombel']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors shadow-sm text-sm">
                        <i class="fas fa-filter mr-1"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($data['rombel_id']): ?>
    <!-- Info Periode -->
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-3 mb-5 flex items-center gap-3">
        <i class="fas fa-calendar-alt text-emerald-600"></i>
        <span class="text-sm text-emerald-800 font-medium">
            Menampilkan rekap <strong><?= htmlspecialchars($data['rombel_nama']) ?></strong>
            — Periode: <strong><?= htmlspecialchars($data['label_periode']) ?></strong>
            (<?= $data['tgl_mulai'] ?> s/d <?= $data['tgl_sampai'] ?>)
        </span>
    </div>

    <!-- Tabel Akumulasi -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600" id="rekapTable">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4">Nama Siswa</th>
                        <th class="px-5 py-4">NISN</th>
                        <th class="px-5 py-4 text-center text-emerald-700">Hadir</th>
                        <th class="px-5 py-4 text-center text-amber-700">Sakit</th>
                        <th class="px-5 py-4 text-center text-blue-700">Izin</th>
                        <th class="px-5 py-4 text-center text-red-700">Alpa</th>
                        <th class="px-5 py-4 text-center">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no = 1; foreach ($data['rekap'] as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3 text-center text-slate-400"><?= $no++ ?></td>
                        <td class="px-5 py-3 font-semibold text-slate-800"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td class="px-5 py-3 text-xs text-slate-500"><?= htmlspecialchars($row['nisn']) ?></td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 font-bold text-sm"><?= $row['hadir'] ?></span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 font-bold text-sm"><?= $row['sakit'] ?></span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-bold text-sm"><?= $row['izin'] ?></span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-md <?= $row['alpa'] > 0 ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-400' ?> font-bold text-sm"><?= $row['alpa'] ?></span>
                        </td>
                        <td class="px-5 py-3 text-center font-bold text-slate-700"><?= $row['total'] ?></td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($data['rekap'])): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                            <i class="fas fa-folder-open text-4xl mb-3 block text-slate-300"></i>
                            Tidak ada data absensi untuk periode dan kelas ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>

                <?php if (!empty($data['rekap'])): ?>
                <tfoot class="bg-slate-100 border-t-2 border-slate-300 text-sm font-bold text-slate-700">
                    <tr>
                        <td colspan="3" class="px-5 py-3 text-right">TOTAL KESELURUHAN</td>
                        <td class="px-5 py-3 text-center text-emerald-700"><?= array_sum(array_column($data['rekap'], 'hadir')) ?></td>
                        <td class="px-5 py-3 text-center text-amber-700"><?= array_sum(array_column($data['rekap'], 'sakit')) ?></td>
                        <td class="px-5 py-3 text-center text-blue-700"><?= array_sum(array_column($data['rekap'], 'izin')) ?></td>
                        <td class="px-5 py-3 text-center text-red-700"><?= array_sum(array_column($data['rekap'], 'alpa')) ?></td>
                        <td class="px-5 py-3 text-center"><?= array_sum(array_column($data['rekap'], 'total')) ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <?php else: ?>
    <!-- Prompt pilih kelas -->
    <div class="text-center py-16 bg-white rounded-xl border border-dashed border-slate-300 text-slate-400">
        <i class="fas fa-chalkboard-teacher text-5xl mb-4 block text-slate-300"></i>
        <p class="font-medium">Pilih kelas dan periode untuk menampilkan rekap absensi.</p>
    </div>
    <?php endif; ?>
</div>
