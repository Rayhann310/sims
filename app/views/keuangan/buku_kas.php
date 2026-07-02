<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="bukuKasApp()">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Pantau arus kas, laporan keuangan, dan grafik statistik secara real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASEURL; ?>/keuangan/exportExcelKas?bulan=<?= $data['filter_bulan']; ?>&tahun=<?= $data['filter_tahun']; ?>" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <button @click="modalTransaksi = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pemasukan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-4">
                <i class="fas fa-arrow-down text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pemasukan (Total)</p>
                <h3 class="text-2xl font-bold text-slate-900">Rp <?= number_format($data['statistik']['total_pemasukan'], 0, ',', '.'); ?></h3>
                <p class="text-xs text-emerald-600 mt-1"><i class="fas fa-chart-line"></i> Rp <?= number_format($data['statistik']['pemasukan_bulan_ini'], 0, ',', '.'); ?> bulan ini</p>
            </div>
        </div>
        <!-- Pengeluaran -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mr-4">
                <i class="fas fa-arrow-up text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pengeluaran (Total)</p>
                <h3 class="text-2xl font-bold text-slate-900">Rp <?= number_format($data['statistik']['total_pengeluaran'], 0, ',', '.'); ?></h3>
                <p class="text-xs text-rose-600 mt-1"><i class="fas fa-chart-line"></i> Rp <?= number_format($data['statistik']['pengeluaran_bulan_ini'], 0, ',', '.'); ?> bulan ini</p>
            </div>
        </div>
        <!-- Saldo Kas -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center relative overflow-hidden">
            <div class="absolute right-0 top-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50"></div>
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mr-4 relative z-10">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-medium text-slate-500">Saldo Kas Akhir</p>
                <h3 class="text-2xl font-bold text-indigo-900">Rp <?= number_format($data['statistik']['saldo_akhir'], 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <!-- Chart & Filter Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Arus Kas Tahun <?= date('Y') ?></h3>
            <div class="relative h-72">
                <canvas id="kasChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Filter Laporan</h3>
            <form action="<?= BASEURL; ?>/keuangan/bukuKas" method="GET" class="flex-1 flex flex-col">
                <div class="space-y-4 flex-1">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                        <select name="bulan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">Semua Bulan</option>
                            <?php 
                            $bulans = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                            foreach($bulans as $num => $name): ?>
                                <option value="<?= str_pad($num, 2, '0', STR_PAD_LEFT); ?>" <?= ($data['filter_bulan'] == str_pad($num, 2, '0', STR_PAD_LEFT)) ? 'selected' : ''; ?>><?= $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                        <select name="tahun" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                            <?php 
                            $currentYear = date('Y');
                            for($y = $currentYear; $y >= $currentYear - 5; $y--): ?>
                                <option value="<?= $y; ?>" <?= ($data['filter_tahun'] == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">Terapkan Filter</button>
                    <a href="<?= BASEURL; ?>/keuangan/bukuKas?filter=semua" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg font-medium hover:bg-slate-200 transition-colors" title="Reset">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Buku Kas -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg">Rincian Buku Kas</h3>
            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Cari transaksi..." class="pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-64">
                <i class="fas fa-search absolute left-3 top-2.5 text-slate-400"></i>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sumber / Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Pemasukan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengeluaran</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <template x-if="filteredKas.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">Tidak ada data transaksi ditemukan.</td>
                        </tr>
                    </template>
                    <template x-for="item in filteredKas" :key="item.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900" x-text="formatDate(item.tanggal)"></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-slate-900" x-text="item.sumber"></div>
                                <div class="text-slate-500 text-xs mt-0.5 line-clamp-1" x-text="item.keterangan || '-'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="item.jenis === 'Pemasukan' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                      x-text="item.jenis"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-emerald-600">
                                <span x-show="item.jenis === 'Pemasukan'" x-text="'Rp ' + formatRupiah(item.nominal)"></span>
                                <span x-show="item.jenis !== 'Pemasukan'">-</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-rose-600">
                                <span x-show="item.jenis === 'Pengeluaran'" x-text="'Rp ' + formatRupiah(item.nominal)"></span>
                                <span x-show="item.jenis !== 'Pengeluaran'">-</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <template x-if="item.sumber !== 'Pembayaran SPP'">
                                    <a :href="'<?= BASEURL; ?>/keuangan/hapusKas/' + item.id" onclick="return confirm('Hapus transaksi ini?');" class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-md transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </template>
                                <template x-if="item.sumber === 'Pembayaran SPP'">
                                    <span class="text-xs text-slate-400 cursor-not-allowed" title="Hapus dari menu Riwayat Bayar SPP">Auto (SPP)</span>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div x-show="modalTransaksi" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalTransaksi" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalTransaksi = false"></div>
            <div x-show="modalTransaksi" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Catat Transaksi Manual</h3>
                    <button @click="modalTransaksi = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/keuangan/prosesTambahKas" method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Transaksi</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="jenis" value="Pemasukan" class="peer sr-only" checked>
                                    <div class="rounded-lg border border-slate-200 px-4 py-3 hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:ring-1 peer-checked:ring-emerald-500 transition-all flex flex-col items-center">
                                        <i class="fas fa-arrow-down text-emerald-500 text-xl mb-1"></i>
                                        <span class="text-sm font-medium text-slate-900">Pemasukan</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="jenis" value="Pengeluaran" class="peer sr-only">
                                    <div class="rounded-lg border border-slate-200 px-4 py-3 hover:bg-slate-50 peer-checked:border-rose-500 peer-checked:ring-1 peer-checked:ring-rose-500 transition-all flex flex-col items-center">
                                        <i class="fas fa-arrow-up text-rose-500 text-xl mb-1"></i>
                                        <span class="text-sm font-medium text-slate-900">Pengeluaran</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Sumber / Kategori</label>
                            <input type="text" name="sumber" required placeholder="Contoh: Dana BOS, Gaji Guru, Listrik" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-lg font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan Tambahan (Opsional)</label>
                            <textarea name="keterangan" rows="2" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="modalTransaksi = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bukuKasApp', () => ({
        modalTransaksi: false,
        searchQuery: '',
        kasData: <?= json_encode($data['kas'] ?? []) ?>,
        
        get filteredKas() {
            if (this.searchQuery === '') {
                return this.kasData;
            }
            const q = this.searchQuery.toLowerCase();
            return this.kasData.filter(item => {
                return (item.sumber && item.sumber.toLowerCase().includes(q)) || 
                       (item.keterangan && item.keterangan.toLowerCase().includes(q)) ||
                       (item.jenis && item.jenis.toLowerCase().includes(q));
            });
        },
        
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        },
        
        formatDate(dateStr) {
            if(!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }
    }))
});

// Render Chart.js
const chartData = <?= json_encode($data['chart']) ?>;
const ctx = document.getElementById('kasChart').getContext('2d');

const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const pemasukanData = Object.values(chartData).map(d => d.pemasukan);
const pengeluaranData = Object.values(chartData).map(d => d.pengeluaran);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: pemasukanData,
                backgroundColor: 'rgba(16, 185, 129, 0.8)', // Emerald
                borderRadius: 4,
                barPercentage: 0.7
            },
            {
                label: 'Pengeluaran',
                data: pengeluaranData,
                backgroundColor: 'rgba(244, 63, 94, 0.8)', // Rose
                borderRadius: 4,
                barPercentage: 0.7
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) label += ': ';
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false,
                    color: 'rgba(0,0,0,0.05)'
                },
                ticks: {
                    callback: function(value) {
                        if (value === 0) return '0';
                        return 'Rp ' + (value / 1000000) + ' Jt';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
