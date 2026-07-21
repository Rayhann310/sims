<?php
$modeAbsen = $data['pengaturan']['mode_absen_siswa'] ?? 'Masuk Saja';
$isMasukPulang = ($modeAbsen === 'Masuk & Pulang');
$isPerMapel    = ($modeAbsen === 'Per Mata Pelajaran');
?>

<div class="space-y-5"
     x-data="absensiSiswaApp()"
     x-init="init()">

    <!-- ===== HEADER ===== -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Absensi Siswa Harian</h1>
            <p class="text-sm text-slate-500 mt-1">
                Catat kehadiran siswa per hari.
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                    <?= $isMasukPulang ? 'bg-violet-100 text-violet-700' : ($isPerMapel ? 'bg-sky-100 text-sky-700' : 'bg-emerald-100 text-emerald-700') ?>">
                    <i class="fas <?= $isMasukPulang ? 'fa-exchange-alt' : ($isPerMapel ? 'fa-chalkboard-teacher' : 'fa-sign-in-alt') ?>"></i>
                    <?= htmlspecialchars($modeAbsen) ?>
                </span>
            </p>
        </div>

        <!-- Tombol Scan QR (opsional) -->
        <?php if (!$isPerMapel): ?>
        <button @click="openScanModal()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-indigo-200 transition-all duration-200 shrink-0">
            <i class="fas fa-qrcode"></i>
            Scan QR (Opsional)
        </button>
        <?php endif; ?>
    </div>

    <?php if ($isPerMapel): ?>
    <!-- Info: mode per mapel -->
    <div class="bg-sky-50 border border-sky-200 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-sky-500 flex items-center justify-center shrink-0">
            <i class="fas fa-info text-white"></i>
        </div>
        <div>
            <p class="font-bold text-sky-800">Mode Per Mata Pelajaran Aktif</p>
            <p class="text-sm text-sky-600 mt-0.5">Absensi siswa dilakukan oleh guru di tiap kelas melalui menu <strong>Absensi Kelas</strong>. Halaman ini tidak digunakan untuk input absensi.</p>
            <a href="<?= BASEURL ?>/ScannerKelas" class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-sky-600 text-white text-sm rounded-lg hover:bg-sky-700 font-semibold transition-colors">
                <i class="fas fa-arrow-right"></i> Buka Absensi Kelas
            </a>
        </div>
    </div>

    <?php else: ?>

    <!-- ===== FILTER KELAS & TANGGAL ===== -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kelas / Rombel</label>
                <select x-model="rombelId" @change="loadSiswa()"
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-sm">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($data['rombels'] as $r): ?>
                    <option value="<?= $r['id'] ?>">[Kls <?= $r['tingkat'] ?>] <?= htmlspecialchars($r['nama_rombel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal</label>
                <input type="date" x-model="tanggal" @change="loadSiswa()"
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-sm">
            </div>
            <?php if ($isMasukPulang): ?>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tipe Absen</label>
                <div class="flex gap-2">
                    <button @click="tipeAbsen = 'masuk'; loadSiswa()"
                            :class="tipeAbsen === 'masuk' ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-slate-600 border-slate-300 hover:border-emerald-400'"
                            class="flex-1 py-2.5 rounded-lg border-2 text-sm font-bold transition-all duration-150">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                    </button>
                    <button @click="tipeAbsen = 'pulang'; loadSiswa()"
                            :class="tipeAbsen === 'pulang' ? 'bg-violet-500 text-white border-violet-500' : 'bg-white text-slate-600 border-slate-300 hover:border-violet-400'"
                            class="flex-1 py-2.5 rounded-lg border-2 text-sm font-bold transition-all duration-150">
                        <i class="fas fa-sign-out-alt mr-1"></i> Pulang
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== STATISTIK CEPAT ===== -->
    <div x-show="siswaList.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0">
                <i class="fas fa-check text-white text-sm"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-emerald-700" x-text="countByStatus('Hadir')"></p>
                <p class="text-xs text-emerald-600">Hadir</p>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center shrink-0">
                <i class="fas fa-briefcase-medical text-white text-sm"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-amber-700" x-text="countByStatus('Sakit')"></p>
                <p class="text-xs text-amber-600">Sakit</p>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center shrink-0">
                <i class="fas fa-file-alt text-white text-sm"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-blue-700" x-text="countByStatus('Izin')"></p>
                <p class="text-xs text-blue-600">Izin</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 bg-red-500 rounded-lg flex items-center justify-center shrink-0">
                <i class="fas fa-times text-white text-sm"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-red-700" x-text="countByStatus(null)"></p>
                <p class="text-xs text-red-600">Belum Absen</p>
            </div>
        </div>
    </div>

    <!-- ===== DAFTAR SISWA ===== -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Toolbar -->
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-3">
            <div>
                <h3 class="font-bold text-slate-800">Daftar Siswa</h3>
                <p class="text-xs text-slate-500 mt-0.5">Klik status untuk mengubah. Perubahan langsung tersimpan.</p>
            </div>
            <div class="sm:ml-auto flex gap-2">
                <!-- Tandai Semua Hadir -->
                <button @click="tandaiSemua('Hadir')"
                        x-show="siswaList.length > 0"
                        class="px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-semibold rounded-lg transition-colors">
                    <i class="fas fa-check-double mr-1"></i> Semua Hadir
                </button>
            </div>
        </div>

        <!-- Loading -->
        <div x-show="isLoading" class="text-center py-16">
            <i class="fas fa-spinner fa-spin text-3xl text-indigo-400 mb-3"></i>
            <p class="text-slate-500 text-sm">Memuat data siswa...</p>
        </div>

        <!-- Empty state -->
        <div x-show="!isLoading && !rombelId" class="text-center py-16">
            <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-users text-slate-400 text-2xl"></i>
            </div>
            <p class="text-slate-500 text-sm">Pilih kelas terlebih dahulu untuk memuat daftar siswa.</p>
        </div>

        <div x-show="!isLoading && rombelId && siswaList.length === 0 && rombelLoaded" class="text-center py-16">
            <p class="text-slate-400 text-sm">Tidak ada siswa di kelas ini.</p>
        </div>

        <!-- Grid Siswa -->
        <div x-show="!isLoading && siswaList.length > 0"
             class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
            <template x-for="s in siswaList" :key="s.id">
                <div class="border rounded-xl p-4 transition-all duration-200"
                     :class="{
                         'border-emerald-200 bg-emerald-50': s.status === 'Hadir',
                         'border-amber-200 bg-amber-50':    s.status === 'Sakit',
                         'border-blue-200 bg-blue-50':      s.status === 'Izin',
                         'border-red-200 bg-red-50':        s.status === 'Alpa',
                         'border-slate-200 bg-white':       !s.status
                     }">
                    <!-- Info siswa -->
                    <div class="flex items-start gap-2 mb-3">
                        <div class="w-9 h-9 rounded-lg shrink-0 flex items-center justify-center text-xs font-bold"
                             :class="{
                                 'bg-emerald-500 text-white': s.status === 'Hadir',
                                 'bg-amber-500 text-white':   s.status === 'Sakit',
                                 'bg-blue-500 text-white':    s.status === 'Izin',
                                 'bg-red-500 text-white':     s.status === 'Alpa',
                                 'bg-slate-200 text-slate-500': !s.status
                             }"
                             x-text="s.status ? s.status.charAt(0) : '?'">
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 text-sm leading-tight line-clamp-2" x-text="s.nama_lengkap"></p>
                            <p class="text-xs text-slate-400 mt-0.5" x-text="s.nisn"></p>
                            <p x-show="s.waktu" class="text-xs text-slate-400 mt-0.5">
                                <i class="fas fa-clock text-[10px]"></i>
                                <span x-text="s.waktu"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Status -->
                    <div class="grid grid-cols-4 gap-1">
                        <button @click="submitStatus(s, 'Hadir')"
                                class="py-1.5 rounded-lg text-xs font-bold transition-all duration-150"
                                :class="s.status === 'Hadir' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600'">
                            H
                        </button>
                        <button @click="submitStatus(s, 'Sakit')"
                                class="py-1.5 rounded-lg text-xs font-bold transition-all duration-150"
                                :class="s.status === 'Sakit' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-500 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600'">
                            S
                        </button>
                        <button @click="submitStatus(s, 'Izin')"
                                class="py-1.5 rounded-lg text-xs font-bold transition-all duration-150"
                                :class="s.status === 'Izin' ? 'bg-blue-500 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-500 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600'">
                            I
                        </button>
                        <button @click="submitStatus(s, 'Alpa')"
                                class="py-1.5 rounded-lg text-xs font-bold transition-all duration-150"
                                :class="s.status === 'Alpa' ? 'bg-red-500 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-500 hover:bg-red-50 hover:border-red-300 hover:text-red-600'">
                            A
                        </button>
                    </div>

                    <!-- Indikator saving -->
                    <div x-show="s.saving" class="text-center mt-2">
                        <i class="fas fa-spinner fa-spin text-xs text-slate-400"></i>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- ===== MODAL SCAN QR (Opsional) ===== -->
    <div x-show="scanModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="closeScanModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">

            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Scan QR Siswa</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Mode opsional — arahkan QR ke kamera</p>
                </div>
                <button @click="closeScanModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-slate-500 text-sm"></i>
                </button>
            </div>

            <!-- Tipe toggle (jika Masuk & Pulang) -->
            <?php if ($isMasukPulang): ?>
            <div class="px-6 pt-4 flex gap-3">
                <button @click="scanTipe = 'masuk'"
                        :class="scanTipe === 'masuk' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500'"
                        class="flex-1 py-2 rounded-xl text-sm font-bold transition-colors">
                    <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                </button>
                <button @click="scanTipe = 'pulang'"
                        :class="scanTipe === 'pulang' ? 'bg-violet-500 text-white' : 'bg-slate-100 text-slate-500'"
                        class="flex-1 py-2 rounded-xl text-sm font-bold transition-colors">
                    <i class="fas fa-sign-out-alt mr-1"></i> Pulang
                </button>
            </div>
            <?php else: ?>
            <input type="hidden" x-bind:value="scanTipe = 'masuk'">
            <?php endif; ?>

            <!-- Scanner area -->
            <div class="p-5">
                <div class="rounded-2xl overflow-hidden bg-black" style="aspect-ratio:1/1;" id="scan-reader"></div>
            </div>

            <!-- Log hasil scan -->
            <div class="px-5 pb-5 space-y-2 max-h-40 overflow-y-auto" id="scan-log">
                <template x-for="(log, i) in scanLogs" :key="i">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium border"
                         :class="log.ok ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700'">
                        <i class="fas" :class="log.ok ? 'fa-check-circle' : 'fa-times-circle'"></i>
                        <span x-text="log.pesan"></span>
                    </div>
                </template>
                <div x-show="scanLogs.length === 0" class="text-center text-slate-400 text-sm py-3">
                    Belum ada hasil scan.
                </div>
            </div>
        </div>
    </div>

    <!-- Toast notifikasi -->
    <div x-show="toast.show"
         x-cloak
         x-transition
         class="fixed bottom-5 right-5 z-50 px-5 py-3 rounded-xl shadow-xl text-white text-sm font-semibold flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-red-600'">
        <i class="fas" :class="toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
        <span x-text="toast.pesan"></span>
    </div>

    <?php endif; // end !isPerMapel ?>
</div>

<!-- Html5Qrcode Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
const BASEURL_ABS = '<?= BASEURL ?>';
const MODE_ABSEN  = '<?= $modeAbsen ?>';

function absensiSiswaApp() {
    return {
        rombelId: '',
        tanggal: '<?= date('Y-m-d') ?>',
        tipeAbsen: 'masuk',       // masuk | pulang
        siswaList: [],
        isLoading: false,
        rombelLoaded: false,

        // Scan modal
        scanModalOpen: false,
        scanTipe: 'masuk',
        scanLogs: [],
        qrScanner: null,

        // Toast
        toast: { show: false, type: 'success', pesan: '' },

        init() {
            // Tidak ada inisiasi khusus; user pilih kelas dulu
        },

        async loadSiswa() {
            if (!this.rombelId) return;
            this.isLoading = true;
            this.siswaList = [];
            this.rombelLoaded = false;

            try {
                const res = await fetch(`${BASEURL_ABS}/AbsensiSiswa/getSiswa?rombel_id=${this.rombelId}&tanggal=${this.tanggal}`);
                const result = await res.json();
                if (result.status) {
                    this.siswaList = result.data.map(s => ({
                        ...s,
                        status: MODE_ABSEN === 'Masuk & Pulang'
                            ? (this.tipeAbsen === 'masuk' ? (s.sudah_masuk ? s.status : null) : (s.sudah_pulang ? s.status_pulang : null))
                            : s.status,
                        saving: false
                    }));
                }
                this.rombelLoaded = true;
            } catch (e) {
                console.error(e);
            }

            this.isLoading = false;
        },

        async submitStatus(siswa, newStatus) {
            // Optimistic update
            siswa.status = newStatus;
            siswa.saving = true;

            const tipe = MODE_ABSEN === 'Masuk & Pulang' ? this.tipeAbsen : 'masuk';

            try {
                const res = await fetch(`${BASEURL_ABS}/AbsensiSiswa/submitManual`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        siswa_id: siswa.id,
                        status: newStatus,
                        tipe_absen: tipe,
                        tanggal: this.tanggal
                    })
                });
                const result = await res.json();
                if (!result.status) {
                    this.showToast('error', result.pesan || 'Gagal menyimpan.');
                } else {
                    siswa.waktu = new Date().toLocaleTimeString('id-ID');
                }
            } catch (e) {
                this.showToast('error', 'Koneksi gagal.');
            }

            siswa.saving = false;
        },

        async tandaiSemua(status) {
            if (!confirm(`Tandai SEMUA siswa sebagai "${status}"?`)) return;
            for (const s of this.siswaList) {
                await this.submitStatus(s, status);
            }
            this.showToast('success', `Semua siswa ditandai ${status}.`);
        },

        countByStatus(status) {
            if (status === null) return this.siswaList.filter(s => !s.status).length;
            return this.siswaList.filter(s => s.status === status).length;
        },

        // ===== Scan Modal =====
        openScanModal() {
            this.scanModalOpen = true;
            this.scanLogs = [];
            setTimeout(() => this.initScanner(), 200);
        },

        closeScanModal() {
            this.scanModalOpen = false;
            if (this.qrScanner) {
                this.qrScanner.clear().catch(() => {});
                this.qrScanner = null;
            }
        },

        initScanner() {
            if (this.qrScanner) return;
            this.qrScanner = new Html5QrcodeScanner(
                'scan-reader',
                { fps: 10, qrbox: { width: 200, height: 200 }, supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA] },
                false
            );
            this.qrScanner.render(
                this.onScanSuccess.bind(this),
                () => {}
            );
        },

        async onScanSuccess(qrToken) {
            this.qrScanner.pause();

            try {
                const res = await fetch(`${BASEURL_ABS}/AbsensiSiswa/submitScan`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        qr_token: qrToken,
                        tipe_absen: this.scanTipe,
                        tanggal: this.tanggal
                    })
                });
                const result = await res.json();

                this.scanLogs.unshift({ ok: result.status, pesan: result.pesan });
                if (this.scanLogs.length > 8) this.scanLogs.pop();

                // Reload daftar siswa jika rombel sudah dipilih
                if (this.rombelId) {
                    this.loadSiswa();
                }
            } catch (e) {
                this.scanLogs.unshift({ ok: false, pesan: 'Koneksi gagal.' });
            }

            setTimeout(() => { if (this.qrScanner) this.qrScanner.resume(); }, 2000);
        },

        // ===== Toast =====
        showToast(type, pesan) {
            this.toast = { show: true, type, pesan };
            setTimeout(() => { this.toast.show = false; }, 3000);
        }
    };
}
</script>
