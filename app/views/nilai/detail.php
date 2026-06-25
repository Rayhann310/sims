<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'presensi' }">
    <!-- Header/Breadcrumb -->
    <div class="mb-6">
        <a href="<?= BASEURL; ?>/nilai" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Jadwal
        </a>
        <h1 class="text-3xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
        <p class="text-sm text-slate-500 mt-1">Kelola presensi harian dan input nilai siswa secara masal.</p>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="flex border-b border-slate-200">
            <button @click="activeTab = 'presensi'; window.history.replaceState(null, '', '?tab=presensi&tanggal=<?= $data['tanggal'] ?>')" :class="{'border-emerald-600 text-emerald-600 bg-emerald-50': activeTab === 'presensi', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'presensi'}" class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                Presensi Harian
            </button>
            <button @click="activeTab = 'nilai'; window.history.replaceState(null, '', '?tab=nilai&jenis=<?= urlencode($data['jenis_nilai']) ?>')" :class="{'border-emerald-600 text-emerald-600 bg-emerald-50': activeTab === 'nilai', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'nilai'}" class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                Input Nilai
            </button>
        </div>

        <!-- Presensi Content -->
        <div x-show="activeTab === 'presensi'" class="p-6">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <form action="" method="get" class="flex items-end gap-3">
                    <input type="hidden" name="tab" value="presensi">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Presensi</label>
                        <input type="date" name="tanggal" value="<?= $data['tanggal']; ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors border border-slate-300">
                        Pilih
                    </button>
                </form>
            </div>

            <form action="<?= BASEURL; ?>/nilai/simpanPresensi" method="post">
                <input type="hidden" name="jadwal_id" value="<?= $data['jadwal_id']; ?>">
                <input type="hidden" name="tanggal" value="<?= $data['tanggal']; ?>">
                
                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <?php $no = 1; foreach($data['siswa'] as $s) : 
                                $status = isset($data['presensi'][$s['id']]) ? $data['presensi'][$s['id']] : 'Hadir';
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?= $s['nisn']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?= $s['nama_lengkap']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex rounded-md shadow-sm" role="group">
                                        <label class="px-3 py-1.5 text-sm font-medium border border-slate-200 rounded-l-lg cursor-pointer transition-colors <?= $status=='Hadir' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-white text-slate-600 hover:bg-slate-50' ?>">
                                            <input type="radio" name="presensi[<?= $s['id']; ?>]" value="Hadir" class="hidden" <?= $status=='Hadir' ? 'checked' : '' ?> onclick="this.parentNode.parentNode.querySelectorAll('label').forEach(l=>l.className='px-3 py-1.5 text-sm font-medium border border-slate-200 cursor-pointer transition-colors bg-white text-slate-600 hover:bg-slate-50'); this.parentNode.className='px-3 py-1.5 text-sm font-medium border rounded-l-lg cursor-pointer transition-colors bg-emerald-100 text-emerald-800 border-emerald-300'; this.parentNode.parentNode.firstElementChild.classList.add('rounded-l-lg'); this.parentNode.parentNode.lastElementChild.classList.add('rounded-r-lg');">
                                            Hadir
                                        </label>
                                        <label class="px-3 py-1.5 text-sm font-medium border-t border-b border-slate-200 cursor-pointer transition-colors <?= $status=='Izin' ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-white text-slate-600 hover:bg-slate-50' ?>">
                                            <input type="radio" name="presensi[<?= $s['id']; ?>]" value="Izin" class="hidden" <?= $status=='Izin' ? 'checked' : '' ?> onclick="this.parentNode.parentNode.querySelectorAll('label').forEach(l=>l.className='px-3 py-1.5 text-sm font-medium border border-slate-200 cursor-pointer transition-colors bg-white text-slate-600 hover:bg-slate-50'); this.parentNode.className='px-3 py-1.5 text-sm font-medium border-t border-b border-l cursor-pointer transition-colors bg-blue-100 text-blue-800 border-blue-300'; this.parentNode.parentNode.firstElementChild.classList.add('rounded-l-lg'); this.parentNode.parentNode.lastElementChild.classList.add('rounded-r-lg');">
                                            Izin
                                        </label>
                                        <label class="px-3 py-1.5 text-sm font-medium border border-slate-200 cursor-pointer transition-colors <?= $status=='Sakit' ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-white text-slate-600 hover:bg-slate-50' ?>">
                                            <input type="radio" name="presensi[<?= $s['id']; ?>]" value="Sakit" class="hidden" <?= $status=='Sakit' ? 'checked' : '' ?> onclick="this.parentNode.parentNode.querySelectorAll('label').forEach(l=>l.className='px-3 py-1.5 text-sm font-medium border border-slate-200 cursor-pointer transition-colors bg-white text-slate-600 hover:bg-slate-50'); this.parentNode.className='px-3 py-1.5 text-sm font-medium border-t border-b border-r cursor-pointer transition-colors bg-amber-100 text-amber-800 border-amber-300'; this.parentNode.parentNode.firstElementChild.classList.add('rounded-l-lg'); this.parentNode.parentNode.lastElementChild.classList.add('rounded-r-lg');">
                                            Sakit
                                        </label>
                                        <label class="px-3 py-1.5 text-sm font-medium border border-slate-200 rounded-r-lg cursor-pointer transition-colors <?= $status=='Alpa' ? 'bg-red-100 text-red-800 border-red-300' : 'bg-white text-slate-600 hover:bg-slate-50' ?>">
                                            <input type="radio" name="presensi[<?= $s['id']; ?>]" value="Alpa" class="hidden" <?= $status=='Alpa' ? 'checked' : '' ?> onclick="this.parentNode.parentNode.querySelectorAll('label').forEach(l=>l.className='px-3 py-1.5 text-sm font-medium border border-slate-200 cursor-pointer transition-colors bg-white text-slate-600 hover:bg-slate-50'); this.parentNode.className='px-3 py-1.5 text-sm font-medium border rounded-r-lg cursor-pointer transition-colors bg-red-100 text-red-800 border-red-300'; this.parentNode.parentNode.firstElementChild.classList.add('rounded-l-lg'); this.parentNode.parentNode.lastElementChild.classList.add('rounded-r-lg');">
                                            Alpa
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($data['siswa'])): ?>
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada siswa di kelas ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if(!empty($data['siswa'])): ?>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                        Simpan Presensi
                    </button>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Nilai Content -->
        <div x-show="activeTab === 'nilai'" class="p-6" style="display: none;">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <form action="" method="get" class="flex items-end gap-3">
                    <input type="hidden" name="tab" value="nilai">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Penilaian</label>
                        <select name="jenis" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                            <?php foreach($data['list_jenis_nilai'] as $jn): ?>
                                <option value="<?= $jn; ?>" <?= $data['jenis_nilai'] == $jn ? 'selected' : '' ?>><?= $jn; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors border border-slate-300">
                        Pilih
                    </button>
                </form>
            </div>

            <form action="<?= BASEURL; ?>/nilai/simpanNilai" method="post">
                <input type="hidden" name="jadwal_id" value="<?= $data['jadwal_id']; ?>">
                <input type="hidden" name="jenis_nilai" value="<?= $data['jenis_nilai']; ?>">
                
                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai (0-100)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <?php $no = 1; foreach($data['siswa'] as $s) : 
                                $nilai = isset($data['nilai'][$s['id']]) ? $data['nilai'][$s['id']] : '';
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?= $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?= $s['nisn']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?= $s['nama_lengkap']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <input type="number" step="0.01" min="0" max="100" name="nilai[<?= $s['id']; ?>]" value="<?= $nilai; ?>" placeholder="0" class="w-24 px-3 py-1.5 text-right border border-slate-300 rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($data['siswa'])): ?>
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada siswa di kelas ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if(!empty($data['siswa'])): ?>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                        Simpan Nilai
                    </button>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
