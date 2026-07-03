<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Monitor aktivitas peserta di ruangan ujian ini secara real-time.</p>
            
            <div class="mt-4 flex flex-wrap gap-2 text-sm text-slate-700 bg-white p-3 rounded-lg border border-slate-200 shadow-sm inline-flex items-center">
                <span class="font-bold text-slate-900"><i class="fas fa-file-alt text-indigo-500 mr-1"></i> <?= htmlspecialchars($data['jadwal']['nama_ujian'] ?? ''); ?></span>
                <span class="text-slate-300">|</span>
                <span class="font-medium"><i class="fas fa-book text-emerald-500 mr-1"></i> <?= htmlspecialchars($data['jadwal']['nama_mapel'] ?? $data['jadwal']['id_mapel']); ?></span>
                <?php if(!empty($data['jadwal']['nama_rombel'])): ?>
                <span class="text-slate-300">|</span>
                <span class="font-medium text-amber-700"><i class="fas fa-users mr-1"></i> Kelas: <?= htmlspecialchars($data['jadwal']['nama_rombel']); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex flex-col items-end gap-2">
            <a href="<?= BASEURL; ?>/Proctor" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            
            <!-- TOKEN BOX -->
            <div class="bg-indigo-600 rounded-lg shadow-md border border-indigo-700 p-4 text-center mt-2 w-48 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 opacity-50 transform -skew-x-12 translate-x-full"></div>
                <div class="text-indigo-100 text-xs font-semibold mb-1 uppercase tracking-wider">Token Ujian Aktif</div>
                <div id="tokenDisplay" class="text-3xl font-black text-white tracking-widest bg-indigo-800/50 py-2 rounded mb-2 border border-indigo-500/30">
                    <?= $data['jadwal']['token_aktif'] ?? '------'; ?>
                </div>
                <button id="btnRefreshToken" class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-white text-indigo-600 hover:bg-indigo-50 text-xs font-bold rounded transition-colors">
                    <i class="fas fa-sync-alt mr-1.5"></i> Perbarui Token
                </button>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-amber-100 border-b border-amber-200 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-users text-amber-600 text-xl mr-3"></i>
                <h3 class="text-amber-800 font-bold">Monitor Peserta <span class="font-normal text-amber-700 text-sm ml-2">(Auto-update setiap 5 detik)</span></h3>
            </div>
            <div class="flex items-center">
                <span class="relative flex h-3 w-3 mr-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-semibold text-emerald-700">Live</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan Sistem</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pesertaTableBody" class="divide-y divide-slate-200 bg-white">
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const idJadwal = <?= $data['id_jadwal']; ?>;
    const baseUrl = '<?= BASEURL; ?>';
    const tbody = document.getElementById('pesertaTableBody');
    const tokenDisplay = document.getElementById('tokenDisplay');
    const btnRefresh = document.getElementById('btnRefreshToken');
    
    // Function to render table rows
    function renderTable(pesertaList) {
        if (!pesertaList || pesertaList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada peserta yang tergabung di ruangan ini.</td></tr>';
            return;
        }
        
        let html = '';
        pesertaList.forEach((p, index) => {
            const isTerkunci = p.status_ujian === '2';
            const rowClass = isTerkunci ? 'bg-rose-50/50' : 'hover:bg-slate-50';
            
            // Status Badge
            let statusBadge = '';
            if(p.status_ujian === '1') {
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Sedang Mengerjakan</span>';
            } else if(p.status_ujian === '2') {
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 animate-pulse">Terkunci</span>';
            } else if(p.status_ujian === '3') {
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Selesai</span>';
            } else {
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Belum Mulai</span>';
            }
            
            // Keterangan
            let keterangan = isTerkunci ? `<span class="text-rose-600 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i> ${p.alasan_terkunci || ''}</span>` : `<span class="text-slate-400">-</span>`;
            
            // Aksi Button
            let aksiBtn = '';
            if(isTerkunci) {
                aksiBtn = `<a href="${baseUrl}/Proctor/unlockSiswa/${p.id_peserta}/${idJadwal}" 
                             class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-md transition-colors"
                             onclick="return confirm('Yakin ingin membuka akses ujian siswa ini?');">
                             <i class="fas fa-unlock mr-1"></i> Buka Kunci
                          </a>`;
            } else {
                aksiBtn = `<button class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-medium rounded-md cursor-not-allowed" disabled>
                              <i class="fas fa-lock mr-1"></i> Buka Kunci
                           </button>`;
            }
            
            html += `<tr class="${rowClass}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-bold text-slate-900">${p.nama_lengkap}</div></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">${p.nisn}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">${statusBadge}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${keterangan}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">${aksiBtn}</td>
                     </tr>`;
        });
        
        tbody.innerHTML = html;
    }
    
    // Fetch data from server
    function fetchMonitorData() {
        fetch(`${baseUrl}/Proctor/getMonitorData/${idJadwal}`)
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    renderTable(data.peserta);
                    if(data.token && data.token !== '------' && tokenDisplay.innerText.trim() !== data.token) {
                        tokenDisplay.innerText = data.token;
                        tokenDisplay.classList.add('bg-white', 'text-indigo-900');
                        setTimeout(() => tokenDisplay.classList.remove('bg-white', 'text-indigo-900'), 500);
                    }
                }
            })
            .catch(error => console.error('Error fetching monitor data:', error));
    }
    
    // Initial fetch
    fetchMonitorData();
    
    // Set interval for polling (every 5 seconds)
    setInterval(fetchMonitorData, 5000);
    
    // Refresh Token Action
    btnRefresh.addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Memproses...';
        btn.disabled = true;
        
        fetch(`${baseUrl}/Proctor/refreshToken/${idJadwal}`)
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    tokenDisplay.innerText = data.token;
                    // Add some animation
                    tokenDisplay.classList.add('scale-110');
                    setTimeout(() => tokenDisplay.classList.remove('scale-110'), 300);
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error generating token:', error);
                alert('Terjadi kesalahan koneksi.');
            })
            .finally(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
    });
});
</script>
