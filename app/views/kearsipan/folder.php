<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="{ viewMode: 'grid' }">
    <!-- Page Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div>
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?= BASEURL; ?>/kearsipan" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                Kearsipan
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="ml-1 text-sm font-medium text-slate-700 md:ml-2"><?= htmlspecialchars($data['kategori_aktif']['nama_kategori']); ?></span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">
                <?= htmlspecialchars($data['kategori_aktif']['nama_kategori']); ?>
            </h1>
        </div>
        
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2 mt-4 sm:mt-0">
            <!-- Toggle View Mode -->
            <div class="flex bg-slate-100 p-1 rounded-md border border-slate-200 mr-2">
                <button @click="viewMode = 'grid'" :class="{'bg-white shadow-sm text-indigo-500': viewMode === 'grid', 'text-slate-500 hover:text-slate-600': viewMode !== 'grid'}" class="p-1.5 rounded transition-all" title="Grid View">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </button>
                <button @click="viewMode = 'list'" :class="{'bg-white shadow-sm text-indigo-500': viewMode === 'list', 'text-slate-500 hover:text-slate-600': viewMode !== 'list'}" class="p-1.5 rounded transition-all" title="List View">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </button>
            </div>
            
            <button onclick="document.getElementById('tambahModal').classList.remove('hidden')" class="btn bg-primary hover:bg-indigo-600 text-white">
                <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16"><path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/></svg>
                <span class="hidden xs:block ml-2">Upload File Disini</span>
            </button>
        </div>
    </div>

    <!-- Flasher -->
    <div class="mb-4">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Files Section -->
    <div class="mb-8">
        <?php if(empty($data['surat'])): ?>
            <div class="bg-white border border-slate-200 border-dashed rounded-lg p-6 text-center text-slate-500">
                Folder ini masih kosong. Klik tombol "Upload File Disini" untuk menambahkan dokumen.
            </div>
        <?php else: ?>
            
            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach($data['surat'] as $surat): ?>
                <div class="bg-white border border-slate-200 hover:border-slate-300 shadow-sm hover:shadow-md rounded-xl p-4 transition-all flex flex-col group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                            <?php if($surat['jenis_surat'] == 'Masuk'): ?>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <?php else: ?>
                                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <?php endif; ?>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity space-x-1">
                            <?php if ($surat['file_surat']) : ?>
                                <a href="<?= BASEURL; ?>/public/uploads/kearsipan/<?= $surat['file_surat']; ?>" target="_blank" class="text-indigo-500 hover:text-indigo-700" title="Buka File"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                            <?php endif; ?>
                            <a href="<?= BASEURL; ?>/kearsipan/hapus/<?= $surat['id']; ?>" onclick="return confirm('Hapus dokumen ini?');" class="text-rose-500 hover:text-rose-700" title="Hapus"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></a>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h4 class="font-medium text-slate-800 line-clamp-2" title="<?= $surat['perihal']; ?>"><?= $surat['perihal']; ?></h4>
                        <p class="text-xs text-slate-500 mt-1"><?= $surat['nomor_surat']; ?></p>
                        <p class="text-xs text-slate-400 mt-1"><?= date('d M Y', strtotime($surat['tanggal_surat'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- List View (Table) -->
            <div x-show="viewMode === 'list'" style="display: none;" class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap"><div class="font-semibold text-left">Nomor Surat</div></th>
                            <th class="px-4 py-3 whitespace-nowrap"><div class="font-semibold text-left">Tanggal</div></th>
                            <th class="px-4 py-3 whitespace-nowrap"><div class="font-semibold text-left">Jenis</div></th>
                            <th class="px-4 py-3 whitespace-nowrap"><div class="font-semibold text-left">Pengirim/Penerima</div></th>
                            <th class="px-4 py-3 whitespace-nowrap"><div class="font-semibold text-left">Perihal</div></th>
                            <th class="px-4 py-3 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-200">
                        <?php foreach($data['surat'] as $surat): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-slate-800 font-medium"><?= $surat['nomor_surat']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-500"><?= date('d/m/Y', strtotime($surat['tanggal_surat'])); ?></td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <?php if ($surat['jenis_surat'] == 'Masuk') : ?>
                                    <span class="inline-flex text-xs font-medium bg-emerald-100 text-emerald-600 rounded-full px-2 py-0.5">Masuk</span>
                                <?php else : ?>
                                    <span class="inline-flex text-xs font-medium bg-rose-100 text-rose-600 rounded-full px-2 py-0.5">Keluar</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-800"><?= $surat['pengirim_penerima']; ?></td>
                            <td class="px-4 py-3"><div class="text-slate-600 max-w-xs truncate" title="<?= $surat['perihal']; ?>"><?= $surat['perihal']; ?></div></td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <?php if ($surat['file_surat']) : ?>
                                        <a href="<?= BASEURL; ?>/public/uploads/kearsipan/<?= $surat['file_surat']; ?>" target="_blank" class="text-indigo-500 hover:text-indigo-600" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= BASEURL; ?>/kearsipan/hapus/<?= $surat['id']; ?>" class="text-rose-500 hover:text-rose-600" onclick="return confirm('Hapus?');">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Surat/Dokumen ke Folder Ini -->
<div id="tambahModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('tambahModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="<?= BASEURL; ?>/kearsipan/tambah" method="POST" enctype="multipart/form-data">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4" id="modal-title">Upload Dokumen Kearsipan</h3>
                            
                            <input type="hidden" name="kategori_id" value="<?= $data['kategori_aktif']['id']; ?>">
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Surat <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nomor_surat" class="form-input w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tanggal_surat" class="form-input w-full" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis <span class="text-rose-500">*</span></label>
                                        <select name="jenis_surat" class="form-select w-full" required>
                                            <option value="">Pilih Jenis</option>
                                            <option value="Masuk">Surat Masuk</option>
                                            <option value="Keluar">Surat Keluar</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Folder Tujuan</label>
                                        <input type="text" class="form-input w-full bg-slate-100 text-slate-500 cursor-not-allowed" value="<?= htmlspecialchars($data['kategori_aktif']['nama_kategori']); ?>" disabled>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Pengirim / Penerima <span class="text-rose-500">*</span></label>
                                    <input type="text" name="pengirim_penerima" class="form-input w-full" placeholder="Cth: Dinas Pendidikan / Orang Tua Siswa" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Perihal <span class="text-rose-500">*</span></label>
                                    <textarea name="perihal" class="form-textarea w-full" rows="2" required></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan Tambahan</label>
                                    <textarea name="keterangan" class="form-textarea w-full" rows="2"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Upload Berkas (PDF/JPG/PNG)</label>
                                    <input type="file" name="file_surat" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Upload & Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('tambahModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
