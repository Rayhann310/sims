<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Laporan rekapitulasi kehadiran siswa. Mode sistem saat ini: <strong><?= $data['mode']; ?></strong></p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="<?= BASEURL; ?>/LaporanAbsen/rekapKelas" class="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-sm font-semibold rounded-lg transition-colors border border-indigo-200 inline-flex items-center shadow-sm">
                <i class="fas fa-table mr-2"></i> Rekap Per Kelas
            </a>
            <a href="<?= BASEURL; ?>/LaporanAbsen/eksporExcel?tgl_mulai=<?= $data['tgl_mulai'] ?>&tgl_sampai=<?= $data['tgl_sampai'] ?>&rombel_id=<?= $data['rombel_id'] ?>" target="_blank" class="px-4 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-sm font-semibold rounded-lg transition-colors border border-emerald-200 inline-flex items-center shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Ekspor Excel
            </a>
            <a href="<?= BASEURL; ?>/LaporanAbsen/cetakPdf?tgl_mulai=<?= $data['tgl_mulai'] ?>&tgl_sampai=<?= $data['tgl_sampai'] ?>&rombel_id=<?= $data['rombel_id'] ?>" target="_blank" class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 text-sm font-semibold rounded-lg transition-colors border border-rose-200 inline-flex items-center shadow-sm">
                <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>


    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="<?= BASEURL; ?>/LaporanAbsen" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" value="<?= $data['tgl_mulai'] ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm bg-slate-50" required>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Sampai</label>
                <input type="date" name="tgl_sampai" value="<?= $data['tgl_sampai'] ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm bg-slate-50" required>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Rombel / Kelas (Opsional)</label>
                <select name="rombel_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm bg-slate-50">
                    <option value="">Semua Rombel</option>
                    <?php foreach($data['rombels'] as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $data['rombel_id'] == $r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['nama_rombel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors shadow-sm text-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Grafik Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Summary Cards -->
        <div class="md:col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600"><i class="fas fa-check-circle text-xl"></i></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hadir</p><p class="text-2xl font-black text-slate-800"><?= $data['grafik']['Hadir'] ?? 0 ?></p></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600"><i class="fas fa-notes-medical text-xl"></i></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sakit</p><p class="text-2xl font-black text-slate-800"><?= $data['grafik']['Sakit'] ?? 0 ?></p></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><i class="fas fa-envelope-open-text text-xl"></i></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Izin</p><p class="text-2xl font-black text-slate-800"><?= $data['grafik']['Izin'] ?? 0 ?></p></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center text-red-600"><i class="fas fa-times-circle text-xl"></i></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alpa</p><p class="text-2xl font-black text-slate-800"><?= $data['grafik']['Alpa'] ?? 0 ?></p></div>
            </div>
        </div>

        <!-- Chart -->
        <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center items-center">
            <h3 class="text-sm font-bold text-slate-700 mb-4 w-full text-left">Statistik Kehadiran</h3>
            <div class="w-full h-64 flex justify-center">
                <canvas id="absensiChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600" id="absensiTable">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">Kelas</th>
                        <?php if($data['mode'] === 'Per Jam Pelajaran'): ?>
                        <th class="px-6 py-4">Jam Ke & Mapel</th>
                        <?php endif; ?>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Scan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no = 1; foreach($data['laporan'] as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4"><?= $no++; ?></td>
                        <td class="px-6 py-4 font-medium"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                            <div class="text-xs text-slate-400">NISN: <?= htmlspecialchars($row['nisn']) ?></div>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($row['kelas']) ?></td>
                        <?php if($data['mode'] === 'Per Jam Pelajaran'): ?>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 bg-slate-100 rounded text-xs font-semibold">Jam <?= $row['jam_ke'] ?></span>
                            <div class="text-xs mt-1 text-slate-500"><?= htmlspecialchars($row['nama_mapel']) ?></div>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-4">
                            <?php 
                            $statusColors = [
                                'Hadir' => 'bg-emerald-100 text-emerald-700',
                                'Sakit' => 'bg-amber-100 text-amber-700',
                                'Izin' => 'bg-blue-100 text-blue-700',
                                'Alpa' => 'bg-red-100 text-red-700'
                            ];
                            $colorClass = $statusColors[$row['status']] ?? 'bg-slate-100 text-slate-700';
                            ?>
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold <?= $colorClass ?>"><?= $row['status'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-xs"><?= $row['waktu_scan'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($data['laporan'])): ?>
                    <tr>
                        <td colspan="<?= $data['mode'] === 'Per Jam Pelajaran' ? 7 : 6 ?>" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
                            Tidak ada data absensi untuk rentang tanggal tersebut.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById("absensiTable") && document.querySelectorAll('#absensiTable tbody tr').length > 0 && !document.querySelector('.datatable-wrapper')) {
        new window.simpleDatatables.DataTable("#absensiTable", {
            searchable: true,
            fixedHeight: true,
            perPage: 10
        });
    }

    // Render Chart
    const ctx = document.getElementById('absensiChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                datasets: [{
                    data: [
                        <?= $data['grafik']['Hadir'] ?? 0 ?>,
                        <?= $data['grafik']['Sakit'] ?? 0 ?>,
                        <?= $data['grafik']['Izin'] ?? 0 ?>,
                        <?= $data['grafik']['Alpa'] ?? 0 ?>
                    ],
                    backgroundColor: [
                        '#10b981', // emerald-500
                        '#f59e0b', // amber-500
                        '#3b82f6', // blue-500
                        '#ef4444'  // red-500
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: { family: "'Inter', sans-serif", size: 12 }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
