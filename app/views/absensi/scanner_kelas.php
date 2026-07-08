<div class="space-y-6" x-data="scannerKelasData()">

    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h1 class="text-2xl font-bold text-slate-800">Scanner & Absensi Kelas</h1>
        <p class="text-sm text-slate-500 mt-1">Gunakan fitur ini untuk mencatat kehadiran siswa pada jam pelajaran Anda.</p>
    </div>

    <!-- Parameter Filter -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Jam Pelajaran Ke-</label>
            <select x-model="jamKe" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                <option value="">-- Pilih Jam Pelajaran --</option>
                <template x-for="i in 10">
                    <option :value="i" x-text="`Jam Ke-${i}`"></option>
                </template>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kelas / Rombel</label>
            <select x-model="rombelId" @change="loadSiswa()" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($data['rombel'] as $r): ?>
                <option value="<?= $r['id'] ?>"><?= $r['grade'] . ' ' . $r['jurusan'] . ' ' . $r['nama_kelas'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div x-show="jamKe && rombelId" style="display: none;">
        <!-- Tabs -->
        <div class="flex gap-4 border-b border-slate-200 mb-4">
            <button @click="activeTab = 'manual'" :class="activeTab == 'manual' ? 'border-b-2 border-indigo-600 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-3 text-sm transition-colors">
                <i class="fas fa-list-check mr-2"></i> Input Manual
            </button>
            <button @click="switchTab('scan')" :class="activeTab == 'scan' ? 'border-b-2 border-indigo-600 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-3 text-sm transition-colors">
                <i class="fas fa-qrcode mr-2"></i> Scan QR
            </button>
        </div>

        <!-- Tab Manual -->
        <div x-show="activeTab == 'manual'" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800">Daftar Siswa</h3>
                <div class="text-sm text-slate-500">Klik status untuk mengubah.</div>
            </div>
            
            <div x-show="isLoading" class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-indigo-500"></i>
                <p class="mt-2 text-slate-500">Memuat data siswa...</p>
            </div>

            <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="s in siswaList" :key="s.id">
                    <div class="border border-slate-200 rounded-xl p-4 flex flex-col justify-between">
                        <div>
                            <p class="font-bold text-slate-800 line-clamp-1" x-text="s.nama_lengkap"></p>
                            <p class="text-xs text-slate-500" x-text="s.nisn"></p>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button @click="submitManual(s.id, 'Hadir')" class="flex-1 py-1 rounded text-xs font-bold transition-colors" :class="s.status == 'Hadir' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'">H</button>
                            <button @click="submitManual(s.id, 'Sakit')" class="flex-1 py-1 rounded text-xs font-bold transition-colors" :class="s.status == 'Sakit' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'">S</button>
                            <button @click="submitManual(s.id, 'Izin')" class="flex-1 py-1 rounded text-xs font-bold transition-colors" :class="s.status == 'Izin' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'">I</button>
                            <button @click="submitManual(s.id, 'Alpa')" class="flex-1 py-1 rounded text-xs font-bold transition-colors" :class="s.status == 'Alpa' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'">A</button>
                        </div>
                    </div>
                </template>
                <div x-show="siswaList.length === 0" class="col-span-full text-center py-8 text-slate-500">
                    Tidak ada data siswa pada kelas ini.
                </div>
            </div>
        </div>

        <!-- Tab Scan QR -->
        <div x-show="activeTab == 'scan'" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col items-center">
            <h3 class="text-lg font-bold text-slate-800 mb-4 text-center">Arahkan QR Code Siswa ke Kamera</h3>
            <div id="reader" class="w-full max-w-sm rounded-xl overflow-hidden border-2 border-slate-200 bg-slate-100 mb-4"></div>
            
            <div class="w-full max-w-md space-y-2 mt-4 max-h-48 overflow-y-auto" id="scan-results">
                <!-- Log hasil scan akan muncul di sini -->
            </div>
        </div>
    </div>

    <!-- Alert belum pilih filter -->
    <div x-show="!jamKe || !rombelId" class="text-center py-12 bg-white rounded-2xl border border-slate-200 border-dashed text-slate-400">
        <i class="fas fa-hand-pointer text-4xl mb-3"></i>
        <p>Silakan pilih Jam Pelajaran dan Kelas untuk mulai.</p>
    </div>

</div>

<!-- Html5Qrcode Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
function scannerKelasData() {
    return {
        jamKe: '',
        rombelId: '',
        activeTab: 'manual',
        siswaList: [],
        isLoading: false,
        html5QrcodeScanner: null,

        async loadSiswa() {
            if (!this.rombelId) return;
            this.isLoading = true;
            try {
                const res = await fetch('<?= BASEURL; ?>/ScannerKelas/getSiswaByRombel/' + this.rombelId);
                const result = await res.json();
                if(result.status) {
                    this.siswaList = result.data.map(s => ({
                        ...s,
                        status: null // Default belum diset
                    }));
                }
            } catch (e) {
                console.error(e);
            }
            this.isLoading = false;
        },

        async submitManual(siswa_id, status) {
            // Optimistic update
            const index = this.siswaList.findIndex(s => s.id === siswa_id);
            if (index !== -1) {
                this.siswaList[index].status = status;
            }

            // Sync ke server
            try {
                await fetch('<?= BASEURL; ?>/ScannerKelas/submitAbsen', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        siswa_id: siswa_id,
                        jam_ke: this.jamKe,
                        status: status
                    })
                });
            } catch (e) {
                console.error(e);
            }
        },

        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'scan') {
                setTimeout(() => this.initScanner(), 100);
            } else {
                if (this.html5QrcodeScanner) {
                    this.html5QrcodeScanner.clear();
                    this.html5QrcodeScanner = null;
                }
            }
        },

        initScanner() {
            if (this.html5QrcodeScanner) return;

            this.html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                /* verbose= */ false
            );

            this.html5QrcodeScanner.render(this.onScanSuccess.bind(this), this.onScanFailure.bind(this));
        },

        async onScanSuccess(decodedText, decodedResult) {
            // Pause scanning while submitting
            this.html5QrcodeScanner.pause();

            try {
                const res = await fetch('<?= BASEURL; ?>/ScannerKelas/submitAbsen', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        qr_token: decodedText,
                        jam_ke: this.jamKe
                    })
                });
                const result = await res.json();

                const resultsDiv = document.getElementById('scan-results');
                const p = document.createElement('div');
                p.className = `p-3 rounded-lg text-sm font-semibold border ${result.status ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700'}`;
                p.innerHTML = `<i class="fas ${result.status ? 'fa-check-circle' : 'fa-times-circle'} mr-2"></i> ${result.pesan}`;
                resultsDiv.prepend(p);

                // Hapus log lama biar gak numpuk
                if (resultsDiv.children.length > 5) {
                    resultsDiv.lastChild.remove();
                }

                // Resume
                setTimeout(() => {
                    this.html5QrcodeScanner.resume();
                }, 2000);

            } catch (e) {
                console.error(e);
                this.html5QrcodeScanner.resume();
            }
        },

        onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
        }
    }
}
</script>
