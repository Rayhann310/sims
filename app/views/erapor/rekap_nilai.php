<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Rekap Nilai Kelas</h2>
                <p class="text-slate-500 mt-1">Lihat seluruh nilai rapor siswa yang telah diinput oleh guru mata pelajaran.</p>
            </div>
            <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <?php if($_SESSION['user']['role'] === 'admin'): ?>
    <!-- Filter Kelas untuk Admin -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="<?= BASEURL; ?>/erapor/rekapNilai" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Kelas</label>
                <select name="rombel_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none bg-slate-50 focus:bg-white" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($data['rombel_list'] as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= (isset($_GET['rombel_id']) && $_GET['rombel_id'] == $r['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nama_rombel']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-500/20 transition-all shadow-sm flex items-center">
                    <i class="fas fa-search mr-2"></i> Tampilkan Nilai
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if(!empty($data['siswa_list'])): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Nilai Siswa</h3>
            <p class="text-sm text-slate-500">Nilai kosong menandakan guru mapel terkait belum melakukan input nilai.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white">
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 w-16 sticky left-0 bg-white shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] z-10">No</th>
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 sticky left-[64px] bg-white shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] z-10 w-64">Nama Siswa</th>
                        
                        <?php 
                        // Kumpulkan semua mata pelajaran dari siswa pertama sebagai header kolom
                        $all_mapel = [];
                        if(isset($data['rekap'][$data['siswa_list'][0]['id']])) {
                            foreach($data['rekap'][$data['siswa_list'][0]['id']] as $kel) {
                                foreach($kel['mapel'] as $m) {
                                    $all_mapel[$m['mapel_id']] = $m['nama_mapel'];
                                }
                            }
                        }
                        
                        foreach($all_mapel as $mapel_name): 
                        ?>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-600 border-b border-slate-100 text-center">
                            <div class="w-24 overflow-hidden text-ellipsis" title="<?= htmlspecialchars($mapel_name) ?>">
                                <?= htmlspecialchars($mapel_name) ?>
                            </div>
                        </th>
                        <?php endforeach; ?>
                        
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 text-center bg-slate-50">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no=1; foreach($data['siswa_list'] as $s): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-sm text-slate-600 sticky left-0 bg-white group-hover:bg-slate-50/50 z-10"><?= $no++; ?></td>
                        <td class="py-4 px-6 sticky left-[64px] bg-white group-hover:bg-slate-50/50 z-10">
                            <p class="font-medium text-slate-800 text-sm truncate w-56" title="<?= htmlspecialchars($s['nama_lengkap']) ?>"><?= htmlspecialchars($s['nama_lengkap']) ?></p>
                        </td>
                        
                        <?php 
                        $total = 0; $count = 0;
                        $siswa_nilai = [];
                        // Restructure array mapel siswa ini
                        if(isset($data['rekap'][$s['id']])) {
                            foreach($data['rekap'][$s['id']] as $kel) {
                                foreach($kel['mapel'] as $m) {
                                    $siswa_nilai[$m['mapel_id']] = [
                                        'nilai' => $m['nilai'],
                                        'sikap' => $m['sikap']
                                    ];
                                    if(!empty($m['nilai'])) {
                                        $total += $m['nilai'];
                                        $count++;
                                    }
                                }
                            }
                        }
                        
                        foreach($all_mapel as $mapel_id => $mapel_name): 
                            $nilai_item = $siswa_nilai[$mapel_id] ?? null;
                            $has_nilai = !empty($nilai_item['nilai']);
                        ?>
                        <td class="py-4 px-6 text-center border-l border-slate-50">
                            <?php if($has_nilai): ?>
                                <span class="font-bold text-slate-700"><?= $nilai_item['nilai'] ?></span>
                                <div class="text-[10px] text-slate-400 mt-0.5">Sikap: <?= $nilai_item['sikap'] ?></div>
                            <?php else: ?>
                                <span class="text-slate-300">-</span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                        
                        <td class="py-4 px-6 text-center font-bold text-emerald-600 bg-slate-50">
                            <?= $count > 0 ? round($total/$count, 2) : '0' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
