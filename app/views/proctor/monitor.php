<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Monitor aktivitas peserta di ruangan ujian ini secara real-time.</p>
        </div>
        <a href="<?= BASEURL; ?>/Proctor" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-amber-100 border-b border-amber-200 px-6 py-4 flex items-center">
            <i class="fas fa-users text-amber-600 text-xl mr-3"></i>
            <h3 class="text-amber-800 font-bold">Monitor Peserta <span class="font-normal text-amber-700">(Refresh halaman untuk update status terbaru)</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan Sistem</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if(empty($data['peserta'])): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada peserta yang tergabung di ruangan ini.</td></tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($data['peserta'] as $p): ?>
                        <tr class="hover:bg-slate-50 <?= ($p['status_ujian'] == '2') ? 'bg-rose-50/50' : '' ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $i++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900"><?= $p['nama_lengkap']; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $p['nisn']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?php if($p['status_ujian'] == '1'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Sedang Mengerjakan</span>
                                <?php elseif($p['status_ujian'] == '2'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 animate-pulse">Terkunci</span>
                                <?php elseif($p['status_ujian'] == '3'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Selesai</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Belum Mulai</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if($p['status_ujian'] == '2'): ?>
                                    <span class="text-rose-600 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i> <?= $p['alasan_terkunci']; ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <?php if($p['status_ujian'] == '2'): ?>
                                    <a href="<?= BASEURL; ?>/Proctor/unlockSiswa/<?= $p['id_peserta']; ?>/<?= $data['id_jadwal']; ?>" 
                                       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-md transition-colors"
                                       onclick="return confirm('Yakin ingin membuka akses ujian siswa ini?');">
                                       <i class="fas fa-unlock mr-1"></i> Buka Kunci
                                    </a>
                                <?php else: ?>
                                    <button class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-medium rounded-md cursor-not-allowed" disabled>
                                        <i class="fas fa-lock mr-1"></i> Buka Kunci
                                    </button>
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
