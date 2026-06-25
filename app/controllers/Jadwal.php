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

    // AJAX: dapatkan jadwal by rombel
    public function getJadwalAjax($rombel_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->model('JadwalModel')->getJadwalByRombel($rombel_id));
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
}
