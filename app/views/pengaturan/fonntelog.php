<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Debug API Fonnte</h2>
            <p class="text-slate-500 mt-1 text-sm">Log riwayat pengiriman notifikasi WhatsApp</p>
        </div>
        <a href="<?= BASEURL; ?>/pengaturan" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-sm">
                        <th class="px-6 py-4 font-semibold text-slate-700 whitespace-nowrap">Waktu</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 whitespace-nowrap">Nomor Tujuan</th>
                        <th class="px-6 py-4 font-semibold text-slate-700">Pesan</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 whitespace-nowrap">HTTP Code</th>
                        <th class="px-6 py-4 font-semibold text-slate-700">Response / Error</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if(empty($data['logs'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada log Fonnte (Tabel baru dibuat atau belum ada aktivitas kirim WA).</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($data['logs'] as $log): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap"><?= date('d M Y H:i:s', strtotime($log['tanggal'])); ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-700"><?= htmlspecialchars($log['nomor_tujuan']); ?></td>
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-pre-wrap max-w-xs break-words"><?= htmlspecialchars(substr($log['pesan'], 0, 100)) . '...'; ?></td>
                            <td class="px-6 py-4 text-sm text-center">
                                <?php if($log['response_code'] == 200): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">200 OK</span>
                                <?php elseif(!$log['response_code']): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">TIMEOUT/FAIL</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><?= $log['response_code']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-600 bg-slate-50 rounded p-2 max-w-md break-words border border-slate-100 m-2 inline-block">
                                <?= htmlspecialchars($log['response_body']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($log['status'] == 'Sukses'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800"><i class="fas fa-check-circle mr-1"></i> Sukses</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i> Gagal</span>
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
