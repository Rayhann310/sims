        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Gelombang SPMB</h2>
            <button type="button" data-modal-target="tambahModal" data-modal-toggle="tambahModal" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Gelombang
            </button>
        </div>

        <?php Flasher::flash(); ?>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4">No</th>
                            <th scope="col" class="px-6 py-4">Nama Gelombang</th>
                            <th scope="col" class="px-6 py-4">Periode</th>
                            <th scope="col" class="px-6 py-4">Harga Formulir</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach($data['gelombang'] as $g): ?>
                        <tr class="bg-white border-b hover:bg-slate-50">
                            <td class="px-6 py-4"><?= $i++; ?></td>
                            <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($g['nama_gelombang']); ?></td>
                            <td class="px-6 py-4">
                                <?= date('d/m/Y', strtotime($g['tanggal_mulai'])); ?> - <?= date('d/m/Y', strtotime($g['tanggal_selesai'])); ?>
                            </td>
                            <td class="px-6 py-4 text-emerald-600 font-semibold">Rp <?= number_format($g['harga_formulir'], 0, ',', '.'); ?></td>
                            <td class="px-6 py-4">
                                <?php if($g['status'] == 'Buka'): ?>
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded border border-emerald-400">Buka</span>
                                <?php else: ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Tutup</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <button type="button" 
                                    data-modal-target="editModal<?= $g['id']; ?>" 
                                    data-modal-toggle="editModal<?= $g['id']; ?>"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <?php if($g['status'] == 'Tutup'): ?>
                                    <a href="<?= BASEURL; ?>/adminspmb/migrasiMassal/<?= $g['id']; ?>" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 p-2 rounded-lg" onclick="return confirm('Migrasi semua peserta yang LULUS di gelombang ini ke Data Siswa?');" title="Migrasi Massal Lulus -> Siswa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= BASEURL; ?>/adminspmb/hapusGelombang/<?= $g['id']; ?>" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg" onclick="return confirm('Yakin ingin menghapus gelombang ini? Data peserta di gelombang ini bisa terdampak.');" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div id="editModal<?= $g['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-xl shadow">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-gray-900">Ubah Gelombang SPMB</h3>
                                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="editModal<?= $g['id']; ?>">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                            <span class="sr-only">Tutup modal</span>
                                        </button>
                                    </div>
                                    <div class="p-4 md:p-5">
                                        <form class="space-y-4" action="<?= BASEURL; ?>/adminspmb/ubahGelombang" method="POST">
                                            <input type="hidden" name="id" value="<?= $g['id']; ?>">
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">Nama Gelombang</label>
                                                <input type="text" name="nama_gelombang" value="<?= htmlspecialchars($g['nama_gelombang']); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" value="<?= $g['tanggal_mulai']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                                                </div>
                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai" value="<?= $g['tanggal_selesai']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">Harga Formulir (Rp)</label>
                                                <input type="number" name="harga_formulir" value="<?= $g['harga_formulir']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                                            </div>
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                                <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                                                    <option value="Buka" <?= $g['status'] == 'Buka' ? 'selected' : ''; ?>>Buka</option>
                                                    <option value="Tutup" <?= $g['status'] == 'Tutup' ? 'selected' : ''; ?>>Tutup</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Perubahan</button>
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
<!-- Tambah Modal -->
<div id="tambahModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-xl shadow">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">Tambah Gelombang SPMB</h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="tambahModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <form class="space-y-4" action="<?= BASEURL; ?>/adminspmb/tambahGelombang" method="POST">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nama Gelombang</label>
                        <input type="text" name="nama_gelombang" placeholder="Contoh: Gelombang 1 Tahun 2026" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Harga Formulir (Rp)</label>
                        <input type="number" name="harga_formulir" placeholder="Contoh: 150000" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Status Awal</label>
                        <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                            <option value="Buka">Buka</option>
                            <option value="Tutup" selected>Tutup</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Gelombang</button>
                </form>
            </div>
        </div>
    </div>
</div>
