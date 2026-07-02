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
                                    <!-- Tombol Detail -->
                                    <button type="button" 
                                        onclick="document.getElementById('detailModal<?= $p['id']; ?>').classList.remove('hidden'); document.getElementById('detailModal<?= $p['id']; ?>').classList.add('flex')"
                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg text-xs font-medium" title="Detail Siswa">
                                        Detail
                                    </button>

                                    <!-- Tombol Edit -->
                                    <button type="button" 
                                        onclick="document.getElementById('editModal<?= $p['id']; ?>').classList.remove('hidden'); document.getElementById('editModal<?= $p['id']; ?>').classList.add('flex')"
                                        class="text-amber-600 hover:text-amber-900 bg-amber-50 px-3 py-1.5 rounded-lg text-xs font-medium" title="Edit Data">
                                        Edit
                                    </button>

                                    <!-- Tombol Ubah Status -->
                                    <button type="button" 
                                        onclick="document.getElementById('statusModal<?= $p['id']; ?>').classList.remove('hidden'); document.getElementById('statusModal<?= $p['id']; ?>').classList.add('flex')"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-medium" title="Ubah Status">
                                        Seleksi
                                    </button>
                                    
                                    <!-- Tombol Migrasi (Jika Lulus) -->
                                    <?php if($p['status_seleksi'] == 'Lulus'): ?>
                                        <a href="<?= BASEURL; ?>/adminspmb/migrasiSiswa/<?= $p['id']; ?>" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 px-3 py-1.5 rounded-lg text-xs font-medium" onclick="return confirm('Jadikan peserta ini sebagai Siswa Aktif?');" title="Migrasi ke Siswa">
                                            Jadikan Siswa
                                        </a>
                                    <?php endif; ?>

                                    <!-- Tombol Hapus -->
                                    <a href="<?= BASEURL; ?>/adminspmb/hapusPeserta/<?= $p['id']; ?>" 
                                       class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg text-xs font-medium" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini secara permanen beserta akunnya?');" title="Hapus">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Status Modal -->
                        <div id="statusModal<?= $p['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-slate-900 bg-opacity-50">
                            <div class="relative p-4 w-full max-w-sm max-h-full">
                                <div class="relative bg-white rounded-xl shadow">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-gray-900">Ubah Status Seleksi</h3>
                                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onclick="document.getElementById('statusModal<?= $p['id']; ?>').classList.add('hidden'); document.getElementById('statusModal<?= $p['id']; ?>').classList.remove('flex')">
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

                        <!-- Detail Modal -->
                        <div id="detailModal<?= $p['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-slate-900 bg-opacity-50">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <div class="relative bg-white rounded-xl shadow">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-gray-900">Detail Peserta SPMB</h3>
                                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onclick="document.getElementById('detailModal<?= $p['id']; ?>').classList.add('hidden'); document.getElementById('detailModal<?= $p['id']; ?>').classList.remove('flex')">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                        </button>
                                    </div>
                                    <div class="p-4 md:p-5">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <h4 class="font-semibold text-slate-800 border-b pb-2 mb-3">Informasi Akun & Pendaftaran</h4>
                                                <ul class="text-sm space-y-2 text-slate-600">
                                                    <li><span class="font-medium text-slate-900">Username/NISN:</span> <?= htmlspecialchars($p['nisn']); ?></li>
                                                    <li><span class="font-medium text-slate-900">Gelombang:</span> <?= htmlspecialchars($p['nama_gelombang']); ?></li>
                                                    <li><span class="font-medium text-slate-900">Status Seleksi:</span> <?= htmlspecialchars($p['status_seleksi']); ?></li>
                                                    <li><span class="font-medium text-slate-900">Pembayaran:</span> <?= htmlspecialchars($p['status_pembayaran']); ?></li>
                                                    <li><span class="font-medium text-slate-900">Tanggal Daftar:</span> <?= date('d M Y H:i', strtotime($p['created_at'])); ?></li>
                                                </ul>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-slate-800 border-b pb-2 mb-3">Biodata Pribadi</h4>
                                                <ul class="text-sm space-y-2 text-slate-600">
                                                    <li><span class="font-medium text-slate-900">Nama Lengkap:</span> <?= htmlspecialchars($p['nama_lengkap']); ?></li>
                                                    <li><span class="font-medium text-slate-900">TTL:</span> <?= htmlspecialchars($p['tempat_lahir'] ?? '-'); ?>, <?= $p['tanggal_lahir'] ? date('d M Y', strtotime($p['tanggal_lahir'])) : '-'; ?></li>
                                                    <li><span class="font-medium text-slate-900">Asal Sekolah:</span> <?= htmlspecialchars($p['asal_sekolah']); ?></li>
                                                    <li><span class="font-medium text-slate-900">No. HP (WA):</span> <?= htmlspecialchars($p['no_hp']); ?></li>
                                                    <li><span class="font-medium text-slate-900">Alamat:</span> <?= htmlspecialchars($p['alamat_lengkap'] ?? '-'); ?></li>
                                                </ul>
                                            </div>
                                            <div class="md:col-span-2">
                                                <h4 class="font-semibold text-slate-800 border-b pb-2 mb-3 mt-2">Informasi Orang Tua</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <ul class="text-sm space-y-2 text-slate-600">
                                                        <li><span class="font-medium text-slate-900">Nama Ayah:</span> <?= htmlspecialchars($p['nama_ayah'] ?? '-'); ?></li>
                                                        <li><span class="font-medium text-slate-900">Nama Ibu:</span> <?= htmlspecialchars($p['nama_ibu'] ?? '-'); ?></li>
                                                    </ul>
                                                    <ul class="text-sm space-y-2 text-slate-600">
                                                        <li><span class="font-medium text-slate-900">Pekerjaan Ortu:</span> <?= htmlspecialchars($p['pekerjaan_ortu'] ?? '-'); ?></li>
                                                        <li><span class="font-medium text-slate-900">No. HP Ortu:</span> <?= htmlspecialchars($p['no_hp_ortu'] ?? '-'); ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div id="editModal<?= $p['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-slate-900 bg-opacity-50">
                            <div class="relative p-4 w-full max-w-lg max-h-full">
                                <div class="relative bg-white rounded-xl shadow">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-gray-900">Edit Data Peserta</h3>
                                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onclick="document.getElementById('editModal<?= $p['id']; ?>').classList.add('hidden'); document.getElementById('editModal<?= $p['id']; ?>').classList.remove('flex')">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                        </button>
                                    </div>
                                    <div class="p-4 md:p-5">
                                        <form class="space-y-4" action="<?= BASEURL; ?>/adminspmb/editPeserta" method="POST">
                                            <input type="hidden" name="id" value="<?= $p['id']; ?>">
                                            
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">NISN / Username</label>
                                                <input type="text" name="nisn" value="<?= htmlspecialchars($p['nisn']); ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                                            </div>
                                            
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                                                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($p['nama_lengkap']); ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                                            </div>
                                            
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">Asal Sekolah</label>
                                                <input type="text" name="asal_sekolah" value="<?= htmlspecialchars($p['asal_sekolah']); ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                                            </div>
                                            
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">No. HP / WA</label>
                                                <input type="text" name="no_hp" value="<?= htmlspecialchars($p['no_hp']); ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                                            </div>
                                            
                                            <button type="submit" class="w-full text-white bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
