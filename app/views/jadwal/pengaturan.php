<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Atur jam operasional, waktu istirahat, dan alokasi jam mengajar.</p>
        </div>
        <a href="<?= BASEURL; ?>/jadwal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Jadwal
        </a>
    </div>

    <?php Flasher::flash(); ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pengaturan Waktu -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2"><i class="fas fa-clock text-emerald-500 mr-2"></i> Jam Operasional Sekolah</h2>
                <form action="<?= BASEURL; ?>/jadwal/simpanPengaturanJadwal" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jam Mulai (JP 1)</label>
                            <input type="time" name="jam_mulai" value="<?= date('H:i', strtotime($data['pengaturan']['jam_mulai'])) ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Durasi per Jam Pelajaran (Menit)</label>
                            <input type="number" name="durasi_per_jp" value="<?= $data['pengaturan']['durasi_per_jp'] ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Maksimal JP per Hari</label>
                            <input type="number" name="max_jp_per_hari" value="<?= $data['pengaturan']['max_jp_per_hari'] ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Hari Aktif (pisahkan dengan koma)</label>
                            <input type="text" name="hari_aktif" value="<?= $data['pengaturan']['hari_aktif'] ?>" placeholder="Senin,Selasa,Rabu,Kamis,Jumat" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white font-medium py-2 rounded-lg hover:bg-emerald-700 transition-colors">Simpan Pengaturan</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2"><i class="fas fa-coffee text-amber-500 mr-2"></i> Jadwal Istirahat</h2>
                <form action="<?= BASEURL; ?>/jadwal/simpanIstirahat" method="POST" class="mb-4" id="form-istirahat">
                    <input type="hidden" name="id" id="istirahat_id">
                    <div class="space-y-3">
                        <input type="text" name="nama_istirahat" id="nama_istirahat" placeholder="Nama (cth: Istirahat 1, Upacara)" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                        <div class="flex gap-2">
                            <input type="number" name="setelah_jp_ke" id="setelah_jp_ke" placeholder="Stlh JP ke" class="w-1/2 px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            <input type="number" name="durasi_menit" id="durasi_menit" placeholder="Durasi (m)" class="w-1/2 px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                        </div>
                        <input type="text" name="hari_khusus" id="hari_khusus" placeholder="Khusus Hari (kosongkan = tiap hari)" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-slate-800 text-white font-medium py-2 rounded-lg hover:bg-slate-900 transition-colors text-sm">Simpan</button>
                            <button type="button" onclick="resetFormIstirahat()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm" title="Batal Edit"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </form>
                
                <ul class="space-y-2">
                    <?php foreach($data['istirahat'] as $ist): ?>
                        <li class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-lg text-sm">
                            <div>
                                <p class="font-bold text-slate-700"><?= $ist['nama_istirahat'] ?></p>
                                <p class="text-xs text-slate-500">Stlh JP <?= $ist['setelah_jp_ke'] ?> (<?= $ist['durasi_menit'] ?>m) <?= $ist['hari_khusus'] ? '- Hny ' . $ist['hari_khusus'] : '' ?></p>
                            </div>
                            <div class="flex gap-1">
                                <button type="button" onclick="editIstirahat(<?= htmlspecialchars(json_encode($ist)) ?>)" class="text-blue-500 hover:bg-blue-50 p-1.5 rounded-lg"><i class="fas fa-edit"></i></button>
                                <a href="<?= BASEURL; ?>/jadwal/hapusIstirahat/<?= $ist['id'] ?>" class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg" onclick="return confirm('Hapus istirahat ini?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Alokasi Jam Mengajar -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
                <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2"><i class="fas fa-book-open text-blue-500 mr-2"></i> Alokasi Beban Jam (JP / Minggu)</h2>
                <form action="<?= BASEURL; ?>/jadwal/simpanAlokasi" method="POST" class="mb-6 flex gap-3 flex-wrap items-end bg-slate-50 p-4 rounded-xl border border-slate-100" id="form-alokasi">
                    <input type="hidden" name="id" id="alokasi_id">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            <option value="">-- Pilih Mapel --</option>
                            <?php foreach($data['mapel_list'] as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="w-48">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach($data['daftar_kelas'] as $k): ?>
                                <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?> (<?= $k['tingkat'] ?> <?= $k['jurusan'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="w-24">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jml JP</label>
                        <input type="number" name="jumlah_jp" id="jumlah_jp" value="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm"><i class="fas fa-save mr-1"></i> Simpan</button>
                        <button type="button" onclick="resetFormAlokasi()" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors text-sm" title="Batal Edit"><i class="fas fa-times"></i></button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200 uppercase text-xs">
                            <tr>
                                <th class="py-3 px-4 rounded-tl-lg">Mata Pelajaran</th>
                                <th class="py-3 px-4">Kelas</th>
                                <th class="py-3 px-4 text-center">Jml JP / Minggu</th>
                                <th class="py-3 px-4 rounded-tr-lg text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach($data['alokasi'] as $a): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-slate-800"><?= $a['nama_mapel'] ?></td>
                                    <td class="py-3 px-4"><span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-md font-bold text-xs"><?= $a['nama_kelas'] ?> (<?= $a['tingkat'] ?> <?= $a['jurusan'] ?>)</span></td>
                                    <td class="py-3 px-4 text-center"><span class="font-bold text-emerald-600"><?= $a['jumlah_jp'] ?> JP</span></td>
                                    <td class="py-3 px-4 text-right">
                                        <button type="button" onclick="editAlokasi(<?= htmlspecialchars(json_encode($a)) ?>)" class="text-blue-500 hover:text-blue-700 mx-1 p-1"><i class="fas fa-edit"></i></button>
                                        <a href="<?= BASEURL; ?>/jadwal/hapusAlokasi/<?= $a['id'] ?>" onclick="return confirm('Hapus alokasi ini?')" class="text-red-500 hover:text-red-700 mx-1 p-1"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(empty($data['alokasi'])): ?>
                                <tr><td colspan="4" class="py-6 text-center text-slate-400">Belum ada pengaturan alokasi beban mengajar.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function editIstirahat(data) {
        document.getElementById('istirahat_id').value = data.id;
        document.getElementById('nama_istirahat').value = data.nama_istirahat;
        document.getElementById('setelah_jp_ke').value = data.setelah_jp_ke;
        document.getElementById('durasi_menit').value = data.durasi_menit;
        document.getElementById('hari_khusus').value = data.hari_khusus;
        document.getElementById('form-istirahat').scrollIntoView({behavior: 'smooth'});
    }

    function resetFormIstirahat() {
        document.getElementById('istirahat_id').value = '';
        document.getElementById('form-istirahat').reset();
    }

    function editAlokasi(data) {
        document.getElementById('alokasi_id').value = data.id;
        document.getElementById('mapel_id').value = data.mapel_id;
        document.getElementById('kelas_id').value = data.kelas_id;
        document.getElementById('jumlah_jp').value = data.jumlah_jp;
        document.getElementById('form-alokasi').scrollIntoView({behavior: 'smooth'});
    }

    function resetFormAlokasi() {
        document.getElementById('alokasi_id').value = '';
        document.getElementById('form-alokasi').reset();
        document.getElementById('jumlah_jp').value = '2'; // default
    }
</script>
