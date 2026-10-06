<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Absensi Semester</h2>
                <p class="text-slate-500 mt-1">Rekapitulasi ketidakhadiran siswa dalam satu semester untuk ditampilkan di Rapor.</p>
            </div>
            <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <?php if($_SESSION['user']['role'] === 'admin'): ?>
    <!-- Filter Kelas untuk Admin -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="<?= BASEURL; ?>/erapor/absensi" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Kelas</label>
                <select name="rombel_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all outline-none bg-slate-50 focus:bg-white" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($data['rombel_list'] as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= (isset($_GET['rombel_id']) && $_GET['rombel_id'] == $r['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nama_rombel']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white font-medium rounded-xl hover:bg-amber-600 focus:ring-4 focus:ring-amber-500/20 transition-all shadow-sm flex items-center">
                    <i class="fas fa-search mr-2"></i> Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if(isset($data['siswa_list'])): ?>
    <!-- Form Absensi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-data="absensiRapor()">
        <form @submit.prevent="simpanSemua()">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Daftar Siswa</h3>
                    <p class="text-sm text-slate-500">Masukkan jumlah hari ketidakhadiran (Sakit, Izin, Tanpa Keterangan).</p>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white font-medium rounded-xl hover:bg-amber-600 transition-all shadow-sm flex items-center" :disabled="loading">
                    <i class="fas" :class="loading ? 'fa-spinner fa-spin' : 'fa-save'"></i> 
                    <span class="ml-2" x-text="loading ? 'Menyimpan...' : 'Simpan Semua'"></span>
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 w-16">No</th>
                            <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100">NISN / Nama Siswa</th>
                            <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 text-center w-32">Sakit (Hari)</th>
                            <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 text-center w-32">Izin (Hari)</th>
                            <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 text-center w-32">Alfa (Hari)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $no=1; foreach($data['siswa_list'] as $s): ?>
                        <?php $ab = $data['absensi'][$s['id']]; ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-sm text-slate-600"><?= $no++; ?></td>
                            <td class="py-4 px-6">
                                <p class="font-medium text-slate-800"><?= htmlspecialchars($s['nama_lengkap']) ?></p>
                                <p class="text-xs text-slate-500"><?= htmlspecialchars($s['nisn']) ?></p>
                            </td>
                            <td class="py-4 px-6">
                                <input type="number" min="0" name="sakit[<?= $s['id'] ?>]" value="<?= $ab['sakit'] ?>" class="w-full text-center px-3 py-2 rounded-lg border border-slate-200 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all text-sm">
                            </td>
                            <td class="py-4 px-6">
                                <input type="number" min="0" name="izin[<?= $s['id'] ?>]" value="<?= $ab['izin'] ?>" class="w-full text-center px-3 py-2 rounded-lg border border-slate-200 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all text-sm">
                            </td>
                            <td class="py-4 px-6">
                                <input type="number" min="0" name="alfa[<?= $s['id'] ?>]" value="<?= $ab['alfa'] ?>" class="w-full text-center px-3 py-2 rounded-lg border border-slate-200 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all text-sm">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('absensiRapor', () => ({
        loading: false,
        simpanSemua() {
            this.loading = true;
            const form = this.$el.querySelector('form');
            const formData = new FormData(form);
            
            fetch('<?= BASEURL; ?>/erapor/saveAbsensi', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                Swal.fire({
                    icon: data.status,
                    title: data.title,
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(() => {
                this.loading = false;
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            });
        }
    }));
});
</script>
