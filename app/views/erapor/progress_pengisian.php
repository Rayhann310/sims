<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-lg border border-teal-200">
                        <i class="fas fa-chart-pie mr-1"></i> Monitoring Wali Kelas
                    </span>
                    <?php if($data['detail_rombel']): ?>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg">
                        Kelas: <?= htmlspecialchars($data['detail_rombel']['nama_rombel']) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mt-2">Progress Pengisian Nilai Rapor</h2>
                <p class="text-slate-500 mt-1 text-sm">Pantau persentase kelengkapan pengisian nilai rapor dari guru mata pelajaran dan hubungi via WhatsApp langsung.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= BASEURL; ?>/erapor/cetak<?= !empty($data['selected_rombel']) ? '?rombel='.$data['selected_rombel'].'&tahun='.$data['selected_tahun'] : '' ?>" class="px-4 py-2.5 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition-colors font-medium text-sm shadow-sm flex items-center gap-2">
                    <i class="fas fa-print"></i> Cetak Rapor Kelas
                </a>
                <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors font-medium text-sm flex items-center gap-1.5">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Filter / Info Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="<?= BASEURL; ?>/erapor/progressPengisian" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Tahun Akademik</label>
                <select name="tahun" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none text-sm transition-all" onchange="this.form.submit()">
                    <?php foreach($data['tahun_list'] as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $data['selected_tahun'] == $t['id'] ? 'selected' : '' ?>>
                            <?= $t['nama_tahun'] ?> (Semester <?= $t['semester'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if($_SESSION['user']['role'] === 'admin'): ?>
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Pilih Kelas / Rombel</label>
                <select name="rombel_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none text-sm transition-all" onchange="this.form.submit()">
                    <?php foreach($data['rombel_list'] as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $data['selected_rombel'] == $r['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nama_rombel']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Kelas Binaan</label>
                <div class="px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-700">
                    <?= htmlspecialchars($data['detail_rombel']['nama_rombel'] ?? 'Kelas Anda') ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white font-medium rounded-xl hover:bg-slate-900 transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Muat Ulang
                </button>
            </div>
        </form>
    </div>

    <?php if($data['progress']): ?>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card: Rata-rata Progres Kelas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Progres Nilai</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 mb-2">
                <?= $data['progress']['total_persen_kelas'] ?>%
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: <?= $data['progress']['total_persen_kelas'] ?>%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-2">Rata-rata seluruh mapel kelas</p>
        </div>

        <!-- Card: Mapel Lengkap -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mapel Tuntas (100%)</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <i class="fas fa-check-double text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600 mb-1">
                <?= $data['progress']['mapel_tuntas'] ?> <span class="text-slate-400 text-lg font-normal">/ <?= $data['progress']['total_mapel'] ?></span>
            </div>
            <p class="text-xs text-slate-500">Mata pelajaran telah 100% dinilai</p>
        </div>

        <!-- Card: Mapel Belum Lengkap -->
        <?php $belumLengkap = $data['progress']['total_mapel'] - $data['progress']['mapel_tuntas']; ?>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Belum Lengkap</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <i class="fas fa-hourglass-half text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold <?= $belumLengkap > 0 ? 'text-amber-600' : 'text-slate-800' ?> mb-1">
                <?= $belumLengkap ?>
            </div>
            <p class="text-xs text-slate-500">Mata pelajaran butuh follow-up</p>
        </div>

        <!-- Card: Total Siswa -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jumlah Siswa</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <i class="fas fa-user-graduate text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 mb-1">
                <?= $data['progress']['total_siswa'] ?>
            </div>
            <p class="text-xs text-slate-500">Siswa aktif di rombel ini</p>
        </div>
    </div>

    <!-- Table List Guru & Mapel -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Daftar Guru Pengajar & Kelengkapan Nilai</h3>
                <p class="text-xs text-slate-500 mt-0.5">Klik tombol WhatsApp untuk mengirimkan pesan pengingat kepada guru yang belum selesai mengisi nilai.</p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-medium border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Lengkap
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 rounded-full font-medium border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Sebagian
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 rounded-full font-medium border border-rose-200">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Belum Diisi
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm no-datatable">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-5 w-12 text-center">No</th>
                        <th class="py-3.5 px-5 min-w-[200px]">Mata Pelajaran</th>
                        <th class="py-3.5 px-5 min-w-[220px]">Guru Pengampu</th>
                        <th class="py-3.5 px-5 text-center min-w-[140px]">Siswa Terisi</th>
                        <th class="py-3.5 px-5 min-w-[220px]">Persentase</th>
                        <th class="py-3.5 px-5 text-center w-44">Aksi Hubungi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['progress']['list'])): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fas fa-inbox text-4xl mb-3 block text-slate-300"></i>
                            Belum ada jadwal mata pelajaran untuk rombel ini.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php 
                    $no = 1; 
                    $namaKelas = $data['detail_rombel']['nama_rombel'] ?? 'Kelas';
                    foreach($data['progress']['list'] as $row): 
                        $persen = $row['persen'];
                        $isLengkap = ($persen >= 100);
                        
                        // Buat teks pesan template WA otomatis
                        $pesanWA = "Assalamu'alaikum Wr. Wb. Yth. Bapak/Ibu " . $row['nama_guru'] . ",\n\n"
                                 . "Mohon izin mengingatkan dari Wali Kelas " . $namaKelas . " terkait pengisian nilai E-Rapor:\n"
                                 . "• Mata Pelajaran: *" . $row['nama_mapel'] . "*\n"
                                 . "• Kelas: *" . $namaKelas . "*\n"
                                 . "• Status Saat Ini: *" . $row['terisi'] . " dari " . $row['total_siswa'] . " siswa (" . $persen . "%)*\n\n"
                                 . ($isLengkap 
                                    ? "Alhamdulillah nilai sudah lengkap 100%. Terima kasih banyak atas kerjasamanya! 🙏" 
                                    : "Mohon berkenan untuk segera melengkapi input nilai rapor agar pencetakan rapor dapat diproses tepat waktu. Terima kasih banyak atas bantuan dan kerjasamanya! 🙏");
                        $waUrl = !empty($row['no_hp_wa']) ? "https://wa.me/" . $row['no_hp_wa'] . "?text=" . rawurlencode($pesanWA) : null;
                    ?>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-5 text-center text-slate-400 font-mono text-xs"><?= $no++ ?></td>
                        <td class="py-4 px-5">
                            <span class="font-bold text-slate-800 text-sm block"><?= htmlspecialchars($row['nama_mapel']) ?></span>
                            <span class="text-xs text-slate-400">Kode: <?= htmlspecialchars($row['kode_mapel'] ?? '-') ?></span>
                        </td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold">
                                    <?= strtoupper(substr($row['nama_guru'], 0, 1)) ?>
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-800 text-sm block"><?= htmlspecialchars($row['nama_guru']) ?></span>
                                    <span class="text-xs text-slate-500 font-mono flex items-center gap-1">
                                        <i class="fab fa-whatsapp text-emerald-500"></i>
                                        <?= !empty($row['no_hp']) ? htmlspecialchars($row['no_hp']) : '<span class="italic text-slate-400">No HP belum ada</span>' ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-center">
                            <span class="inline-flex items-center gap-1 font-bold text-sm <?= $isLengkap ? 'text-emerald-700' : ($row['terisi'] > 0 ? 'text-amber-700' : 'text-rose-600') ?>">
                                <?= $row['terisi'] ?> <span class="text-xs text-slate-400 font-normal">/ <?= $row['total_siswa'] ?></span>
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold <?= $isLengkap ? 'text-emerald-600' : ($persen > 0 ? 'text-amber-600' : 'text-slate-400') ?>">
                                        <?= $persen ?>%
                                    </span>
                                    <span class="text-[11px] <?= $isLengkap ? 'text-emerald-600 font-medium' : ($persen > 0 ? 'text-amber-600' : 'text-rose-500') ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <?php 
                                    $barColor = $isLengkap ? 'bg-emerald-500' : ($persen > 0 ? 'bg-amber-500' : 'bg-rose-400');
                                    ?>
                                    <div class="<?= $barColor ?> h-2 rounded-full transition-all duration-300" style="width: <?= $persen ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-center">
                            <?php if(!empty($row['no_hp_wa'])): ?>
                            <a href="<?= $waUrl ?>" target="_blank" 
                               class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 <?= $isLengkap ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm' ?> rounded-xl text-xs font-semibold transition-all active:scale-95" 
                               title="Kirim pesan WhatsApp ke Guru">
                                <i class="fab fa-whatsapp text-sm"></i>
                                <span><?= $isLengkap ? 'Konfirmasi' : 'Ingatkan WA' ?></span>
                            </a>
                            <?php else: ?>
                            <button type="button" disabled 
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-400 rounded-xl text-xs font-medium cursor-not-allowed" 
                                    title="Nomor HP guru tidak terdaftar di database">
                                <i class="fas fa-phone-slash text-xs"></i>
                                <span>Tanpa No WA</span>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
