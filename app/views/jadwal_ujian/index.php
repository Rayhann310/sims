<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Kelola jadwal ujian dan plotting pengawas di sini.</p>
        </div>
        <a href="<?= BASEURL; ?>/JadwalUjian/tambah" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jadwal
        </a>
    </div>

    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Pelaksanaan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Durasi & Pengawas</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if(empty($data['jadwal'])): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada jadwal ujian.</td></tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($data['jadwal'] as $row): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $i++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900"><?= $row['nama_ujian']; ?></div>
                                <div class="text-xs text-slate-500 mt-1">Mapel: <?= $row['id_mapel']; ?> (Bisa di-join nanti)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">Mulai: <span class="font-medium"><?= date('d/m/Y H:i', strtotime($row['waktu_mulai'])); ?></span></div>
                                <div class="text-sm text-slate-900 mt-1">Selesai: <span class="font-medium"><?= date('d/m/Y H:i', strtotime($row['waktu_selesai'])); ?></span></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?= $row['durasi_menit']; ?> Menit</div>
                                <div class="text-xs font-semibold text-indigo-600 mt-1">Pengawas: <?= $row['nama_pengawas'] ?? 'Tidak ada'; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?php if($row['status'] == 'Aktif'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>
                                <?php elseif($row['status'] == 'Draft'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Draft</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                <a href="<?= BASEURL; ?>/JadwalUjian/kelolaSoal/<?= $row['id_jadwal']; ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors">Kelola Soal</a>
                                <a href="<?= BASEURL; ?>/JadwalUjian/hapus/<?= $row['id_jadwal']; ?>" 
                                   class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors"
                                   onclick="return confirm('Yakin ingin menghapus jadwal ini?');">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
