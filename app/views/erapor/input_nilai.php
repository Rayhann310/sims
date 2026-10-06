<div class="space-y-6 animate-fade-in-up" x-data="inputNilai()">
    
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Input Nilai Rapor</h2>
                <p class="text-slate-500 mt-1">Silakan pilih rombongan belajar dan mata pelajaran.</p>
            </div>
            <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="<?= BASEURL; ?>/erapor/inputNilai" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Pilihan Mapel & Kelas dari Jadwal Guru -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Mata Pelajaran & Kelas</label>
                <select name="mapel_rombel" id="mapel_rombel" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none bg-slate-50 focus:bg-white" onchange="splitRombelMapel(this)">
                    <option value="">-- Pilih Kelas & Mapel --</option>
                    <?php foreach($data['mapel_list'] as $m): ?>
                        <?php 
                        $val = $m['rombel_id'].'-'.$m['id']; 
                        $selected = ($data['selected_rombel'] == $m['rombel_id'] && $data['selected_mapel'] == $m['id']) ? 'selected' : '';
                        ?>
                        <option value="<?= $val; ?>" <?= $selected; ?>>
                            <?= $m['nama_kelas'] ?> - <?= $m['nama_rombel'] ?> | <?= $m['nama_mapel'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="rombel_id" id="rombel_id" value="<?= $data['selected_rombel'] ?>">
            <input type="hidden" name="mapel_id" id="mapel_id" value="<?= $data['selected_mapel'] ?>">
            
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-500/20 transition-all shadow-sm flex items-center">
                    <i class="fas fa-search mr-2"></i> Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    <?php if(isset($data['siswa_list'])): ?>
    <!-- Table Nilai -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Siswa</h3>
            <p class="text-sm text-slate-500">Nilai akan otomatis tersimpan saat Anda berpindah kolom (auto-save).</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 w-16">No</th>
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 min-w-[200px]">NISN / Nama Siswa</th>
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 min-w-[150px] text-center">Nilai Pengetahuan</th>
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 min-w-[150px] text-center">Nilai Sikap</th>
                        <th class="py-4 px-4 md:px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 min-w-[100px] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no=1; foreach($data['siswa_list'] as $s): ?>
                    <?php 
                        $n = $data['nilai_lama'][$s['id']];
                    ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4 md:px-6 text-sm text-slate-600"><?= $no++; ?></td>
                        <td class="py-4 px-4 md:px-6">
                            <p class="font-medium text-slate-800 whitespace-normal min-w-[150px]"><?= htmlspecialchars($s['nama_lengkap']) ?></p>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($s['nisn']) ?></p>
                        </td>
                        <td class="py-4 px-4 md:px-6">
                            <input type="number" min="0" max="100" class="w-full min-w-[80px] text-center px-3 py-2 rounded-lg border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all input-nilai" 
                                   data-siswa="<?= $s['id'] ?>" 
                                   value="<?= htmlspecialchars($n['nilai'] ?? '') ?>"
                                   @change="saveRow(<?= $s['id'] ?>)">
                        </td>
                        <td class="py-4 px-4 md:px-6">
                            <select class="w-full min-w-[120px] text-center px-3 py-2 rounded-lg border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all input-sikap"
                                    data-siswa="<?= $s['id'] ?>"
                                    @change="saveRow(<?= $s['id'] ?>)">
                                <option value="">--</option>
                                <option value="A" <?= ($n['sikap']??'') == 'A' ? 'selected' : '' ?>>A (Sangat Baik)</option>
                                <option value="B" <?= ($n['sikap']??'') == 'B' ? 'selected' : '' ?>>B (Baik)</option>
                                <option value="C" <?= ($n['sikap']??'') == 'C' ? 'selected' : '' ?>>C (Cukup)</option>
                                <option value="D" <?= ($n['sikap']??'') == 'D' ? 'selected' : '' ?>>D (Kurang)</option>
                            </select>
                        </td>
                        <td class="py-4 px-4 md:px-6 text-center">
                            <span :id="'status-' + <?= $s['id'] ?>" class="inline-block text-xs font-medium px-2.5 py-1 rounded-full <?= !empty($n['nilai']) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                <?= !empty($n['nilai']) ? 'Tersimpan' : 'Belum' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function splitRombelMapel(select) {
    const val = select.value;
    if(val) {
        const parts = val.split('-');
        document.getElementById('rombel_id').value = parts[0];
        document.getElementById('mapel_id').value = parts[1];
    } else {
        document.getElementById('rombel_id').value = '';
        document.getElementById('mapel_id').value = '';
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('inputNilai', () => ({
        saveRow(siswa_id) {
            const inputNilai = document.querySelector(`.input-nilai[data-siswa="${siswa_id}"]`);
            const inputSikap = document.querySelector(`.input-sikap[data-siswa="${siswa_id}"]`);
            const mapel_id = document.getElementById('mapel_id').value;
            
            const nilai = inputNilai.value;
            const sikap = inputSikap.value;
            
            const statusEl = document.getElementById('status-'+siswa_id);
            statusEl.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-700';
            statusEl.innerText = 'Menyimpan...';

            // Send ajax
            const formData = new FormData();
            formData.append('siswa_id', siswa_id);
            formData.append('mapel_id', mapel_id);
            formData.append('nilai', nilai);
            formData.append('sikap', sikap);

            fetch('<?= BASEURL; ?>/erapor/saveNilaiAjax', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    statusEl.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700';
                    statusEl.innerText = 'Tersimpan';
                } else {
                    statusEl.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 text-red-700';
                    statusEl.innerText = 'Gagal';
                }
            })
            .catch(err => {
                statusEl.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 text-red-700';
                statusEl.innerText = 'Error';
            });
        }
    }));
});
</script>
