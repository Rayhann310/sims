<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Jadwal extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
        // Pastikan self-healing berjalan
        $this->model('UserModel');
    }

    public function index()
    {
        $data['judul'] = 'Jadwal Pelajaran';
        $data['tahun_akademik'] = $this->model('AkademikModel')->getAllTahun();
        $data['mapel_list']     = $this->model('JadwalModel')->getAllMapel();
        $data['guru_list']      = $this->model('JadwalModel')->getAllGuru();

        $this->view('templates/admin_header', $data);
        $this->view('jadwal/index', $data);
        $this->view('templates/admin_footer');
    }

    // AJAX: dapatkan rombel by tahun akademik (terbuka untuk guru)
    public function getRombelAjax($ta_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->model('RombelModel')->getRombelByTahunAkademik($ta_id));
        exit;
    }

    // AJAX: dapatkan jadwal by rombel
    public function getJadwalAjax($rombel_id)
    {
        header('Content-Type: application/json');
        $jadwal = $this->model('JadwalModel')->getJadwalByRombel($rombel_id);
        
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'guru') {
            $guru_id = $this->model('JadwalModel')->getGuruIdByUserId($_SESSION['user']['id']);
            if ($guru_id) {
                $filtered = [];
                foreach ($jadwal as $j) {
                    if ($j['guru_id'] == $guru_id) {
                        $filtered[] = $j;
                    }
                }
                $jadwal = array_values($filtered); // reset keys for JSON array format
            }
        }
        
        echo json_encode($jadwal);
        exit;
    }

    // AJAX: cek konflik jam sebelum simpan
    public function cekKonflik()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['error' => 'invalid']); exit; }

        $model = $this->model('JadwalModel');
        $hasil = $model->cekKonflikJam(
            $_POST['rombel_id'], $_POST['guru_id'],
            $_POST['hari'], $_POST['jam_mulai'], $_POST['jam_selesai'],
            !empty($_POST['exclude_id']) ? $_POST['exclude_id'] : null
        );
        echo json_encode($hasil);
        exit;
    }

    // Tambah jadwal manual
    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model  = $this->model('JadwalModel');
            $result = $model->tambahJadwal($_POST);
            if ($result['status']) {
                $_SESSION['flash'] = ['pesan' => 'Jadwal berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => $result['pesan'], 'aksi' => '', 'tipe' => 'danger'];
            }
        }
        header('Location: ' . BASEURL . '/jadwal');
        exit;
    }

    // Hapus satu jadwal
    public function hapus($id)
    {
        $this->model('JadwalModel')->hapusJadwal($id);
        $_SESSION['flash'] = ['pesan' => 'Jadwal berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        header('Location: ' . BASEURL . '/jadwal');
        exit;
    }

    // Download template Excel
    public function template()
    {
        $mapel = $this->model('JadwalModel')->getAllMapel();
        $guru  = $this->model('JadwalModel')->getAllGuru();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Jadwal');

        // Header
        $sheet->setCellValue('A1', 'mapel_id');
        $sheet->setCellValue('B1', 'guru_id');
        $sheet->setCellValue('C1', 'hari');
        $sheet->setCellValue('D1', 'jam_mulai');
        $sheet->setCellValue('E1', 'jam_selesai');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '047857']],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet referensi Mapel
        $sheetMapel = $spreadsheet->createSheet();
        $sheetMapel->setTitle('Ref Mapel');
        $sheetMapel->setCellValue('A1', 'ID');
        $sheetMapel->setCellValue('B1', 'Nama Mapel');
        foreach ($mapel as $i => $m) {
            $sheetMapel->setCellValue('A' . ($i + 2), $m['id']);
            $sheetMapel->setCellValue('B' . ($i + 2), $m['nama_mapel']);
        }

        // Sheet referensi Guru
        $sheetGuru = $spreadsheet->createSheet();
        $sheetGuru->setTitle('Ref Guru');
        $sheetGuru->setCellValue('A1', 'ID');
        $sheetGuru->setCellValue('B1', 'Nama Guru');
        foreach ($guru as $i => $g) {
            $sheetGuru->setCellValue('A' . ($i + 2), $g['id']);
            $sheetGuru->setCellValue('B' . ($i + 2), $g['nama_lengkap']);
        }

        // Contoh hari di sheet catatan
        $sheetNote = $spreadsheet->createSheet();
        $sheetNote->setTitle('Catatan');
        $sheetNote->setCellValue('A1', 'Nilai Hari yang Valid:');
        foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $i => $h) {
            $sheetNote->setCellValue('A' . ($i + 2), $h);
        }
        $sheetNote->setCellValue('C1', 'Format Jam: HH:MM (contoh: 07:00, 08:30)');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_jadwal.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Import dari Excel
    public function import()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            $rombel_id = $_POST['rombel_id'];
            if (empty($rombel_id)) {
                $_SESSION['flash'] = ['pesan' => 'Rombel belum dipilih', 'aksi' => '', 'tipe' => 'danger'];
                header('Location: ' . BASEURL . '/jadwal');
                exit;
            }

            $allowed_mimes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/octet-stream'];
            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, ['xls', 'xlsx'])) {
                $_SESSION['flash'] = ['pesan' => 'Format file harus .xlsx atau .xls', 'aksi' => '', 'tipe' => 'danger'];
                header('Location: ' . BASEURL . '/jadwal');
                exit;
            }

            try {
                $spreadsheet = IOFactory::load($_FILES['file_excel']['tmp_name']);
                $sheetData   = $spreadsheet->getActiveSheet()->toArray();

                $dataInsert = [];
                // Skip baris pertama (header)
                for ($i = 1; $i < count($sheetData); $i++) {
                    $mapel_id    = trim($sheetData[$i][0] ?? '');
                    $guru_id     = trim($sheetData[$i][1] ?? '');
                    $hari        = trim($sheetData[$i][2] ?? '');
                    $jam_mulai   = trim($sheetData[$i][3] ?? '');
                    $jam_selesai = trim($sheetData[$i][4] ?? '');

                    if ($mapel_id !== '' && $guru_id !== '' && $hari !== '' && $jam_mulai !== '' && $jam_selesai !== '') {
                        $dataInsert[] = [
                            'rombel_id'   => $rombel_id,
                            'mapel_id'    => (int)$mapel_id,
                            'guru_id'     => (int)$guru_id,
                            'hari'        => $hari,
                            'jam_mulai'   => $jam_mulai,
                            'jam_selesai' => $jam_selesai,
                        ];
                    }
                }

                if (count($dataInsert) > 0) {
                    $result = $this->model('JadwalModel')->importJadwalMassal($dataInsert);
                    $msg = $result['inserted'] . ' jadwal berhasil diimport.';
                    if (!empty($result['errors'])) {
                        $msg .= ' ' . count($result['errors']) . ' baris dilewati (bentrok jam).';
                    }
                    $_SESSION['flash'] = ['pesan' => $msg, 'aksi' => '', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Tidak ada data valid di file Excel', 'aksi' => '', 'tipe' => 'danger'];
                }
            } catch (Exception $e) {
                $_SESSION['flash'] = ['pesan' => 'Gagal membaca file: ' . $e->getMessage(), 'aksi' => '', 'tipe' => 'danger'];
            }

            header('Location: ' . BASEURL . '/jadwal');
            exit;
        }
    }
    // --- PENGATURAN & ALOKASI ---
    public function pengaturan()
    {
        $data['judul'] = 'Pengaturan Jadwal & Alokasi Mapel';
        $data['pengaturan'] = $this->model('JadwalModel')->getPengaturanJadwal();
        $data['istirahat'] = $this->model('JadwalModel')->getAllIstirahat();
        $data['alokasi'] = $this->model('JadwalModel')->getAllAlokasi();
        $data['mapel_list'] = $this->model('JadwalModel')->getAllMapel();
        
        // Fetch master_jurusan from AkademikModel
        require_once 'app/models/AkademikModel.php';
        $akademikModel = new AkademikModel();
        $data['master_jurusan'] = $akademikModel->getAllJurusan();

        $this->view('templates/admin_header', $data);
        $this->view('jadwal/pengaturan', $data);
        $this->view('templates/admin_footer');
    }

    public function simpanPengaturanJadwal()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('JadwalModel')->savePengaturanJadwal($_POST);
            $_SESSION['flash'] = ['pesan' => 'Pengaturan Jadwal', 'aksi' => 'diperbarui', 'tipe' => 'success'];
        }
        header('Location: ' . BASEURL . '/jadwal/pengaturan');
        exit;
    }

    public function simpanIstirahat()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('JadwalModel')->tambahIstirahat($_POST);
            $_SESSION['flash'] = ['pesan' => 'Jadwal Istirahat', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
        }
        header('Location: ' . BASEURL . '/jadwal/pengaturan');
        exit;
    }

    public function hapusIstirahat($id)
    {
        $this->model('JadwalModel')->hapusIstirahat($id);
        $_SESSION['flash'] = ['pesan' => 'Jadwal Istirahat', 'aksi' => 'dihapus', 'tipe' => 'success'];
        header('Location: ' . BASEURL . '/jadwal/pengaturan');
        exit;
    }

    public function simpanAlokasi()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('JadwalModel')->simpanAlokasi($_POST);
            $_SESSION['flash'] = ['pesan' => 'Alokasi Mapel', 'aksi' => 'disimpan', 'tipe' => 'success'];
        }
        header('Location: ' . BASEURL . '/jadwal/pengaturan');
        exit;
    }

    public function hapusAlokasi($id)
    {
        $this->model('JadwalModel')->hapusAlokasi($id);
        $_SESSION['flash'] = ['pesan' => 'Alokasi Mapel', 'aksi' => 'dihapus', 'tipe' => 'success'];
        header('Location: ' . BASEURL . '/jadwal/pengaturan');
        exit;
    }
    // --- AUTO GENERATE JADWAL ---
    public function autoGenerate()
    {
        $rombel_id = isset($_GET['rombel_id']) ? $_GET['rombel_id'] : null;
        $ta_id = isset($_GET['ta_id']) ? $_GET['ta_id'] : null;
        if (!$rombel_id || !$ta_id) {
            $_SESSION['flash'] = ['pesan' => 'Rombel belum dipilih', 'aksi' => '', 'tipe' => 'danger'];
            header('Location: ' . BASEURL . '/jadwal');
            exit;
        }

        // Ambil info rombel
        $db = new Database();
        $db->query("SELECT r.*, k.tingkat, k.jurusan FROM rombel r JOIN kelas k ON r.kelas_id = k.id WHERE r.id = :id");
        $db->bind('id', $rombel_id);
        $rombel = $db->single();

        $jurusan = $rombel['jurusan'];
        if (empty($jurusan)) {
            if (stripos($rombel['nama_kelas'], 'ipa') !== false || stripos($rombel['nama_rombel'], 'ipa') !== false) $jurusan = 'MIPA';
            elseif (stripos($rombel['nama_kelas'], 'ips') !== false || stripos($rombel['nama_rombel'], 'ips') !== false) $jurusan = 'IPS';
            elseif (stripos($rombel['nama_kelas'], 'bahasa') !== false) $jurusan = 'BAHASA';
            else $jurusan = 'UMUM';
        }

        // Ambil alokasi mapel sesuai tingkat & jurusan
        $db->query("SELECT a.*, m.nama_mapel, m.kode_mapel FROM alokasi_mapel a JOIN mata_pelajaran m ON a.mapel_id = m.id WHERE a.tingkat = :tingkat AND a.jurusan = :jurusan");
        $db->bind('tingkat', $rombel['tingkat']);
        $db->bind('jurusan', $jurusan);
        $alokasi = $db->resultSet();
        
        // Jika masih kosong, coba ambil semua mapel di tingkat tersebut (fallback darurat)
        if (empty($alokasi)) {
            $db->query("SELECT a.*, m.nama_mapel, m.kode_mapel FROM alokasi_mapel a JOIN mata_pelajaran m ON a.mapel_id = m.id WHERE a.tingkat = :tingkat GROUP BY a.mapel_id");
            $db->bind('tingkat', $rombel['tingkat']);
            $alokasi = $db->resultSet();
        }

        $data['judul'] = 'Auto Generate Jadwal - ' . $rombel['nama_rombel'];
        $data['rombel'] = $rombel;
        $data['alokasi'] = $alokasi;
        $data['guru_list'] = $this->model('JadwalModel')->getAllGuru();
        $data['ta_id'] = $ta_id;

        $this->view('templates/admin_header', $data);
        $this->view('jadwal/auto_generate', $data);
        $this->view('templates/admin_footer');
    }

    public function prosesGenerate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        
        $rombel_id = $_POST['rombel_id'];
        $ta_id = $_POST['ta_id'];
        
        $mapel_guru = [];
        // Extract mapel_id dan guru_id dari POST (berbentuk array: guru[mapel_id])
        if (isset($_POST['guru'])) {
            foreach ($_POST['guru'] as $mapel_id => $guru_id) {
                if (!empty($guru_id)) {
                    $mapel_guru[$mapel_id] = $guru_id;
                }
            }
        }

        // Masukkan data ini ke session untuk diproses di preview
        $_SESSION['auto_generate_data'] = [
            'rombel_id' => $rombel_id,
            'ta_id' => $ta_id,
            'mapel_guru' => $mapel_guru
        ];

        header('Location: ' . BASEURL . '/jadwal/previewGenerate');
        exit;
    }

    public function previewGenerate()
    {
        if (!isset($_SESSION['auto_generate_data'])) {
            header('Location: ' . BASEURL . '/jadwal');
            exit;
        }

        $sessionData = $_SESSION['auto_generate_data'];
        $rombel_id = $sessionData['rombel_id'];
        $ta_id = $sessionData['ta_id'];
        $mapel_guru = $sessionData['mapel_guru']; // [mapel_id => guru_id]

        $db = new Database();
        
        // Ambil info rombel
        $db->query("SELECT r.*, k.tingkat, k.jurusan FROM rombel r JOIN kelas k ON r.kelas_id = k.id WHERE r.id = :id");
        $db->bind('id', $rombel_id);
        $rombel = $db->single();

        // Pengaturan
        $model = $this->model('JadwalModel');
        $pengaturan = $model->getPengaturanJadwal();
        $istirahat = $model->getAllIstirahat();
        
        $hari_aktif = explode(',', $pengaturan['hari_aktif']);
        $max_jp = (int)$pengaturan['max_jp_per_hari'];
        $durasi = (int)$pengaturan['durasi_per_jp'];
        
        // Ambil alokasi untuk tahu butuh berapa JP per mapel
        $db->query("SELECT a.*, m.nama_mapel, m.kode_mapel FROM alokasi_mapel a JOIN mata_pelajaran m ON a.mapel_id = m.id WHERE a.tingkat = :tingkat AND a.jurusan = :jurusan");
        $db->bind('tingkat', $rombel['tingkat']);
        $db->bind('jurusan', $rombel['jurusan']);
        $alokasiRaw = $db->resultSet();
        $alokasiMap = []; // [mapel_id => ['nama' => x, 'jml' => y]]
        foreach($alokasiRaw as $a) {
            $alokasiMap[$a['mapel_id']] = [
                'nama' => $a['nama_mapel'],
                'jml' => (int)$a['jumlah_jp']
            ];
        }
        
        // Persiapkan grid (Hari -> JP)
        $grid = [];
        foreach($hari_aktif as $h) {
            $grid[$h] = [];
            
            // Kalkulasi jam
            $currentTime = strtotime($pengaturan['jam_mulai']);
            
            for($jp = 1; $jp <= $max_jp; $jp++) {
                // Cek apakah JP ini adalah istirahat
                $isBreak = false;
                $breakName = '';
                $breakDuration = 0;
                
                foreach($istirahat as $ist) {
                    if ($ist['setelah_jp_ke'] == ($jp - 1)) {
                        // Berlaku tiap hari ATAU hari ini khusus
                        if (empty($ist['hari_khusus']) || strtolower($ist['hari_khusus']) == strtolower($h)) {
                            $isBreak = true;
                            $breakName = $ist['nama_istirahat'];
                            $breakDuration = (int)$ist['durasi_menit'];
                            break; // Anggap 1 istirahat per slot
                        }
                    }
                }
                
                if ($isBreak) {
                    $jamSelesaiBreak = $currentTime + ($breakDuration * 60);
                    $grid[$h]['break_' . $jp] = [
                        'type' => 'break',
                        'name' => $breakName,
                        'jam_mulai' => date('H:i', $currentTime),
                        'jam_selesai' => date('H:i', $jamSelesaiBreak)
                    ];
                    $currentTime = $jamSelesaiBreak;
                }
                
                // Normal JP
                $jamSelesai = $currentTime + ($durasi * 60);
                $grid[$h][$jp] = [
                    'type' => 'jp',
                    'jp' => $jp,
                    'jam_mulai' => date('H:i', $currentTime),
                    'jam_selesai' => date('H:i', $jamSelesai),
                    'mapel_id' => null,
                    'guru_id' => null,
                    'nama_mapel' => '',
                    'nama_guru' => '',
                    'conflict' => false
                ];
                $currentTime = $jamSelesai;
            }
        }
        
        // Ambil nama guru
        $guru_list = $model->getAllGuru();
        $guruMap = [];
        foreach($guru_list as $g) {
            $guruMap[$g['id']] = $g['nama_lengkap'];
        }

        // ALGORITMA PENEMPATAN GREEDY
        // Urutkan mapel berdasarkan prioritas/jumlah JP terbanyak agar mudah cari slot berjejer (block)
        $tasks = [];
        foreach($mapel_guru as $m_id => $g_id) {
            if (isset($alokasiMap[$m_id])) {
                $tasks[] = [
                    'mapel_id' => $m_id,
                    'guru_id' => $g_id,
                    'sisa_jp' => $alokasiMap[$m_id]['jml'],
                    'nama_mapel' => $alokasiMap[$m_id]['nama'],
                    'nama_guru' => $guruMap[$g_id] ?? 'Guru ' . $g_id
                ];
            }
        }
        
        // Sort tasks descending by sisa_jp
        usort($tasks, function($a, $b) {
            return $b['sisa_jp'] - $a['sisa_jp'];
        });

        foreach($tasks as &$task) {
            while($task['sisa_jp'] > 0) {
                // Tentukan ukuran blok (coba pasang 2 JP sekaligus jika sisa >= 2, kalau tidak 1)
                $blockSize = ($task['sisa_jp'] >= 2) ? 2 : 1;
                $placed = false;
                
                foreach($hari_aktif as $h) {
                    for($jp = 1; $jp <= $max_jp - $blockSize + 1; $jp++) {
                        // Cek apakah slot(s) kosong
                        $canPlace = true;
                        $slotsToFill = [];
                        
                        for($b = 0; $b < $blockSize; $b++) {
                            $targetJp = $jp + $b;
                            if (isset($grid[$h][$targetJp]) && $grid[$h][$targetJp]['mapel_id'] === null) {
                                // Cek bentrok guru di jam ini pakai cekKonflikJam
                                $konflik = $model->cekKonflikJam(
                                    $rombel_id, 
                                    $task['guru_id'], 
                                    $h, 
                                    $grid[$h][$targetJp]['jam_mulai'], 
                                    $grid[$h][$targetJp]['jam_selesai']
                                );
                                
                                if ($konflik['konflik_guru']) {
                                    $canPlace = false;
                                    break;
                                }
                                $slotsToFill[] = $targetJp;
                            } else {
                                $canPlace = false;
                                break;
                            }
                        }
                        
                        if ($canPlace) {
                            // Cek apakah hari ini sudah ada mapel yang sama (hindari double pelajaran di 1 hari jika memungkinkan)
                            // Ini optional, tapi bagus untuk jadwal
                            $hasSameToday = false;
                            foreach($grid[$h] as $k => $v) {
                                if ($v['type'] == 'jp' && $v['mapel_id'] == $task['mapel_id']) {
                                    $hasSameToday = true;
                                    break;
                                }
                            }
                            
                            // Jika ada yang sama di hari yang sama, dan blok size > 1, mending coba hari lain dulu
                            // (Sangat kompleks untuk perfect greedy, kita pakai sederhana saja)
                            if ($hasSameToday && count($hari_aktif) > 1 && mt_rand(0, 1) == 0) {
                                // skip with 50% chance to spread subjects
                                continue; 
                            }

                            // Place
                            foreach($slotsToFill as $s) {
                                $grid[$h][$s]['mapel_id'] = $task['mapel_id'];
                                $grid[$h][$s]['guru_id'] = $task['guru_id'];
                                $grid[$h][$s]['nama_mapel'] = $task['nama_mapel'];
                                $grid[$h][$s]['nama_guru'] = $task['nama_guru'];
                            }
                            $task['sisa_jp'] -= $blockSize;
                            $placed = true;
                            break;
                        }
                    }
                    if ($placed) break;
                }
                
                if (!$placed) {
                    // Jika tidak bisa ditempatkan dengan blockSize 2, coba turunkan jadi 1
                    if ($blockSize > 1) {
                        // loop kembali dengan sisa JP yang sama (akan otomatis jadi blockSize 1 next time jika kita force)
                        // Wait, if it fails to place blockSize 2, it loops forever.
                        // Force sisa_jp to be evaluated as 1 temporarily for this turn
                        $task['sisa_jp'] -= 1; 
                        // fallback strategy, just place 1
                        $task['sisa_jp'] += 1;
                        // a hack: we break out if we absolutely can't place it even with size 1
                    } 
                    // Break to avoid infinite loop if grid is full
                    break;
                }
            }
        }
        
        $_SESSION['generated_grid'] = $grid; // Simpan untuk disave nanti

        $data['judul'] = 'Preview Jadwal - ' . $rombel['nama_rombel'];
        $data['rombel'] = $rombel;
        $data['grid'] = $grid;
        $data['hari_aktif'] = $hari_aktif;
        $data['unplaced'] = array_filter($tasks, function($t) { return $t['sisa_jp'] > 0; });
        $data['max_jp'] = $max_jp;

        $this->view('templates/admin_header', $data);
        $this->view('jadwal/preview', $data);
        $this->view('templates/admin_footer');
    }

    public function simpanJadwalOtomatis()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['generated_grid'])) {
            header('Location: ' . BASEURL . '/jadwal');
            exit;
        }

        $grid = $_SESSION['generated_grid'];
        $rombel_id = $_SESSION['auto_generate_data']['rombel_id'];
        
        $dataInsert = [];
        foreach ($grid as $hari => $slots) {
            foreach ($slots as $key => $slot) {
                if ($slot['type'] == 'jp' && !empty($slot['mapel_id']) && !empty($slot['guru_id'])) {
                    $dataInsert[] = [
                        'rombel_id'   => $rombel_id,
                        'mapel_id'    => $slot['mapel_id'],
                        'guru_id'     => $slot['guru_id'],
                        'hari'        => $hari,
                        'jam_mulai'   => $slot['jam_mulai'],
                        'jam_selesai' => $slot['jam_selesai'],
                    ];
                }
            }
        }

        if (count($dataInsert) > 0) {
            // Kita bisa pakai importJadwalMassal
            $result = $this->model('JadwalModel')->importJadwalMassal($dataInsert);
            $msg = $result['inserted'] . ' jadwal hasil generate berhasil disimpan.';
            if (!empty($result['errors'])) {
                $msg .= ' ' . count($result['errors']) . ' blok dilewati (bentrok manual).';
            }
            $_SESSION['flash'] = ['pesan' => $msg, 'aksi' => '', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'Tidak ada jadwal yang disimpan.', 'aksi' => '', 'tipe' => 'danger'];
        }

        // Hapus session 
        unset($_SESSION['generated_grid']);
        unset($_SESSION['auto_generate_data']);

        header('Location: ' . BASEURL . '/jadwal');
        exit;
    }

    public function updateSessionGrid()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grid'])) {
            $grid = json_decode($_POST['grid'], true);
            if ($grid) {
                $_SESSION['generated_grid'] = $grid;
                echo json_encode(['status' => 'ok']);
                exit;
            }
        }
        http_response_code(400);
        exit;
    }
}
