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
                <table class="w-full text-left text-sm no-datatable">
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
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <span>KKM & Kelompok per Mata Pelajaran</span>
                        <span id="kkm-save-status" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full border border-emerald-200">
                            <i class="fas fa-check-circle"></i> Realtime Auto-Save Aktif
                        </span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Ubah urutan, kelompok, atau KKM. Data akan <strong>langsung diurutkan dan disimpan secara realtime</strong> serta otomatis diterapkan pada susunan rapor siswa.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span id="mapel-count-badge" class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold">
                        Total <?= count($data['mapel_list']) ?> Mapel
                    </span>
                </div>
            </div>

            <!-- Toolbar: Search & Filter Kelompok -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4">
                <div class="md:col-span-7 relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="kkm-search" placeholder="Cari mata pelajaran..." oninput="filterMapelTable()"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all">
                </div>
                <div class="md:col-span-5 relative">
                    <select id="kkm-filter-kelompok" onchange="filterMapelTable()"
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none bg-white transition-all">
                        <option value="">-- Semua Kelompok Mapel --</option>
                        <option value="none">-- Tanpa Kelompok Saja --</option>
                        <?php foreach($data['kelompok_list'] as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelompok']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200/80 shadow-sm">
                <table class="w-full text-left text-sm no-datatable" id="table-kkm">
                    <thead class="bg-slate-50/80 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5 w-14 text-center">No</th>
                            <th class="px-4 py-3.5 min-w-[200px]">Mata Pelajaran</th>
                            <th class="px-4 py-3.5 w-52">Kelompok</th>
                            <th class="px-4 py-3.5 w-24 text-center">KKM</th>
                            <th class="px-4 py-3.5 w-36 text-center">Urutan Tampil</th>
                            <th class="px-4 py-3.5 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="mapel-tbody">
                        <?php if(empty($data['mapel_list'])): ?>
                        <tr id="row-empty-mapel"><td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada mata pelajaran.</td></tr>
                        <?php else: ?>
                        <?php $no=1; foreach($data['mapel_list'] as $m): ?>
                        <tr class="hover:bg-slate-50/70 transition-all mapel-row" 
                            id="row-mapel-<?= $m['id'] ?>"
                            data-id="<?= $m['id'] ?>"
                            data-nama="<?= htmlspecialchars(strtolower($m['nama_mapel'])) ?>"
                            data-kelompok-id="<?= $m['kelompok_id'] ?? '' ?>"
                            data-urutan="<?= (int)($m['urutan'] ?? 99) ?>">
                            <td class="px-4 py-3 text-slate-500 font-mono text-xs text-center row-number"><?= $no++ ?></td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-slate-800 text-sm block"><?= htmlspecialchars($m['nama_mapel']) ?></span>
                                <span class="text-[11px] text-slate-400">Kode: <?= htmlspecialchars($m['kode_mapel'] ?? '-') ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <select name="kelompok_id" onchange="onKelompokChange(<?= $m['id'] ?>)" 
                                    class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none bg-white transition-all">
                                    <option value="" <?= empty($m['kelompok_id']) ? 'selected' : '' ?>>-- Tanpa Kelompok --</option>
                                    <?php foreach($data['kelompok_list'] as $k): ?>
                                    <option value="<?= $k['id'] ?>" <?= ($m['kelompok_id'] == $k['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($k['nama_kelompok']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="kkm" value="<?= $m['kkm'] ?? 75 ?>" min="0" max="100"
                                    onchange="saveKKMRow(<?= $m['id'] ?>)"
                                    class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-center focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center justify-center gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200">
                                    <button type="button" onclick="stepUrutan(<?= $m['id'] ?>, -1)" title="Naikkan urutan (angka lebih kecil)"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-white shadow-xs hover:bg-emerald-500 hover:text-white text-slate-600 transition-all text-xs active:scale-95">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <input type="number" name="urutan" value="<?= $m['urutan'] ?? 99 ?>" min="1"
                                        oninput="onUrutanInput(<?= $m['id'] ?>)"
                                        onchange="onUrutanChange(<?= $m['id'] ?>)"
                                        class="w-14 px-1 py-1 rounded-lg border border-slate-200 text-xs font-bold text-center text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none bg-white transition-all">
                                    <button type="button" onclick="stepUrutan(<?= $m['id'] ?>, 1)" title="Turunkan urutan (angka lebih besar)"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-white shadow-xs hover:bg-emerald-500 hover:text-white text-slate-600 transition-all text-xs active:scale-95">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="saveKKM(this, <?= $m['id'] ?>)"
                                    class="btn-save-row w-full px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-medium hover:bg-emerald-700 active:scale-95 transition-all shadow-xs flex items-center justify-center gap-1">
                                    <i class="fas fa-save"></i> <span>Simpan</span>
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
                <table class="w-full text-left text-sm no-datatable">
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

// Kelompok Map Data for realtime sorting
const kelompokOrderMap = {};
<?php foreach($data['kelompok_list'] as $k): ?>
kelompokOrderMap['<?= $k['id'] ?>'] = {
    id: <?= (int)$k['id'] ?>,
    nama: <?= json_encode($k['nama_kelompok']) ?>,
    urutan: <?= (int)($k['urutan'] ?? 99) ?>
};
<?php endforeach; ?>

let urutanInputTimer = null;
let saveStatusTimer = null;

// Tab switcher with persistence
function showTab(id) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('text-emerald-600', 'border-b-2', 'border-emerald-500', 'bg-emerald-50');
        b.classList.add('text-slate-500');
    });
    const panel = document.getElementById(id);
    if(panel) panel.classList.remove('hidden');
    const btn = document.getElementById('btn-' + id);
    if(btn) {
        btn.classList.add('text-emerald-600', 'border-b-2', 'border-emerald-500', 'bg-emerald-50');
        btn.classList.remove('text-slate-500');
    }
    try {
        localStorage.setItem('erapor_active_tab', id);
        history.replaceState(null, null, '#' + id);
    } catch(e) {}
}

// Restore active tab on load
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash ? window.location.hash.substring(1) : null;
    const saved = localStorage.getItem('erapor_active_tab');
    const targetTab = hash || saved || 'tab-kelompok';
    if(targetTab && document.getElementById(targetTab)) {
        showTab(targetTab);
    }
});

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
        .then(r => r.json()).then(r => { 
            if(r.status==='success') {
                localStorage.setItem('erapor_active_tab', 'tab-kelompok');
                location.reload(); 
            }
        });
});

function deleteKelompok(id, btn) {
    if(!confirm('Hapus kelompok ini? Semua mapel yang terhubung akan kehilangan kelompoknya.')) return;
    const fd = new FormData();
    fd.append('action', 'delete_kelompok');
    fd.append('id', id);
    fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json()).then(() => {
            const row = document.getElementById('row-kelompok-' + id);
            if(row) row.remove();
        });
}

// --- STATUS BADGE ---
function updateSaveStatus(state, msg) {
    const el = document.getElementById('kkm-save-status');
    if(!el) return;
    clearTimeout(saveStatusTimer);
    if(state === 'saving') {
        el.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-full border border-amber-200 animate-pulse';
        el.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (msg || 'Menyimpan & mengurutkan...');
    } else if(state === 'saved') {
        el.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full border border-emerald-200';
        el.innerHTML = '<i class="fas fa-check-circle"></i> ' + (msg || 'Tersimpan & sinkron rapor');
        saveStatusTimer = setTimeout(() => {
            el.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full border border-emerald-200';
            el.innerHTML = '<i class="fas fa-check-circle"></i> Realtime Auto-Save Aktif';
        }, 3000);
    } else if(state === 'error') {
        el.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-rose-50 text-rose-700 text-xs font-medium rounded-full border border-rose-200';
        el.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal menyimpan';
    }
}

// --- REALTIME SORTING ENGINE ---
// Mengurutkan berdasarkan:
// 1. Urutan Kelompok Mapel (Tanpa Kelompok = 9999)
// 2. Urutan Mapel (angka terkecil ke terbesar)
// 3. Nama Mapel (abjad A-Z)
function reorderMapelTable(highlightId = null) {
    const tbody = document.getElementById('mapel-tbody');
    if(!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('.mapel-row'));
    if(!rows.length) return;

    rows.sort((a, b) => {
        const kelA = a.querySelector('select[name="kelompok_id"]')?.value || '';
        const kelB = b.querySelector('select[name="kelompok_id"]')?.value || '';
        const kelUrutanA = (kelA && kelompokOrderMap[kelA]) ? kelompokOrderMap[kelA].urutan : 9999;
        const kelUrutanB = (kelB && kelompokOrderMap[kelB]) ? kelompokOrderMap[kelB].urutan : 9999;

        if (kelUrutanA !== kelUrutanB) {
            return kelUrutanA - kelUrutanB;
        }

        const urutA = parseInt(a.querySelector('input[name="urutan"]')?.value) || 9999;
        const urutB = parseInt(b.querySelector('input[name="urutan"]')?.value) || 9999;
        if (urutA !== urutB) {
            return urutA - urutB;
        }

        const namaA = (a.dataset.nama || '').toLowerCase();
        const namaB = (b.dataset.nama || '').toLowerCase();
        return namaA.localeCompare(namaB);
    });

    // Pasang ulang ke DOM dan beri nomor urut baru
    let no = 1;
    rows.forEach(row => {
        tbody.appendChild(row);
        const noEl = row.querySelector('.row-number');
        if(noEl) {
            noEl.textContent = no++;
        }
    });

    // Berikan efek highlight halus pada baris yang baru dipindah
    if(highlightId) {
        const targetRow = document.getElementById('row-mapel-' + highlightId);
        if(targetRow) {
            targetRow.classList.remove('bg-emerald-100', 'ring-2', 'ring-emerald-400');
            void targetRow.offsetWidth; // force reflow
            targetRow.classList.add('bg-emerald-100', 'ring-2', 'ring-emerald-400');
            setTimeout(() => {
                targetRow.classList.remove('bg-emerald-100', 'ring-2', 'ring-emerald-400');
            }, 1200);
        }
    }
}

// Stepper tombol panah atas / bawah
function stepUrutan(mapelId, delta) {
    const row = document.getElementById('row-mapel-' + mapelId);
    if(!row) return;
    const input = row.querySelector('input[name="urutan"]');
    let val = (parseInt(input.value) || 1) + delta;
    if(val < 1) val = 1;
    input.value = val;
    row.dataset.urutan = val;

    reorderMapelTable(mapelId);
    saveKKMRow(mapelId);
}

// Ketik di input urutan (debounced)
function onUrutanInput(mapelId) {
    clearTimeout(urutanInputTimer);
    urutanInputTimer = setTimeout(() => {
        onUrutanChange(mapelId);
    }, 500);
}

// Perubahan input urutan selesai
function onUrutanChange(mapelId) {
    clearTimeout(urutanInputTimer);
    const row = document.getElementById('row-mapel-' + mapelId);
    if(!row) return;
    const input = row.querySelector('input[name="urutan"]');
    let val = parseInt(input.value) || 1;
    if(val < 1) val = 1;
    input.value = val;
    row.dataset.urutan = val;

    reorderMapelTable(mapelId);
    saveKKMRow(mapelId);
}

// Perubahan kelompok mapel
function onKelompokChange(mapelId) {
    const row = document.getElementById('row-mapel-' + mapelId);
    if(!row) return;
    const select = row.querySelector('select[name="kelompok_id"]');
    row.dataset.kelompokId = select.value;

    reorderMapelTable(mapelId);
    saveKKMRow(mapelId);
}

// Simpan data satu baris via AJAX
function saveKKMRow(mapelId, btn = null) {
    const row = document.getElementById('row-mapel-' + mapelId);
    if(!row) return Promise.resolve();
    const saveBtn = btn || row.querySelector('.btn-save-row');
    const fd = new FormData();
    fd.append('action', 'save_kkm');
    fd.append('mapel_id', mapelId);
    fd.append('kkm', row.querySelector('input[name="kkm"]').value);
    fd.append('kelompok_id', row.querySelector('select[name="kelompok_id"]').value);
    fd.append('urutan', row.querySelector('input[name="urutan"]').value);

    updateSaveStatus('saving');
    if(saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    return fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(r => {
            updateSaveStatus('saved');
            if(saveBtn) {
                saveBtn.innerHTML = '<i class="fas fa-check"></i> <span>Tersimpan</span>';
                saveBtn.classList.replace('bg-emerald-600', 'bg-green-600');
                setTimeout(() => {
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> <span>Simpan</span>';
                    saveBtn.classList.replace('bg-green-600', 'bg-emerald-600');
                    saveBtn.disabled = false;
                }, 1500);
            }
            return r;
        })
        .catch(err => {
            console.error('Error saving mapel:', err);
            updateSaveStatus('error');
            if(saveBtn) {
                saveBtn.innerHTML = '<i class="fas fa-exclamation-circle"></i> Gagal';
                saveBtn.disabled = false;
            }
        });
}

// Tombol Simpan Manual pada baris
function saveKKM(btn, mapelId) {
    onUrutanChange(mapelId);
    saveKKMRow(mapelId, btn);
}

// Filter Pencarian dan Kelompok Mapel
function filterMapelTable() {
    const searchVal = (document.getElementById('kkm-search')?.value || '').toLowerCase().trim();
    const filterKel = document.getElementById('kkm-filter-kelompok')?.value || '';
    const rows = document.querySelectorAll('#mapel-tbody .mapel-row');

    let visibleCount = 0;
    rows.forEach(row => {
        const nama = (row.dataset.nama || '').toLowerCase();
        const kelId = row.querySelector('select[name="kelompok_id"]')?.value || '';

        const matchesSearch = !searchVal || nama.includes(searchVal);
        let matchesKel = true;
        if(filterKel === 'none') {
            matchesKel = (kelId === '');
        } else if(filterKel !== '') {
            matchesKel = (kelId === filterKel);
        }

        if(matchesSearch && matchesKel) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const badge = document.getElementById('mapel-count-badge');
    if(badge) {
        badge.textContent = `Menampilkan ${visibleCount} dari ${rows.length} Mapel`;
    }
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
        .then(r => r.json()).then(r => { 
            if(r.status==='success') {
                localStorage.setItem('erapor_active_tab', 'tab-ekskul');
                location.reload(); 
            }
        });
});

function deleteEkskul(id, btn) {
    if(!confirm('Hapus ekskul ini?')) return;
    const fd = new FormData();
    fd.append('action', 'delete_ekskul');
    fd.append('id', id);
    fetch(BASE, { method: 'POST', body: fd })
        .then(r => r.json()).then(() => {
            const row = document.getElementById('row-ekskul-' + id);
            if(row) row.remove();
        });
}
</script>
