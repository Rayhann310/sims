<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Pengaturan E-Rapor</h2>
                <p class="text-slate-500 mt-1">Kelola kelompok mapel, KKM, dan daftar ekstrakurikuler.</p>
            </div>
            <a href="<?= BASEURL; ?>/erapor" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- TAB NAVIGATION -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex border-b border-slate-100">
            <button onclick="showTab('tab-kelompok')" id="btn-tab-kelompok" class="tab-btn px-6 py-4 text-sm font-semibold text-emerald-600 border-b-2 border-emerald-500 bg-emerald-50 transition-all">
                <i class="fas fa-layer-group mr-2"></i> Kelompok Mapel
            </button>
            <button onclick="showTab('tab-kkm')" id="btn-tab-kkm" class="tab-btn px-6 py-4 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-all">
                <i class="fas fa-bullseye mr-2"></i> KKM Mata Pelajaran
            </button>
            <button onclick="showTab('tab-ekskul')" id="btn-tab-ekskul" class="tab-btn px-6 py-4 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-all">
                <i class="fas fa-running mr-2"></i> Master Ekskul
            </button>
        </div>

        <!-- ==================== TAB: KELOMPOK MAPEL ==================== -->
        <div id="tab-kelompok" class="tab-panel p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-700">Daftar Kelompok Mata Pelajaran</h3>
                <button onclick="openKelompokModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Tambah Kelompok
                </button>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Nama Kelompok</th>
                            <th class="px-4 py-3 w-24 text-center">Urutan</th>
                            <th class="px-4 py-3 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="kelompok-tbody">
                        <?php if(empty($data['kelompok_list'])): ?>
                        <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada kelompok mapel. <button onclick="openKelompokModal()" class="text-emerald-600 underline">Tambah sekarang</button></td></tr>
                        <?php else: ?>
                        <?php $no=1; foreach($data['kelompok_list'] as $k): ?>
                        <tr class="hover:bg-slate-50 transition-colors" id="row-kelompok-<?= $k['id'] ?>">
                            <td class="px-4 py-3 text-slate-500"><?= $no++ ?></td>
                            <td class="px-4 py-3 font-semibold text-slate-800"><?= htmlspecialchars($k['nama_kelompok']) ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded-lg text-xs font-mono"><?= $k['urutan'] ?></span>
                            </td>
                            <td class="px-4 py-3 text-center space-x-1">
                                <button onclick='openKelompokModal(<?= json_encode($k) ?>)' class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button onclick="deleteKelompok(<?= $k['id'] ?>, this)" class="p-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB: KKM MAPEL ==================== -->
        <div id="tab-kkm" class="tab-panel p-6 hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-700">KKM & Kelompok per Mata Pelajaran</h3>
                <span class="text-xs text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg"><i class="fas fa-info-circle mr-1"></i> Klik Simpan pada setiap baris untuk menyimpan</span>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 w-8">No</th>
                            <th class="px-4 py-3">Mata Pelajaran</th>
                            <th class="px-4 py-3 w-44">Kelompok</th>
                            <th class="px-4 py-3 w-24 text-center">KKM</th>
                            <th class="px-4 py-3 w-24 text-center">Urutan</th>
                            <th class="px-4 py-3 w-24 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(empty($data['mapel_list'])): ?>
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada mata pelajaran.</td></tr>
                        <?php else: ?>
                        <?php $no=1; foreach($data['mapel_list'] as $m): ?>
                        <tr class="hover:bg-slate-50 transition-colors" id="row-mapel-<?= $m['id'] ?>">
                            <td class="px-4 py-2 text-slate-400 text-xs"><?= $no++ ?></td>
                            <td class="px-4 py-2 font-medium text-slate-800"><?= htmlspecialchars($m['nama_mapel']) ?></td>
                            <td class="px-4 py-2">
                                <select name="kelompok_id" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:border-emerald-400 outline-none bg-white">
                                    <option value="">-- Tanpa Kelompok --</option>
                                    <?php foreach($data['kelompok_list'] as $k): ?>
                                    <option value="<?= $k['id'] ?>" <?= ($m['kelompok_id'] == $k['id']) ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kelompok']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" name="kkm" value="<?= $m['kkm'] ?? 75 ?>" min="0" max="100"
                                    class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs text-center focus:border-emerald-400 outline-none">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" name="urutan" value="<?= $m['urutan'] ?? 99 ?>" min="1"
                                    class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs text-center focus:border-emerald-400 outline-none">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button onclick="saveKKM(this, <?= $m['id'] ?>)"
                                    class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-medium hover:bg-emerald-700 transition-colors">
                                    <i class="fas fa-save mr-1"></i>Simpan
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB: MASTER EKSKUL ==================== -->
        <div id="tab-ekskul" class="tab-panel p-6 hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-700">Master Daftar Ekstrakurikuler</h3>
                <button onclick="openEkskulModal()" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Tambah Ekskul
                </button>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Nama Ekskul</th>
                            <th class="px-4 py-3">Pembina</th>
                            <th class="px-4 py-3 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="ekskul-tbody">
                        <?php if(empty($data['ekskul_list'])): ?>
                        <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada data ekskul. <button onclick="openEkskulModal()" class="text-blue-600 underline">Tambah sekarang</button></td></tr>
                        <?php else: ?>
                        <?php $no=1; foreach($data['ekskul_list'] as $e): ?>
                        <tr class="hover:bg-slate-50 transition-colors" id="row-ekskul-<?= $e['id'] ?>">
                            <td class="px-4 py-3 text-slate-500"><?= $no++ ?></td>
                            <td class="px-4 py-3 font-semibold text-slate-800"><?= htmlspecialchars($e['nama_ekskul']) ?></td>
                            <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($e['pembina'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-center space-x-1">
                                <button onclick='openEkskulModal(<?= json_encode($e) ?>)' class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button onclick="deleteEkskul(<?= $e['id'] ?>, this)" class="p-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KELOMPOK MAPEL -->
<div id="modal-kelompok" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800" id="kelompok-modal-title">Tambah Kelompok Mapel</h3>
            <button onclick="closeModal('modal-kelompok')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="form-kelompok" class="p-6 space-y-4">
            <input type="hidden" name="action" value="save_kelompok">
            <input type="hidden" name="id" id="kelompok-id">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kelompok</label>
                <input type="text" name="nama_kelompok" id="kelompok-nama" required placeholder="Contoh: Kelompok A (Wajib)"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Urutan Tampil</label>
                <input type="number" name="urutan" id="kelompok-urutan" value="1" min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-kelompok')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EKSKUL -->
<div id="modal-ekskul" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800" id="ekskul-modal-title">Tambah Ekskul</h3>
            <button onclick="closeModal('modal-ekskul')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="form-ekskul" class="p-6 space-y-4">
            <input type="hidden" name="action" value="save_ekskul">
            <input type="hidden" name="id" id="ekskul-id">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Ekskul</label>
                <input type="text" name="nama_ekskul" id="ekskul-nama" required placeholder="Contoh: Pramuka"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pembina</label>
                <input type="text" name="pembina" id="ekskul-pembina" placeholder="Nama pembina (opsional)"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-ekskul')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const BASE = '<?= BASEURL ?>/erapor/pengaturan';

// Tab switcher
function showTab(id) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('text-emerald-600', 'border-b-2', 'border-emerald-500', 'bg-emerald-50');
        b.classList.add('text-slate-500');
    });
    document.getElementById(id).classList.remove('hidden');
    const btn = document.getElementById('btn-' + id);
    btn.classList.add('text-emerald-600', 'border-b-2', 'border-emerald-500', 'bg-emerald-50');
    btn.classList.remove('text-slate-500');
}

function closeModal(id) { document.getElementById(id).classList.replace('flex', 'hidden'); }
function openModalEl(id) { document.getElementById(id).classList.replace('hidden', 'flex'); }

// --- KELOMPOK ---
function openKelompokModal(data = null) {
    document.getElementById('form-kelompok').reset();
    document.getElementById('kelompok-id').value = data ? data.id : '';
    document.getElementById('kelompok-nama').value = data ? data.nama_kelompok : '';
    document.getElementById('kelompok-urutan').value = data ? data.urutan : 1;
    document.getElementById('kelompok-modal-title').textContent = data ? 'Edit Kelompok Mapel' : 'Tambah Kelompok Mapel';
    openModalEl('modal-kelompok');
}
document.getElementById('form-kelompok').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json()).then(r => { if(r.status==='success') location.reload(); });
});

function deleteKelompok(id, btn) {
    if(!confirm('Hapus kelompok ini? Semua mapel yang terhubung akan kehilangan kelompoknya.')) return;
    const fd = new FormData();
    fd.append('action', 'delete_kelompok');
    fd.append('id', id);
    fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json()).then(() => document.getElementById('row-kelompok-' + id).remove());
}

// --- KKM ---
function saveKKM(btn, mapelId) {
    const row = document.getElementById('row-mapel-' + mapelId);
    const fd = new FormData();
    fd.append('action', 'save_kkm');
    fd.append('mapel_id', mapelId);
    fd.append('kkm', row.querySelector('input[name="kkm"]').value);
    fd.append('kelompok_id', row.querySelector('select[name="kelompok_id"]').value);
    fd.append('urutan', row.querySelector('input[name="urutan"]').value);

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>...';
    fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(r => {
            btn.innerHTML = '<i class="fas fa-check mr-1"></i>Tersimpan';
            btn.classList.replace('bg-emerald-600', 'bg-green-500');
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-save mr-1"></i>Simpan';
                btn.classList.replace('bg-green-500', 'bg-emerald-600');
                btn.disabled = false;
            }, 2000);
        });
}

// --- EKSKUL ---
function openEkskulModal(data = null) {
    document.getElementById('form-ekskul').reset();
    document.getElementById('ekskul-id').value = data ? data.id : '';
    document.getElementById('ekskul-nama').value = data ? data.nama_ekskul : '';
    document.getElementById('ekskul-pembina').value = data ? (data.pembina || '') : '';
    document.getElementById('ekskul-modal-title').textContent = data ? 'Edit Ekskul' : 'Tambah Ekskul';
    openModalEl('modal-ekskul');
}
document.getElementById('form-ekskul').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json()).then(r => { if(r.status==='success') location.reload(); });
});

function deleteEkskul(id, btn) {
    if(!confirm('Hapus ekskul ini?')) return;
    const fd = new FormData();
    fd.append('action', 'delete_ekskul');
    fd.append('id', id);
    fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json()).then(() => document.getElementById('row-ekskul-' + id).remove());
}
</script>
