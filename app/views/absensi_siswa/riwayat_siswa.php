<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-2">
        <h2 class="text-2xl font-bold text-slate-800">Riwayat Kehadiran Saya</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <?php if(empty($data['riwayat'])): ?>
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-slate-500 text-sm">Belum ada riwayat kehadiran yang tercatat.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 font-medium">Tanggal</th>
                            <?php if($data['mode'] !== 'Normal'): ?>
                                <th class="px-4 py-3 font-medium">Jam Ke</th>
                                <th class="px-4 py-3 font-medium">Mapel</th>
                            <?php endif; ?>
                            <th class="px-4 py-3 font-medium">Waktu Scan</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach($data['riwayat'] as $r): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-medium text-slate-800"><?= date('d M Y', strtotime($r['tanggal'])) ?></span>
                            </td>
                            <?php if($data['mode'] !== 'Normal'): ?>
                                <td class="px-4 py-3"><?= $r['jam_ke'] ?></td>
                                <td class="px-4 py-3"><?= $r['nama_mapel'] ?? '-' ?></td>
                            <?php endif; ?>
                            <td class="px-4 py-3 whitespace-nowrap"><?= $r['waktu_scan'] ?></td>
                            <td class="px-4 py-3">
                                <?php 
                                    $statusColor = 'bg-slate-100 text-slate-700';
                                    if ($r['status'] === 'Hadir') $statusColor = 'bg-emerald-100 text-emerald-700';
                                    if ($r['status'] === 'Sakit') $statusColor = 'bg-blue-100 text-blue-700';
                                    if ($r['status'] === 'Izin') $statusColor = 'bg-amber-100 text-amber-700';
                                    if ($r['status'] === 'Alpa') $statusColor = 'bg-red-100 text-red-700';
                                ?>
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full <?= $statusColor ?>">
                                    <?= $r['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-xs text-slate-500 italic">
                * Menampilkan 50 riwayat terakhir
            </div>
        <?php endif; ?>
    </div>
</div>
