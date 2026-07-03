<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Menampilkan hasil ujian untuk jadwal: <strong class="text-slate-800"><?= htmlspecialchars($data['jadwal']['nama_ujian']); ?></strong></p>
        </div>
        <a href="<?= BASEURL; ?>/JadwalUjian" class="inline-flex items-center px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg border border-slate-300 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Data Hasil Siswa
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Terkunci</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Mulai</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if(empty($data['hasil'])): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada siswa yang mendaftar atau memulai ujian ini.</td></tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($data['hasil'] as $row) : ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $i++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900"><?= htmlspecialchars($row['nama_lengkap']); ?></div>
                                <div class="text-xs text-slate-500 mt-0.5">NISN: <?= htmlspecialchars($row['nisn']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                    if($row['status_ujian'] == '0') echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Belum Mulai</span>';
                                    else if($row['status_ujian'] == '1') echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Mengerjakan</span>';
                                    else if($row['status_ujian'] == '2') echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Terkunci</span>';
                                    else if($row['status_ujian'] == '3') echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Selesai</span>';
                                ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($row['alasan_terkunci']): ?>
                                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-md block w-max max-w-[200px] truncate" title="<?= htmlspecialchars($row['alasan_terkunci']); ?>">
                                        <?= htmlspecialchars($row['alasan_terkunci']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400 text-sm">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                <?= $row['waktu_mulai'] ? date('d/m/Y H:i', strtotime($row['waktu_mulai'])) : '-'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <?php if($row['status_ujian'] == '3' || $row['nilai'] !== null): ?>
                                    <span class="text-lg font-black text-slate-900"><?= number_format((float)$row['nilai'], 2); ?></span>
                                <?php else: ?>
                                    <span class="text-sm font-medium text-slate-400">Menunggu</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
