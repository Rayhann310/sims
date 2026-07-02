<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="{ viewMode: 'grid' }">
    <!-- Page Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                Kearsipan & Tata Usaha
            </h1>
            <p class="text-slate-500 mt-1">Manajemen data surat masuk, surat keluar, dan dokumen sekolah layaknya Google Drive.</p>
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
            
            <button onclick="document.getElementById('modalFolder').classList.remove('hidden')" class="btn bg-white border-slate-200 hover:border-slate-300 text-slate-600 flex items-center px-3 py-2 shadow-sm rounded-lg transition-colors">
                <svg class="w-4 h-4 fill-current text-slate-500 shrink-0" viewBox="0 0 16 16"><path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/></svg>
                <span class="ml-2 font-medium text-sm">Folder Baru</span>
            </button>
            <button onclick="document.getElementById('tambahModal').classList.remove('hidden')" class="btn bg-primary hover:bg-indigo-600 text-white flex items-center px-3 py-2 shadow-sm rounded-lg transition-colors">
                <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16"><path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/></svg>
                <span class="ml-2 font-medium text-sm">Upload File</span>
            </button>
        </div>
    </div>

    <!-- Flasher -->
    <div class="mb-4">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Folders Section -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
            Folders
        </h2>
        
        <?php if(empty($data['kategori'])): ?>
            <div class="bg-white border border-slate-200 border-dashed rounded-lg p-6 text-center text-slate-500">
                Belum ada folder. Silakan klik "Folder Baru" untuk mulai mengelompokkan dokumen.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach($data['kategori'] as $kat): ?>
                <!-- Card Folder (GDrive style) -->
                <div class="bg-white border border-slate-200 hover:border-indigo-400 hover:shadow-md rounded-xl p-4 cursor-pointer transition-all group flex items-center justify-between"
                     ondblclick="window.location.href='<?= BASEURL; ?>/kearsipan/folder/<?= $kat['id']; ?>'">
                    <div class="flex items-center gap-3 w-full">
                        <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                        <div class="truncate w-full">
                            <h3 class="font-medium text-slate-800 group-hover:text-indigo-600 truncate"><?= htmlspecialchars($kat['nama_kategori']); ?></h3>
                        </div>
                    </div>
                    <!-- Actions Menu -->
                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-1">
                        <button type="button" onclick="openEditFolder(<?= $kat['id']; ?>, '<?= htmlspecialchars(addslashes($kat['nama_kategori'])); ?>'); event.stopPropagation(); return false;" class="text-indigo-400 hover:text-indigo-600 p-1" title="Edit Folder">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <a href="<?= BASEURL; ?>/kearsipan/hapuskategori/<?= $kat['id']; ?>" onclick="event.stopPropagation(); return confirm('Hapus folder ini? File di dalamnya akan menjadi Tanpa Kategori.');" class="text-rose-400 hover:text-rose-600 p-1" title="Hapus Folder">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Uncategorized Files Section -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Files (Tidak Berfolder)</h2>
        
        <?php if(empty($data['surat'])): ?>
            <div class="bg-white border border-slate-200 border-dashed rounded-lg p-6 text-center text-slate-500">
                Semua file sudah berada di dalam folder.
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

<!-- Modal Tambah Folder -->
<div id="modalFolder" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('modalFolder').classList.add('hidden')"></div>
        <div class="inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-sm w-full">
            <form action="<?= BASEURL; ?>/kearsipan/tambahkategori" method="POST">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4" id="modal-title">Buat Folder Baru</h3>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Folder</label>
                        <input type="text" name="nama_kategori" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" placeholder="Cth: Surat Edaran 2026" required autofocus>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Buat</button>
                    <button type="button" onclick="document.getElementById('modalFolder').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Surat/Dokumen -->
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
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Surat <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nomor_surat" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Surat <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tanggal_surat" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis <span class="text-rose-500">*</span></label>
                                        <select name="jenis_surat" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" required>
                                            <option value="">Pilih Jenis</option>
                                            <option value="Masuk">Surat Masuk</option>
                                            <option value="Keluar">Surat Keluar</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Folder Tujuan</label>
                                        <select name="kategori_id" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm">
                                            <option value="">-- Tanpa Folder --</option>
                                            <?php foreach($data['kategori'] as $kat): ?>
                                                <option value="<?= $kat['id']; ?>"><?= $kat['nama_kategori']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pengirim / Penerima <span class="text-rose-500">*</span></label>
                                    <input type="text" name="pengirim_penerima" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" placeholder="Cth: Dinas Pendidikan / Orang Tua Siswa" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Perihal <span class="text-rose-500">*</span></label>
                                    <textarea name="perihal" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" rows="2" required></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan Tambahan</label>
                                    <textarea name="keterangan" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" rows="2"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Berkas (PDF/JPG/PNG)</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors bg-slate-50">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-slate-600 justify-center">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>Upload file</span>
                                                    <input type="file" name="file_surat" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-slate-500">PDF, PNG, JPG up to 10MB</p>
                                        </div>
                                    </div>
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

<!-- Modal Edit Folder -->
<div id="modalEditFolder" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('modalEditFolder').classList.add('hidden')"></div>
        <div class="inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-sm w-full">
            <form action="<?= BASEURL; ?>/kearsipan/ubahkategori" method="POST">
                <input type="hidden" name="id" id="edit_folder_id">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4" id="modal-title">Edit Folder</h3>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Folder</label>
                        <input type="text" name="nama_kategori" id="edit_folder_nama" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm" required autofocus>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                    <button type="button" onclick="document.getElementById('modalEditFolder').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditFolder(id, nama) {
    document.getElementById('edit_folder_id').value = id;
    document.getElementById('edit_folder_nama').value = nama;
    document.getElementById('modalEditFolder').classList.remove('hidden');
}
</script>
