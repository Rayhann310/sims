<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Dashboard Peserta SPMB</h1>
            <p class="mt-2 text-sm text-slate-600">Selamat datang, <?= htmlspecialchars($data['peserta']['nama_lengkap']); ?>. Silakan selesaikan tahapan pendaftaran Anda.</p>
        </div>

        <?php Flasher::flash(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            
            <!-- Profil Singkat -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 lg:col-span-1">
                <div class="text-center mb-6">
                    <div class="inline-block p-4 rounded-full bg-emerald-100 text-emerald-600 mb-3">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($data['peserta']['nama_lengkap']); ?></h3>
                    <p class="text-sm text-slate-500">NISN: <?= htmlspecialchars($data['peserta']['nisn']); ?></p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Gelombang</span>
                        <span class="text-sm font-medium text-slate-900"><?= htmlspecialchars($data['peserta']['nama_gelombang']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Asal Sekolah</span>
                        <span class="text-sm font-medium text-slate-900"><?= htmlspecialchars($data['peserta']['asal_sekolah']); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Status Seleksi</span>
                        <?php if($data['peserta']['status_seleksi'] == 'Lulus'): ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lulus</span>
                        <?php elseif($data['peserta']['status_seleksi'] == 'Tidak Lulus'): ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Lulus</span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Pembayaran</span>
                        <?php if($data['peserta']['status_pembayaran'] == 'Lunas'): ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Lunas</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Form -->
            <div class="lg:col-span-2 space-y-8">
                
                <?php if($data['peserta']['status_pembayaran'] != 'Lunas'): ?>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Pembayaran Biaya Pendaftaran
                    </h3>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-yellow-800 mb-2">Silakan transfer biaya pendaftaran formulir sebesar:</p>
                        <p class="text-2xl font-bold text-yellow-900">Rp <?= number_format($data['peserta']['harga_formulir'], 0, ',', '.'); ?></p>
                        <p class="text-sm text-yellow-800 mt-2">Ke Rekening Bank BSI: <strong>1234567890</strong> a.n. SMA NW Jakarta</p>
                    </div>

                    <form action="<?= BASEURL; ?>/spmb/bayar" method="POST" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Metode Transfer</label>
                                <select name="metode" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="Transfer BSI">Transfer Bank BSI</option>
                                    <option value="Transfer Bank Lain">Transfer Bank Lain</option>
                                    <option value="E-Wallet">E-Wallet (OVO/Dana/Gopay)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Upload Bukti Transfer</label>
                                <input type="file" name="bukti_bayar" accept="image/*" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                            </div>
                        </div>
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
                            Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
                <?php else: ?>
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 flex items-center gap-4">
                    <div class="bg-emerald-100 p-3 rounded-full text-emerald-600 shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-emerald-900">Pembayaran Berhasil Diverifikasi!</h3>
                        <p class="text-emerald-700 text-sm mt-1">Status pembayaran Anda LUNAS. Mohon menunggu proses seleksi oleh panitia.</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Riwayat Pembayaran -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-bold text-slate-800">Riwayat Pengajuan Pembayaran</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Tanggal</th>
                                    <th class="px-6 py-3 font-semibold">Metode</th>
                                    <th class="px-6 py-3 font-semibold">Jumlah</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if(empty($data['pembayaran'])): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-slate-500 italic">Belum ada riwayat pembayaran</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($data['pembayaran'] as $b): ?>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4"><?= date('d/m/Y H:i', strtotime($b['tanggal_bayar'])); ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($b['metode']); ?></td>
                                        <td class="px-6 py-4">Rp <?= number_format($b['jumlah_bayar'], 0, ',', '.'); ?></td>
                                        <td class="px-6 py-4">
                                            <?php if($b['status'] == 'Diterima'): ?>
                                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-medium">Diterima</span>
                                            <?php elseif($b['status'] == 'Ditolak'): ?>
                                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 font-medium">Ditolak</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800 font-medium">Pending</span>
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
        </div>
    </div>
</div>
