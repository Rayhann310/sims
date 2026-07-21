<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Preview Import Soal</h1>
            <p class="text-slate-500 mt-1">Mata Pelajaran: <span class="font-bold text-indigo-700"><?= htmlspecialchars($data['mapel']['nama_mapel']); ?></span></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?= BASEURL; ?>/BankSoal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-colors">Batal</a>
            <form action="<?= BASEURL; ?>/BankSoal/simpanImport" method="POST" class="inline-block">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition-colors shadow-sm"><i class="fas fa-save mr-2"></i>Simpan <?= count($data['soal']); ?> Soal</button>
            </form>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6">
        <i class="fas fa-info-circle mr-2"></i> Silakan periksa kembali pratinjau soal di bawah ini. Pastikan gambar (jika ada) dan opsi jawaban terbaca dengan baik sebelum menekan tombol Simpan.
    </div>

    <div class="space-y-6">
        <?php foreach ($data['soal'] as $index => $s): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-3 flex justify-between items-center">
                <div class="font-semibold text-slate-700">Soal #<?= $index + 1; ?></div>
                <div class="flex space-x-2">
                    <span class="px-2.5 py-1 rounded bg-indigo-100 text-indigo-800 text-xs font-semibold"><?= htmlspecialchars($s['tipe_soal']); ?></span>
                    <span class="px-2.5 py-1 rounded bg-slate-200 text-slate-700 text-xs font-semibold"><?= htmlspecialchars($s['tingkat_kesulitan']); ?></span>
                </div>
            </div>
            <div class="p-6">
                <!-- Pertanyaan -->
                <div class="prose max-w-none text-slate-800 mb-6">
                    <?= $s['pertanyaan']; ?>
                </div>

                <!-- Opsi Jawaban (Khusus PG) -->
                <?php if ($s['tipe_soal'] == 'PG' || $s['tipe_soal'] == 'PG_KOMPLEKS'): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start p-3 rounded-lg border <?= ($s['kunci_jawaban'] == 'A') ? 'bg-emerald-50 border-emerald-200' : 'border-slate-200 bg-slate-50' ?>">
                        <div class="font-bold mr-3 mt-1 <?= ($s['kunci_jawaban'] == 'A') ? 'text-emerald-700' : 'text-slate-500' ?>">A.</div>
                        <div class="prose-sm max-w-none w-full"><?= $s['opsi_a']; ?></div>
                    </div>
                    <div class="flex items-start p-3 rounded-lg border <?= ($s['kunci_jawaban'] == 'B') ? 'bg-emerald-50 border-emerald-200' : 'border-slate-200 bg-slate-50' ?>">
                        <div class="font-bold mr-3 mt-1 <?= ($s['kunci_jawaban'] == 'B') ? 'text-emerald-700' : 'text-slate-500' ?>">B.</div>
                        <div class="prose-sm max-w-none w-full"><?= $s['opsi_b']; ?></div>
                    </div>
                    <div class="flex items-start p-3 rounded-lg border <?= ($s['kunci_jawaban'] == 'C') ? 'bg-emerald-50 border-emerald-200' : 'border-slate-200 bg-slate-50' ?>">
                        <div class="font-bold mr-3 mt-1 <?= ($s['kunci_jawaban'] == 'C') ? 'text-emerald-700' : 'text-slate-500' ?>">C.</div>
                        <div class="prose-sm max-w-none w-full"><?= $s['opsi_c']; ?></div>
                    </div>
                    <div class="flex items-start p-3 rounded-lg border <?= ($s['kunci_jawaban'] == 'D') ? 'bg-emerald-50 border-emerald-200' : 'border-slate-200 bg-slate-50' ?>">
                        <div class="font-bold mr-3 mt-1 <?= ($s['kunci_jawaban'] == 'D') ? 'text-emerald-700' : 'text-slate-500' ?>">D.</div>
                        <div class="prose-sm max-w-none w-full"><?= $s['opsi_d']; ?></div>
                    </div>
                    <?php if(!empty(trim(strip_tags($s['opsi_e'])))): ?>
                    <div class="flex items-start p-3 rounded-lg border <?= ($s['kunci_jawaban'] == 'E') ? 'bg-emerald-50 border-emerald-200' : 'border-slate-200 bg-slate-50' ?>">
                        <div class="font-bold mr-3 mt-1 <?= ($s['kunci_jawaban'] == 'E') ? 'text-emerald-700' : 'text-slate-500' ?>">E.</div>
                        <div class="prose-sm max-w-none w-full"><?= $s['opsi_e']; ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($s['tipe_soal'] == 'ESSAY'): ?>
                <div class="mt-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <span class="text-xs font-bold text-slate-500 uppercase">Kunci Jawaban Esai:</span>
                    <div class="mt-2 text-slate-800 prose-sm"><?= $s['kunci_jawaban']; ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
