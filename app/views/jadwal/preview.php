<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="previewData()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Preview Jadwal Otomatis</h1>
            <p class="text-sm text-slate-500 mt-1">Kelas: <span class="font-bold text-slate-700"><?= $data['rombel']['nama_rombel'] ?></span>. Periksa dan sesuaikan jadwal (Drag & Drop) sebelum disimpan.</p>
        </div>
        <div class="flex space-x-2">
            <a href="<?= BASEURL; ?>/jadwal/autoGenerate?rombel_id=<?= $data['rombel']['id'] ?>&ta_id=<?= $_SESSION['auto_generate_data']['ta_id'] ?>" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm flex items-center gap-2">
                <i class="fas fa-undo"></i> Ulangi
            </a>
            <form action="<?= BASEURL; ?>/jadwal/simpanJadwalOtomatis" method="POST" id="formSimpan">
                <!-- Kita akan mengirim grid hasil editan melalui AJAX atau input hidden. Tapi karena ini MVP, kita save dari session jika tidak ada perubahan, ATAU kita kirim grid baru. -->
                <button type="button" @click="saveToServer" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                    <i class="fas fa-save"></i> Simpan ke Database
                </button>
            </form>
        </div>
    </div>

    <?php if(!empty($data['unplaced'])): ?>
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <h3 class="text-red-800 font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i> Ada Pelajaran yang Tidak Mendapat Jam (Bentrok Guru / Penuh)</h3>
        <ul class="list-disc list-inside text-sm text-red-700">
            <?php foreach($data['unplaced'] as $u): ?>
                <li><?= $u['nama_mapel'] ?> (Sisa <?= $u['sisa_jp'] ?> JP) - Guru: <?= $u['nama_guru'] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-3 font-semibold text-slate-700 border-r border-slate-200 w-24 text-center">Jam</th>
                        <?php foreach($data['hari_aktif'] as $hari): ?>
                            <th class="p-3 font-semibold text-slate-700 border-r border-slate-200 text-center w-48"><?= $hari ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Kita perlu pivot grid dari (Hari -> Jam) menjadi (Jam -> Hari)
                    // Ambil daftar kunci jam dari hari pertama sebagai acuan baris
                    $jam_keys = array_keys($data['grid'][$data['hari_aktif'][0]]);
                    ?>
                    <?php foreach($jam_keys as $jk): ?>
                        <tr class="border-b border-slate-100">
                            <!-- Kolom Waktu -->
                            <?php 
                            $refSlot = $data['grid'][$data['hari_aktif'][0]][$jk]; 
                            ?>
                            <td class="p-3 border-r border-slate-200 bg-slate-50 text-center align-middle">
                                <?php if($refSlot['type'] == 'jp'): ?>
                                    <div class="font-bold text-slate-700 text-base">JP <?= $refSlot['jp'] ?></div>
                                <?php else: ?>
                                    <div class="font-bold text-amber-600 text-xs uppercase"><i class="fas fa-coffee"></i></div>
                                <?php endif; ?>
                                <div class="text-[10px] text-slate-500 mt-1"><?= $refSlot['jam_mulai'] ?> - <?= $refSlot['jam_selesai'] ?></div>
                            </td>
                            
                            <!-- Kolom Hari-Hari -->
                            <?php foreach($data['hari_aktif'] as $hari): ?>
                                <?php $slot = $data['grid'][$hari][$jk]; ?>
                                
                                <td class="p-2 border-r border-slate-200 align-top <?= $slot['type'] == 'break' ? 'bg-amber-50' : 'bg-white' ?>" 
                                    <?= $slot['type'] == 'jp' ? 'data-hari="'.$hari.'" data-jk="'.$jk.'" x-ref="dropzone"' : '' ?> >
                                    
                                    <?php if($slot['type'] == 'break'): ?>
                                        <div class="flex items-center justify-center h-full text-amber-700 font-bold text-xs uppercase tracking-wider py-4">
                                            <?= $slot['name'] ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="min-h-[80px] rounded-lg p-2 transition-colors border-2 border-dashed border-transparent hover:border-blue-300 dropzone-area" 
                                             @dragover.prevent="dragOver($event)" 
                                             @dragleave.prevent="dragLeave($event)" 
                                             @drop.prevent="drop($event, '<?= $hari ?>', '<?= $jk ?>')">
                                            
                                            <?php if(!empty($slot['mapel_id'])): ?>
                                                <div class="bg-blue-50 border border-blue-200 p-2 rounded cursor-grab active:cursor-grabbing shadow-sm mapel-card"
                                                     draggable="true" 
                                                     @dragstart="dragStart($event, '<?= $hari ?>', '<?= $jk ?>')"
                                                     @dragend="dragEnd($event)"
                                                     data-mapel="<?= $slot['mapel_id'] ?>" data-guru="<?= $slot['guru_id'] ?>">
                                                    <div class="font-bold text-blue-900 text-xs leading-tight mb-1"><?= $slot['nama_mapel'] ?></div>
                                                    <div class="text-[10px] text-blue-700"><i class="fas fa-user mr-1"></i><?= $slot['nama_guru'] ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('previewData', () => ({
        gridData: <?= json_encode($data['grid']) ?>,
        draggedItem: null,
        draggedFrom: null,

        dragStart(e, hari, jk) {
            e.dataTransfer.effectAllowed = 'move';
            this.draggedFrom = { hari, jk };
            this.draggedItem = this.gridData[hari][jk];
            setTimeout(() => {
                e.target.classList.add('opacity-50');
            }, 0);
        },

        dragEnd(e) {
            e.target.classList.remove('opacity-50');
            document.querySelectorAll('.dropzone-area').forEach(el => el.classList.remove('bg-blue-50'));
            this.draggedItem = null;
            this.draggedFrom = null;
        },

        dragOver(e) {
            e.currentTarget.classList.add('bg-blue-50');
        },

        dragLeave(e) {
            e.currentTarget.classList.remove('bg-blue-50');
        },

        drop(e, targetHari, targetJk) {
            e.currentTarget.classList.remove('bg-blue-50');
            if(!this.draggedFrom) return;

            const fromHari = this.draggedFrom.hari;
            const fromJk = this.draggedFrom.jk;

            // Swap logic in Alpine state
            const targetItemCopy = JSON.parse(JSON.stringify(this.gridData[targetHari][targetJk]));
            const draggedItemCopy = JSON.parse(JSON.stringify(this.gridData[fromHari][fromJk]));

            // Preserve time/type information of the cells, only swap mapel & guru data
            this.gridData[targetHari][targetJk].mapel_id = draggedItemCopy.mapel_id;
            this.gridData[targetHari][targetJk].guru_id = draggedItemCopy.guru_id;
            this.gridData[targetHari][targetJk].nama_mapel = draggedItemCopy.nama_mapel;
            this.gridData[targetHari][targetJk].nama_guru = draggedItemCopy.nama_guru;

            this.gridData[fromHari][fromJk].mapel_id = targetItemCopy.mapel_id;
            this.gridData[fromHari][fromJk].guru_id = targetItemCopy.guru_id;
            this.gridData[fromHari][fromJk].nama_mapel = targetItemCopy.nama_mapel;
            this.gridData[fromHari][fromJk].nama_guru = targetItemCopy.nama_guru;

            // Update DOM manually since Alpine x-for inside tables can be tricky if not set up with <template>
            // For MVP, we will just reload the page with new grid state in session, 
            // OR use AJAX to update session and then we could reload.
            // Let's do AJAX update to session.
            this.updateSessionGrid();
        },

        async updateSessionGrid() {
            // Update session grid via AJAX
            try {
                const fd = new FormData();
                fd.append('grid', JSON.stringify(this.gridData));
                const res = await fetch('<?= BASEURL; ?>/jadwal/updateSessionGrid', {
                    method: 'POST',
                    body: fd
                });
                if(res.ok) {
                    window.location.reload();
                }
            } catch (err) {
                console.error(err);
            }
        },

        saveToServer() {
            document.getElementById('formSimpan').submit();
        }
    }));
});
</script>
