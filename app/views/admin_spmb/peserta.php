<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Peserta SPMB</h2>
            <p class="text-slate-500 mt-1">Verifikasi pembayaran dan ubah status seleksi calon siswa.</p>
        </div>

        <?php Flasher::flash(); ?>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4">No</th>
                            <th scope="col" class="px-6 py-4">NISN / Akun</th>
                            <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-4">Gelombang</th>
                            <th scope="col" class="px-6 py-4">Pembayaran</th>
                            <th scope="col" class="px-6 py-4">Status Seleksi</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach($data['peserta'] as $p): ?>
                        <tr class="bg-white border-b hover:bg-slate-50">
                            <td class="px-6 py-4"><?= $i++; ?></td>
                            <td class="px-6 py-4 font-medium text-slate-900">
                                <?= htmlspecialchars($p['nisn']); ?><br>
                                <span class="text-xs text-slate-500 font-normal">No. HP: <?= htmlspecialchars($p['no_hp']); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($p['nama_lengkap']); ?><br>
                                <span class="text-xs text-slate-500">Asal: <?= htmlspecialchars($p['asal_sekolah']); ?></span>
                            </td>
                            <td class="px-6 py-4"><?= htmlspecialchars($p['nama_gelombang']); ?></td>
                            <td class="px-6 py-4">
                                <?php if($p['status_pembayaran'] == 'Lunas'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Lunas</span>
                                <?php else: ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Belum Bayar</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($p['status_seleksi'] == 'Lulus'): ?>
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded border border-emerald-400">Lulus</span>
                                <?php elseif($p['status_seleksi'] == 'Tidak Lulus'): ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Tidak Lulus</span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-400">Menunggu</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Ubah Status -->
                                    <button type="button" 
                                        data-modal-target="statusModal<?= $p['id']; ?>" 
                                        data-modal-toggle="statusModal<?= $p['id']; ?>"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-medium" title="Ubah Status">
                                        Seleksi
                                    </button>
                                    
                                    <!-- Tombol Migrasi (Jika Lulus) -->
                                    <?php if($p['status_seleksi'] == 'Lulus'): ?>
                                        <a href="<?= BASEURL; ?>/adminspmb/migrasiSiswa/<?= $p['id']; ?>" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 px-3 py-1.5 rounded-lg text-xs font-medium" onclick="return confirm('Jadikan peserta ini sebagai Siswa Aktif?');" title="Migrasi ke Siswa">
                                            Jadikan Siswa
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <!-- Status Modal -->
                        <div id="statusModal<?= $p['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-sm max-h-full">
                                <div class="relative bg-white rounded-xl shadow">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-gray-900">Ubah Status Seleksi</h3>
                                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="statusModal<?= $p['id']; ?>">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                        </button>
                                    </div>
                                    <div class="p-4 md:p-5">
                                        <form class="space-y-4" action="<?= BASEURL; ?>/adminspmb/ubahStatusSeleksi" method="POST">
                                            <input type="hidden" name="id" value="<?= $p['id']; ?>">
                                            <p class="text-sm text-gray-600 mb-4">Peserta: <strong><?= htmlspecialchars($p['nama_lengkap']); ?></strong></p>
                                            
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">Status Seleksi</label>
                                                <select name="status_seleksi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                                                    <option value="Menunggu" <?= $p['status_seleksi'] == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                                    <option value="Lulus" <?= $p['status_seleksi'] == 'Lulus' ? 'selected' : ''; ?>>Lulus</option>
                                                    <option value="Tidak Lulus" <?= $p['status_seleksi'] == 'Tidak Lulus' ? 'selected' : ''; ?>>Tidak Lulus</option>
                                                </select>
                                            </div>
                                            
                                            <?php if($p['status_pembayaran'] != 'Lunas'): ?>
                                                <div class="p-3 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                                                  <span class="font-medium">Perhatian!</span> Peserta ini belum melunasi biaya pendaftaran.
                                                </div>
                                            <?php endif; ?>

                                            <button type="submit" class="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Status</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                        
                        <?php if(empty($data['peserta'])): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">Belum ada data pendaftar.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
