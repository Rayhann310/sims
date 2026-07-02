<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ modalOpen: false, modalPindah: false, selectAllAnggota: false }">
    <div class="mb-6">
        <a href="<?= BASEURL; ?>/akademik/rombel" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 mb-4 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Data Rombel
        </a>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Anggota Rombel: <?= $data['rombel']['nama_rombel']; ?></h1>
                <div class="flex items-center gap-3 mt-2 text-sm text-slate-600">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">
                        Tahun: <?= $data['rombel']['nama_tahun']; ?> (<?= $data['rombel']['semester']; ?>)
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-indigo-100 text-indigo-800">
                        Kelas: <?= $data['rombel']['nama_kelas']; ?>
                    </span>
                </div>
            </div>
            <button @click="modalOpen = true" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Anggota (Siswa)
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-base font-semibold text-slate-800">Daftar Siswa di Rombel Ini</h3>
            <div class="flex items-center gap-3">
                <button type="button" onclick="document.getElementById('modalPindah').style.display='block'; window.dispatchEvent(new CustomEvent('open-modal-pindah'))" class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Pindah Rombel Terpilih
                </button>
                <span class="text-sm font-medium text-slate-500">Total: <?= count($data['anggota']); ?> Siswa</span>
            </div>
        </div>
        <form action="<?= BASEURL; ?>/akademik/pindahSiswaMasal" method="post" id="formPindahMasal">
            <input type="hidden" name="rombel_asal" value="<?= $data['rombel']['id']; ?>">
            
            <!-- Hidden modal for Pindah -->
            <div id="modalPindah" x-show="modalPindah" @open-modal-pindah.window="modalPindah = true" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div x-show="modalPindah" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalPindah = false; document.getElementById('modalPindah').style.display='none'"></div>
                    <div x-show="modalPindah" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-slate-900">Pindah Rombel Masal</h3>
                            <button type="button" @click="modalPindah = false; document.getElementById('modalPindah').style.display='none'" class="text-slate-400 hover:text-slate-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Rombel Tujuan</label>
                            <select name="rombel_tujuan" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                <option value="">-- Pilih Rombel --</option>
                                <?php foreach($data['rombel_tujuan'] as $rt): ?>
                                <option value="<?= $rt['id']; ?>"><?= $rt['nama_kelas']; ?> - <?= $rt['nama_rombel']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-xs text-slate-500 mt-2">Hanya rombel di tahun akademik ini yang ditampilkan.</p>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="modalPindah = false; document.getElementById('modalPindah').style.display='none'" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</button>
                            <button type="submit" onclick="if(document.querySelectorAll('.anggota-checkbox:checked').length == 0) { alert('Pilih minimal 1 siswa!'); return false; }" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600">Pindahkan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-4 py-4 text-left" data-sortable="false">
                                <input type="checkbox" @change="
                                    selectAllAnggota = !selectAllAnggota; 
                                    const checkboxes = document.querySelectorAll('.anggota-checkbox');
                                    checkboxes.forEach(cb => cb.checked = selectAllAnggota);
                                " class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500">
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">L/P</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider" data-sortable="false">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                    <?php $no = 1; foreach($data['anggota'] as $a) : ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox" name="siswa_ids[]" value="<?= $a['id']; ?>" class="anggota-checkbox w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $no++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?= $a['nisn']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900"><?= $a['nama_siswa']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $a['jenis_kelamin']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="<?= BASEURL; ?>/akademik/hapusAnggota/<?= $data['rombel']['id']; ?>/<?= $a['anggota_id']; ?>" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Yakin ingin mengeluarkan siswa ini dari rombel?');" title="Keluarkan">Keluarkan</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <?php if(empty($data['anggota'])): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            Belum ada siswa di rombel ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Modal Tambah Masal -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>

            <!-- Modal Panel -->
            <div x-show="modalOpen" x-transition class="relative inline-block w-full max-w-3xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tambah Anggota ke <?= $data['rombel']['nama_rombel']; ?></h3>
                        <p class="text-sm text-slate-500">Pilih siswa yang belum memiliki rombel di tahun akademik ini.</p>
                    </div>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/akademik/tambahAnggotaMasal" method="post">
                    <input type="hidden" name="rombel_id" value="<?= $data['rombel']['id']; ?>">
                    
                    <div class="max-h-96 overflow-y-auto border border-slate-200 rounded-lg mb-4">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 sticky top-0 z-10">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left" data-sortable="false">
                                        <input type="checkbox" id="selectAllCheckbox" @change="
                                            let cb = document.querySelectorAll('.siswa-checkbox');
                                            cb.forEach(c => c.checked = $event.target.checked);
                                        " class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500">
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">NISN</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Siswa</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kelas Master</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                <?php foreach($data['siswa_tersedia'] as $st) : ?>
                                <tr class="hover:bg-slate-50 cursor-pointer">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="checkbox" name="siswa_ids[]" value="<?= $st['id']; ?>" class="siswa-checkbox w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900 font-medium"><?= $st['nisn']; ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700"><?= $st['nama_lengkap']; ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-500"><?= $st['nama_kelas']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($data['siswa_tersedia'])): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                        Semua siswa sudah masuk rombel di tahun akademik ini.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Tambahkan Terpilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
