<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Minimalist -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Ringkasan Sistem</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau seluruh aktivitas akademik SMA Nahdlatul Wathan Jakarta dari satu tempat.</p>
        </div>
        <div class="px-4 py-2 bg-blue-50 text-blue-600 font-semibold rounded-lg text-sm">
            Tahun Ajaran: <?= htmlspecialchars($data['tahun_ajaran']); ?>
        </div>
    </div>

    <!-- Stats Grid Minimalist -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all group">
            <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-400 mb-1">Siswa Aktif</p>
                <p class="text-3xl font-bold text-slate-800 tracking-tight"><?= number_format($data['total_siswa_aktif']); ?></p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all group">
            <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-400 mb-1">Total Alumni</p>
                <p class="text-3xl font-bold text-slate-800 tracking-tight"><?= number_format($data['total_alumni']); ?></p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all group">
            <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-globe text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-400 mb-1">Siswa + Alumni</p>
                <p class="text-3xl font-bold text-slate-800 tracking-tight"><?= number_format($data['total_keseluruhan']); ?></p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all group">
            <div class="w-14 h-14 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-chalkboard-teacher text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-400 mb-1">Total Guru</p>
                <p class="text-3xl font-bold text-slate-800 tracking-tight"><?= number_format($data['total_guru']); ?></p>
            </div>
        </div>
    </div>

    <?php if(in_array($_SESSION['user']['role'], ['admin', 'guru'])): ?>
    <!-- Quick Actions untuk Absensi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Kiosk Guru -->
        <?php if($_SESSION['user']['role'] == 'admin'): ?>
        <a href="<?= BASEURL; ?>/kioskabsensi" class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-6 rounded-2xl shadow-sm text-white flex items-center justify-between hover:shadow-lg transition-all group overflow-hidden relative">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
            <div>
                <h3 class="text-xl font-bold mb-1 flex items-center gap-2">
                    <i class="fas fa-desktop"></i> Kiosk Absensi Guru
                </h3>
                <p class="text-indigo-100 text-sm">Buka mode layar penuh untuk presensi guru.</p>
            </div>
            <i class="fas fa-chevron-right text-xl opacity-70 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
        </a>
        <?php endif; ?>

        <!-- Scanner Siswa -->
        <a href="<?= BASEURL; ?>/scannerabsensi" class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-2xl shadow-sm text-white flex items-center justify-between hover:shadow-lg transition-all group overflow-hidden relative">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
            <div>
                <h3 class="text-xl font-bold mb-1 flex items-center gap-2">
                    <i class="fas fa-qrcode"></i> Scanner Presensi Siswa
                </h3>
                <p class="text-blue-100 text-sm">Buka kamera untuk scan QR Code absensi siswa.</p>
            </div>
            <i class="fas fa-chevron-right text-xl opacity-70 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>
    <?php endif; ?>

    <?php if($_SESSION['user']['role'] == 'siswa' && !empty($data['qr_token'])): ?>
    <!-- Student QR Code Section -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-8 rounded-2xl shadow-lg border border-purple-200 flex flex-col md:flex-row items-center justify-between gap-8 mt-8 text-white relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-black/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 max-w-lg">
            <h3 class="text-3xl font-extrabold mb-2 flex items-center gap-3">
                <i class="fas fa-qrcode"></i> Kartu Presensi Digital
            </h3>
            <p class="text-purple-100 text-lg mb-6">Tunjukkan QR Code ini pada mesin pemindai (scanner) untuk mencatat kehadiran Anda hari ini.</p>
            <div class="flex flex-wrap gap-4">
                <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=<?= $data['qr_token'] ?>" download="QRCode_Saya.png" target="_blank" class="px-5 py-2.5 bg-white text-purple-700 font-bold rounded-xl shadow hover:bg-slate-50 transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-download"></i> Unduh QR
                </a>
            </div>
        </div>
        
        <div class="relative z-10 shrink-0 bg-white p-4 rounded-2xl shadow-2xl">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= $data['qr_token'] ?>" alt="QR Code Absensi" class="w-48 h-48 rounded-lg">
        </div>
    </div>
    <?php endif; ?>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Gender Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Proporsi Jenis Kelamin (Siswa Aktif)</h3>
            <div class="relative h-64 w-full">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        <!-- Class Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Distribusi Siswa per Kelas (Aktif)</h3>
            <div class="relative h-64 w-full">
                <canvas id="kelasChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for Gender Chart
        <?php
        $genderL = 0;
        $genderP = 0;
        foreach($data['chart_gender'] as $g) {
            if($g['jenis_kelamin'] == 'L') $genderL = $g['jumlah'];
            if($g['jenis_kelamin'] == 'P') $genderP = $g['jumlah'];
        }
        ?>
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [<?= $genderL ?>, <?= $genderP ?>],
                    backgroundColor: ['#3b82f6', '#ec4899'], // Blue and Pink
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%'
            }
        });

        // Data for Class Chart
        <?php
        $kelasLabels = [];
        $kelasData = [];
        foreach($data['chart_kelas'] as $k) {
            $kelasLabels[] = $k['nama_kelas'];
            $kelasData[] = $k['jumlah'];
        }
        ?>
        const kelasCtx = document.getElementById('kelasChart').getContext('2d');
        new Chart(kelasCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($kelasLabels) ?>,
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: <?= json_encode($kelasData) ?>,
                    backgroundColor: '#10b981', // Emerald
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 5 }
                    }
                }
            }
        });
    });
</script>
