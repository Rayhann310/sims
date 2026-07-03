<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Ujian - <?= $data['judul'] ?></title>
    <link rel="stylesheet" href="<?= BASEURL ?>/public/vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/public/vendor/adminlte/dist/css/adminlte.min.css">
    <style>
        body { background-color: #f4f6f9; user-select: none; }
        .exam-header { background: #007bff; color: white; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .timer-box { font-size: 24px; font-weight: bold; background: #dc3545; padding: 5px 15px; border-radius: 5px; }
        .question-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); min-height: 400px; font-size: 18px; }
        .option-label { display: block; padding: 15px; border: 1px solid #ddd; border-radius: 5px; cursor: pointer; transition: 0.3s; margin-bottom: 10px; }
        .option-label:hover { background: #f8f9fa; }
        input[type="radio"]:checked + .option-label { background: #e8f4ff; border-color: #007bff; font-weight: bold; }
        
        /* Layar Kunci / Anti-Cheat */
        #lock-screen {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.9); color: white; z-index: 9999;
            flex-direction: column; align-items: center; justify-content: center;
        }
        #lock-screen h1 { color: #ff4d4d; font-size: 40px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <!-- Layar Terkunci -->
    <div id="lock-screen">
        <i class="fas fa-lock fa-5x text-danger mb-3"></i>
        <h1>UJIAN TERKUNCI!</h1>
        <p class="lead">Anda terdeteksi melakukan pelanggaran (keluar layar penuh atau berpindah tab).</p>
        <p>Silakan angkat tangan dan lapor ke Pengawas Ruangan untuk membuka kunci layar Anda.</p>
        <p class="text-muted mt-3">Menunggu pengawas membuka kunci... <i class="fas fa-spinner fa-spin"></i></p>
    </div>

    <!-- Tombol Mulai (Wajib diklik agar browser mengizinkan Fullscreen) -->
    <div id="start-screen" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: white; z-index: 9000; display: flex; align-items: center; justify-content: center;">
        <div class="text-center">
            <h2>Siap Mengerjakan Ujian?</h2>
            <p>Sistem akan beralih ke Layar Penuh. Jangan tekan tombol ESC atau berpindah tab selama ujian berlangsung.</p>
            <button class="btn btn-primary btn-lg mt-3" onclick="startExam()">Mulai Ujian Sekarang</button>
        </div>
    </div>

    <div class="exam-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="m-0">Matematika - Ujian Tengah Semester</h4>
            <small>Nama Peserta: Siswa Demo</small>
        </div>
        <div class="timer-box" id="timer">
            01:59:59
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="question-box">
                    <p><strong>Soal No. 1</strong></p>
                    <p>Berapakah hasil dari 2 + 2?</p>
                    
                    <div class="mt-4">
                        <label>
                            <input type="radio" name="jawaban" value="A" style="display:none;">
                            <span class="option-label">A. 1</span>
                        </label>
                        <label>
                            <input type="radio" name="jawaban" value="B" style="display:none;">
                            <span class="option-label">B. 2</span>
                        </label>
                        <label>
                            <input type="radio" name="jawaban" value="C" style="display:none;">
                            <span class="option-label">C. 3</span>
                        </label>
                        <label>
                            <input type="radio" name="jawaban" value="D" style="display:none;">
                            <span class="option-label">D. 4</span>
                        </label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-secondary">Soal Sebelumnya</button>
                    <button class="btn btn-warning">Ragu - Ragu</button>
                    <button class="btn btn-primary">Soal Selanjutnya</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">Navigasi Soal</div>
                    <div class="card-body">
                        <button class="btn btn-primary m-1">1</button>
                        <button class="btn btn-outline-secondary m-1">2</button>
                        <button class="btn btn-outline-secondary m-1">3</button>
                        <button class="btn btn-outline-secondary m-1">4</button>
                        <button class="btn btn-outline-secondary m-1">5</button>
                        <hr>
                        <button class="btn btn-danger btn-block mt-3" onclick="selesaiUjian()">Selesai Ujian</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= BASEURL ?>/public/vendor/adminlte/plugins/jquery/jquery.min.js"></script>
    <script>
        let isExamActive = false;

        function startExam() {
            let elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen().then(() => {
                    document.getElementById('start-screen').style.display = 'none';
                    isExamActive = true;
                }).catch(err => {
                    alert('Gagal memasuki mode fullscreen. Harap gunakan browser Chrome/Firefox terbaru.');
                });
            }
        }

        // Deteksi jika keluar dari Fullscreen
        document.addEventListener('fullscreenchange', (event) => {
            if (!document.fullscreenElement && isExamActive) {
                lockScreen('Keluar dari layar penuh');
            }
        });

        // Deteksi jika berpindah tab (Blur / Visibility)
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden' && isExamActive) {
                lockScreen('Berpindah tab atau aplikasi lain');
            }
        });

        window.addEventListener('blur', () => {
            if(isExamActive) lockScreen('Membuka aplikasi lain');
        });

        function lockScreen(alasan) {
            isExamActive = false; // Matikan sementara
            document.getElementById('lock-screen').style.display = 'flex';
            
            // Kirim ping ke server bahwa siswa melanggar
            $.post("<?= BASEURL ?>/UjianSiswa/lockApi", { 
                id_peserta: 1, // ID Peserta (Demo)
                alasan: alasan 
            }, function(response) {
                console.log("Status terkunci dikirim ke server");
            });

            // Di sini nanti ada kode SSE/Polling sederhana untuk mengecek apakah pengawas sudah unlock
            // setInterval(checkUnlockStatus, 3000); 
        }

        function selesaiUjian() {
            if(confirm("Yakin ingin menyelesaikan ujian? Anda tidak bisa mengulangi setelah menekan OK.")) {
                isExamActive = false; // Hindari trigger lock saat pindah halaman
                window.location.href = "<?= BASEURL ?>/UjianSiswa";
            }
        }
    </script>
</body>
</html>
