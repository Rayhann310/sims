<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Setup Auto-Generate</h1>
            <p class="text-sm text-slate-500 mt-1">Kelas: <span class="font-bold text-slate-700"><?= $data['rombel']['nama_rombel']; ?></span> (<?= $data['rombel']['tingkat']; ?> <?= $data['rombel']['jurusan']; ?>)</p>
        </div>
        <a href="<?= BASEURL; ?>/jadwal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Batal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <h2 class="text-lg font-bold text-blue-900 mb-2"><i class="fas fa-info-circle mr-2"></i> Petunjuk</h2>
            <p class="text-sm text-blue-800">
                Pilih guru pengampu untuk setiap mata pelajaran wajib kelas ini. Mata pelajaran yang tidak dipilih gurunya <strong>TIDAK AKAN</strong> diikutsertakan dalam Auto-Generate.
            </p>
        </div>
        
        <form action="<?= BASEURL; ?>/jadwal/prosesGenerate" method="POST" class="p-6">
            <input type="hidden" name="rombel_id" value="<?= $data['rombel']['id'] ?>">
            <input type="hidden" name="ta_id" value="<?= $data['ta_id'] ?>">
            
            <div class="space-y-4">
                <?php foreach($data['alokasi'] as $a): ?>
                    <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-blue-200 transition-colors">
                        <div class="w-1/2">
                            <h3 class="font-bold text-slate-800"><?= $a['nama_mapel'] ?></h3>
                            <p class="text-xs font-medium text-slate-500 mt-1">Kode: <?= $a['kode_mapel'] ?> &bull; Beban: <span class="text-emerald-600 font-bold"><?= $a['jumlah_jp'] ?> JP</span></p>
                        </div>
                        <div class="w-1/2">
                            <select name="guru[<?= $a['mapel_id'] ?>]" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white text-sm">
                                <option value="">-- Abaikan (Tidak Dijadwalkan) --</option>
                                <?php foreach($data['guru_list'] as $g): ?>
                                    <option value="<?= $g['id'] ?>"><?= $g['nama_lengkap'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if(empty($data['alokasi'])): ?>
                    <div class="p-8 text-center bg-amber-50 border border-amber-100 rounded-xl">
                        <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-3"></i>
                        <h3 class="font-bold text-amber-800">Alokasi Mapel Kosong</h3>
                        <p class="text-amber-700 text-sm mt-2">Belum ada alokasi mata pelajaran untuk tingkat <?= $data['rombel']['tingkat'] ?> jurusan <?= $data['rombel']['jurusan'] ?>. Silakan ke menu Pengaturan Jadwal terlebih dahulu.</p>
                        <a href="<?= BASEURL; ?>/jadwal/pengaturan" class="inline-block mt-4 px-4 py-2 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700">Ke Pengaturan</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if(!empty($data['alokasi'])): ?>
            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors shadow-md shadow-purple-200 flex items-center gap-2">
                    Generate Jadwal Sekarang <i class="fas fa-magic"></i>
                </button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>
