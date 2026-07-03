<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Ujian - <?= htmlspecialchars($data['judul']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        body { user-select: none; }
        .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        input[type="radio"]:checked + .option-box { 
            background-color: #ecfdf5; 
            border-color: #10b981; 
        }
        input[type="radio"]:checked + .option-box .option-letter { 
            background-color: #10b981; 
            color: white; 
            border-color: #10b981;
        }
    </style>
</head>
<body class="bg-slate-100 h-screen flex flex-col overflow-hidden text-slate-800" x-data="examApp()">

    <!-- Start Screen -->
    <div x-show="!isExamActive && !isLocked" class="fixed inset-0 z-50 bg-white flex items-center justify-center">
        <div class="max-w-md w-full p-8 text-center">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-laptop-code text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Siap Mengerjakan Ujian?</h2>
            <p class="text-slate-600 mb-6">Sistem akan beralih ke Layar Penuh. Jangan menekan tombol ESC atau berpindah aplikasi selama ujian berlangsung karena akan mengunci akun Anda.</p>
            
            <div class="space-y-4">
                <div>
                    <input type="text" x-model="startToken" placeholder="Masukkan Token Ujian" class="w-full text-center text-xl tracking-[0.2em] uppercase font-mono font-bold border-2 border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition-all">
                </div>
                <button @click="startExam()" :disabled="isStarting" class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-emerald-200 flex justify-center items-center gap-2">
                    <i class="fas fa-play" x-show="!isStarting"></i>
                    <i class="fas fa-spinner fa-spin" x-show="isStarting"></i>
                    <span x-text="isStarting ? 'Memverifikasi...' : 'Mulai Ujian Sekarang'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Lock Screen -->
    <div x-show="isLocked" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/95 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center transform transition-all">
            <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black text-red-600 mb-2">UJIAN TERKUNCI!</h2>
            <p class="text-slate-600 mb-4" x-text="lockReason"></p>
            
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-4 mb-6 text-sm">
                Harap hubungi pengawas ruangan untuk meminta <b>Token Ujian</b> terbaru agar dapat melanjutkan.
            </div>
            
            <div class="space-y-4">
                <div>
                    <input type="text" x-model="unlockToken" placeholder="Masukkan Token Ujian" class="w-full text-center text-2xl tracking-[0.2em] uppercase font-mono font-bold border-2 border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition-all">
                </div>
                <button @click="verifyToken()" :disabled="isVerifying" class="w-full bg-slate-800 hover:bg-slate-900 disabled:bg-slate-400 text-white font-bold py-3 px-4 rounded-xl transition-colors flex justify-center items-center gap-2">
                    <i class="fas fa-key" x-show="!isVerifying"></i>
                    <i class="fas fa-spinner fa-spin" x-show="isVerifying"></i>
                    <span x-text="isVerifying ? 'Memverifikasi...' : 'Buka Kunci Ujian'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 px-6 py-3 shrink-0 flex justify-between items-center z-10 relative shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold">
                CBT
            </div>
            <div>
                <h1 class="font-bold text-slate-800 leading-tight"><?= htmlspecialchars($data['jadwal']['nama_ujian'] ?? 'Ujian') ?></h1>
                <p class="text-xs text-slate-500 font-medium"><?= htmlspecialchars($data['nama_siswa']) ?></p>
            </div>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="bg-slate-100 rounded-lg px-4 py-2 flex items-center gap-3 border border-slate-200">
                <i class="far fa-clock text-emerald-600 text-lg"></i>
                <div class="font-mono text-xl font-bold tracking-wider text-slate-700" x-text="formattedTime">00:00:00</div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- Left: Question Area -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 scrollbar-hide flex flex-col">
            <template x-if="soal.length > 0">
                <div class="max-w-4xl mx-auto w-full flex-1 flex flex-col">
                    
                    <!-- Question Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-6 flex-1">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                            <h2 class="text-xl font-bold text-slate-800">Soal No. <span x-text="currentIndex + 1"></span></h2>
                            <span class="text-sm font-medium text-slate-400">Pilihan Ganda</span>
                        </div>
                        
                        <div class="text-lg text-slate-700 leading-relaxed mb-8" x-html="currentSoal.pertanyaan"></div>
                        
                        <div class="space-y-3">
                            <template x-for="(opt, idx) in optionsList" :key="idx">
                                <label x-show="currentSoal['opsi_' + opt.key]" class="relative flex cursor-pointer group">
                                    <input type="radio" :name="'soal_'+currentSoal.id_soal" :value="opt.key.toUpperCase()" x-model="answers[currentSoal.id_soal]" class="peer sr-only" @change="saveAnswer()">
                                    <div class="option-box w-full flex items-center p-4 rounded-xl border-2 border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/50 transition-all">
                                        <div class="option-letter w-8 h-8 rounded-lg border-2 border-slate-200 flex items-center justify-center font-bold text-slate-500 mr-4 transition-colors">
                                            <span x-text="opt.key.toUpperCase()"></span>
                                        </div>
                                        <div class="flex-1 text-slate-700" x-html="currentSoal['opsi_' + opt.key]"></div>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Navigation Bar -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 flex justify-between items-center shrink-0">
                        <button @click="prevQuestion()" :disabled="currentIndex === 0" class="px-6 py-3 rounded-xl font-semibold transition-colors flex items-center gap-2" :class="currentIndex === 0 ? 'text-slate-400 bg-slate-100 cursor-not-allowed' : 'text-slate-700 bg-slate-100 hover:bg-slate-200'">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        
                        <label class="flex items-center gap-3 cursor-pointer select-none px-4 py-2 rounded-lg hover:bg-amber-50 border border-transparent hover:border-amber-200 transition-colors">
                            <div class="relative">
                                <input type="checkbox" class="sr-only peer" x-model="ragu[currentSoal.id_soal]">
                                <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            </div>
                            <span class="font-medium" :class="ragu[currentSoal.id_soal] ? 'text-amber-600' : 'text-slate-500'">Ragu-ragu</span>
                        </label>
                        
                        <button @click="nextQuestion()" :disabled="currentIndex === soal.length - 1" class="px-6 py-3 rounded-xl font-semibold transition-colors flex items-center gap-2" :class="currentIndex === soal.length - 1 ? 'text-slate-400 bg-slate-100 cursor-not-allowed' : 'text-white bg-emerald-600 hover:bg-emerald-700'">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                </div>
            </template>
            <template x-if="soal.length === 0">
                <div class="flex-1 flex items-center justify-center text-slate-500">
                    Belum ada soal untuk ujian ini.
                </div>
            </template>
        </main>

        <!-- Right: Grid Area -->
        <aside class="w-80 bg-white border-l border-slate-200 flex flex-col shrink-0 z-20 shadow-[-4px_0_15px_rgba(0,0,0,0.02)]">
            <div class="p-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <i class="fas fa-th text-emerald-600"></i>
                <h3 class="font-bold text-slate-700">Navigasi Soal</h3>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 scrollbar-hide">
                <div class="grid grid-cols-5 gap-2">
                    <template x-for="(s, index) in soal" :key="s.id_soal">
                        <button @click="goToQuestion(index)" 
                            class="relative h-12 rounded-lg font-bold text-sm border-2 transition-all flex flex-col items-center justify-center overflow-hidden"
                            :class="{
                                'border-emerald-500 bg-emerald-50 text-emerald-700': currentIndex === index,
                                'border-slate-200 hover:border-slate-300 text-slate-600': currentIndex !== index,
                                'border-emerald-500 text-emerald-700': answers[s.id_soal] && currentIndex !== index,
                                'border-amber-400 bg-amber-50 text-amber-700': ragu[s.id_soal]
                            }">
                            
                            <span x-text="index + 1" class="z-10"></span>
                            
                            <!-- Indikator terjawab -->
                            <div x-show="answers[s.id_soal] && !ragu[s.id_soal]" class="absolute bottom-0 w-full h-1.5 bg-emerald-500"></div>
                            <!-- Indikator ragu -->
                            <div x-show="ragu[s.id_soal]" class="absolute bottom-0 w-full h-1.5 bg-amber-400"></div>
                        </button>
                    </template>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-200 bg-slate-50">
                <div class="flex justify-between text-xs font-medium text-slate-500 mb-4 px-2">
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-emerald-500"></div> Terjawab</div>
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-amber-400"></div> Ragu</div>
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded border-2 border-slate-300"></div> Kosong</div>
                </div>
                <button @click="finishExam()" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm shadow-red-200">
                    <i class="fas fa-flag-checkered"></i> Selesai Ujian
                </button>
            </div>
        </aside>
    </div>

    <script>
        function examApp() {
            return {
                isExamActive: false,
                isLocked: false,
                lockReason: '',
                unlockToken: '',
                startToken: '',
                isVerifying: false,
                isStarting: false,
                
                soal: <?= json_encode($data['soal'] ?? []) ?>,
                currentIndex: 0,
                answers: {}, // id_soal => jawaban (A/B/C/D/E)
                ragu: {}, // id_soal => boolean
                optionsList: [
                    {key: 'a'}, {key: 'b'}, {key: 'c'}, {key: 'd'}, {key: 'e'}
                ],
                
                // Durasi 
                durasiMenit: <?= isset($data['jadwal']['durasi_menit']) ? $data['jadwal']['durasi_menit'] : 60 ?>,
                timeRemaining: 0,
                timerInterval: null,
                
                get currentSoal() {
                    return this.soal[this.currentIndex] || {};
                },
                
                get formattedTime() {
                    let h = Math.floor(this.timeRemaining / 3600);
                    let m = Math.floor((this.timeRemaining % 3600) / 60);
                    let s = this.timeRemaining % 60;
                    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },
                
                init() {
                    this.timeRemaining = this.durasiMenit * 60;
                    this.setupAntiCheat();
                },
                
                startExam() {
                    if(!this.startToken) return alert("Masukkan token ujian terlebih dahulu!");
                    
                    this.isStarting = true;
                    fetch("<?= BASEURL ?>/UjianSiswa/unlockApi", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: new URLSearchParams({
                            id_peserta: <?= $data['peserta']['id_peserta'] ?? 0 ?>,
                            id_jadwal: <?= $data['jadwal']['id_jadwal'] ?? 0 ?>,
                            token: this.startToken
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isStarting = false;
                        if(data.status) {
                            let elem = document.documentElement;
                            if (elem.requestFullscreen) {
                                elem.requestFullscreen().then(() => {
                                    this.isExamActive = true;
                                    this.startTimer();
                                }).catch(err => {
                                    alert('Gagal mode fullscreen. Harap gunakan browser Chrome/Firefox terbaru.');
                                    this.isExamActive = true;
                                    this.startTimer();
                                });
                            } else {
                                this.isExamActive = true;
                                this.startTimer();
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(e => {
                        this.isStarting = false;
                        alert("Terjadi kesalahan koneksi.");
                    });
                },
                
                startTimer() {
                    if(this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(() => {
                        if(this.isExamActive && !this.isLocked) {
                            if(this.timeRemaining > 0) {
                                this.timeRemaining--;
                            } else {
                                clearInterval(this.timerInterval);
                                this.autoSubmit();
                            }
                        }
                    }, 1000);
                },
                
                setupAntiCheat() {
                    document.addEventListener('fullscreenchange', () => {
                        if (!document.fullscreenElement && this.isExamActive && !this.isLocked) {
                            this.lockScreen('Keluar dari layar penuh');
                        }
                    });

                    document.addEventListener('visibilitychange', () => {
                        if (document.visibilityState === 'hidden' && this.isExamActive && !this.isLocked) {
                            this.lockScreen('Berpindah tab atau aplikasi disembunyikan');
                        }
                    });

                    window.addEventListener('blur', () => {
                        if(this.isExamActive && !this.isLocked) {
                            this.lockScreen('Membuka aplikasi lain atau klik di luar browser');
                        }
                    });
                },
                
                lockScreen(reason) {
                    this.isLocked = true;
                    this.lockReason = reason;
                    
                    fetch("<?= BASEURL ?>/UjianSiswa/lockApi", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: new URLSearchParams({
                            id_peserta: <?= $data['peserta']['id_peserta'] ?? 0 ?>,
                            alasan: reason
                        })
                    });
                },
                
                verifyToken() {
                    if(!this.unlockToken) return alert("Masukkan token ujian!");
                    this.isVerifying = true;
                    
                    fetch("<?= BASEURL ?>/UjianSiswa/unlockApi", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: new URLSearchParams({
                            id_peserta: <?= $data['peserta']['id_peserta'] ?? 0 ?>,
                            id_jadwal: <?= $data['jadwal']['id_jadwal'] ?? 0 ?>,
                            token: this.unlockToken
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isVerifying = false;
                        if(data.status) {
                            this.unlockToken = '';
                            this.isLocked = false;
                            
                            // Re-request fullscreen just in case
                            if (!document.fullscreenElement) {
                                document.documentElement.requestFullscreen().catch(e=>console.log(e));
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(e => {
                        this.isVerifying = false;
                        alert("Terjadi kesalahan koneksi.");
                    });
                },
                
                nextQuestion() {
                    if (this.currentIndex < this.soal.length - 1) this.currentIndex++;
                },
                
                prevQuestion() {
                    if (this.currentIndex > 0) this.currentIndex--;
                },
                
                goToQuestion(index) {
                    this.currentIndex = index;
                },
                
                saveAnswer() {
                    // Logic to auto-save answer via AJAX can go here
                },
                
                finishExam() {
                    if(confirm("Apakah Anda yakin ingin menyelesaikan ujian? Anda tidak akan dapat kembali masuk.")) {
                        this.autoSubmit();
                    }
                },
                
                autoSubmit() {
                    this.isExamActive = false;
                    // Logic to submit all answers to server
                    // Redirect to dashboard
                    window.location.href = "<?= BASEURL ?>/UjianSiswa";
                }
            }
        }
    </script>
</body>
</html>
