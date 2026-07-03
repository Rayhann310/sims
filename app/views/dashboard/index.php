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
