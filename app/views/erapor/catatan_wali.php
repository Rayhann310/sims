<div class="space-y-6 animate-fade-in-up" x-data="catatanWali()">
    
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Catatan Wali Kelas</h2>
                <p class="text-slate-500 mt-1">Berikan catatan khusus untuk perkembangan setiap siswa.</p>
            </div>
            <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <?php if(isset($data['siswa_list'])): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Siswa</h3>
            <p class="text-sm text-slate-500">Catatan akan tersimpan otomatis saat Anda selesai mengetik (auto-save).</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white">
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 w-16">No</th>
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 w-1/4">Siswa</th>
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100">Catatan Wali Kelas</th>
                        <th class="py-4 px-6 text-sm font-semibold text-slate-600 border-b border-slate-100 text-center w-32">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no=1; foreach($data['siswa_list'] as $s): ?>
                    <?php $cat = $data['catatan'][$s['id']]; ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-sm text-slate-600 align-top"><?= $no++; ?></td>
                        <td class="py-4 px-6 align-top">
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($s['nama_lengkap']) ?></p>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($s['nisn']) ?></p>
                        </td>
                        <td class="py-4 px-6">
                            <textarea 
                                rows="3" 
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition-all text-sm resize-y" 
                                data-siswa="<?= $s['id'] ?>"
                                @change="simpanCatatan(<?= $s['id'] ?>)"
                                placeholder="Tulis catatan wali kelas di sini..."><?= htmlspecialchars($cat) ?></textarea>
                        </td>
                        <td class="py-4 px-6 text-center align-top">
                            <span :id="'status-' + <?= $s['id'] ?>" class="text-xs font-medium px-2.5 py-1 rounded-full <?= !empty($cat) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                <?= !empty($cat) ? 'Tersimpan' : 'Belum' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
        <div class="text-slate-400 mb-4">
            <i class="fas fa-exclamation-circle text-5xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Tidak Ada Data</h3>
        <p class="text-slate-500">Anda belum ditugaskan sebagai wali kelas atau belum ada siswa di kelas ini.</p>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('catatanWali', () => ({
        simpanCatatan(siswa_id) {
            const textarea = document.querySelector(`textarea[data-siswa="${siswa_id}"]`);
            const catatan = textarea.value;
            
            const statusEl = document.getElementById('status-'+siswa_id);
            statusEl.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-700';
            statusEl.innerText = 'Menyimpan...';

            const formData = new FormData();
            formData.append('id_siswa', siswa_id);
            formData.append('catatan', catatan);

            fetch('<?= BASEURL; ?>/erapor/saveCatatanAjax', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    statusEl.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700';
                    statusEl.innerText = 'Tersimpan';
                }
            })
            .catch(() => {
                statusEl.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 text-red-700';
                statusEl.innerText = 'Error';
            });
        }
    }));
});
</script>
