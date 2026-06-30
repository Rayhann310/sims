<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeAccordion: null }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Daftar histori transaksi pembayaran SPP oleh siswa.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="<?= BASEURL; ?>/keuangan/riwayat" method="GET" class="flex items-center">
                <label for="tahun" class="mr-2 text-sm font-medium text-slate-700">Tahun:</label>
                <select name="tahun" id="tahun" onchange="this.form.submit()" class="pl-3 pr-8 py-2 text-sm border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm border">
                    <?php if(empty($data['tahun_tersedia'])): ?>
                        <option value="<?= date('Y'); ?>"><?= date('Y'); ?></option>
                    <?php else: ?>
                        <?php foreach($data['tahun_tersedia'] as $thn): ?>
                            <option value="<?= $thn; ?>" <?= ($data['tahun_aktif'] == $thn) ? 'selected' : ''; ?>><?= $thn; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </form>
            <a href="<?= BASEURL; ?>/keuangan" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <?php if(empty($data['riwayat_siswa'])): ?>
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-slate-500 font-medium text-lg">Belum ada data pembayaran</p>
                <p class="text-slate-400 text-sm">Tidak ada transaksi pembayaran pada tahun <?= htmlspecialchars($data['tahun_aktif']); ?>.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php foreach($data['riwayat_siswa'] as $index => $siswa): ?>
                <div class="accordion-item">
                    <!-- Accordion Header -->
                    <button @click="activeAccordion = activeAccordion === <?= $index ?> ? null : <?= $index ?>" 
                            class="w-full flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors focus:outline-none focus:bg-slate-50">
                        <div class="flex items-center gap-4 text-left">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm shrink-0">
                                <?= substr($siswa['nama_lengkap'], 0, 1); ?>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($siswa['nama_lengkap']); ?></h3>
                                <p class="text-xs text-slate-500">NISN: <?= htmlspecialchars($siswa['nisn']); ?> &bull; Total Bayar: <span class="font-medium text-emerald-600">Rp <?= number_format($siswa['total_pembayaran'], 0, ',', '.'); ?></span></p>
                            </div>
                        </div>
                        <div class="text-slate-400 transition-transform duration-200" :class="activeAccordion === <?= $index ?> ? 'rotate-180' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>
                    
                    <!-- Accordion Body (Collapsible Panel) -->
                    <div x-show="activeAccordion === <?= $index ?>" 
                         x-collapse 
                         x-cloak
                         class="bg-slate-50 border-t border-slate-100">
                        <div class="px-6 py-4">
                            <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left font-semibold text-slate-600">Tgl Pembayaran</th>
                                            <th class="px-4 py-2 text-left font-semibold text-slate-600">Untuk Tagihan</th>
                                            <th class="px-4 py-2 text-left font-semibold text-slate-600">Nominal</th>
                                            <th class="px-4 py-2 text-left font-semibold text-slate-600">Metode / Ket.</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php foreach($siswa['pembayaran'] as $p): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-slate-600">
                                                <?= date('d M Y', strtotime($p['tanggal_bayar'])); ?><br>
                                                <span class="text-xs text-slate-400"><?= date('H:i', strtotime($p['created_at'])); ?></span>
                                            </td>
                                            <td class="px-4 py-3 text-slate-700 font-medium">
                                                Bulan <?= $p['bulan']; ?> <?= $p['tahun']; ?>
                                            </td>
                                            <td class="px-4 py-3 text-emerald-600 font-bold">
                                                Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.'); ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $p['metode']=='Cash' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' ?>">
                                                    <?= htmlspecialchars($p['metode']); ?>
                                                </span>
                                                <?php if($p['keterangan']): ?>
                                                    <div class="text-xs text-slate-500 mt-1"><?= htmlspecialchars($p['keterangan']); ?></div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

