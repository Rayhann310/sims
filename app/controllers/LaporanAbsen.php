<?php

class LaporanAbsen extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        requireAccess('laporan_absen');
    }

    public function index()
    {
        $data['judul'] = 'Laporan Absensi';

        $tgl_mulai  = $_GET['tgl_mulai']  ?? date('Y-m-01');
        $tgl_sampai = $_GET['tgl_sampai'] ?? date('Y-m-t');
        $rombel_id  = $_GET['rombel_id']  ?? '';

        $data['tgl_mulai']  = $tgl_mulai;
        $data['tgl_sampai'] = $tgl_sampai;
        $data['rombel_id']  = $rombel_id;

        $db = new Database();
        $db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        $data['rombels'] = $db->resultSet();

        $laporanModel      = $this->model('LaporanAbsenModel');
        $data['mode']      = $laporanModel->getModeSiswa();
        $data['laporan']   = $laporanModel->getLaporan($tgl_mulai, $tgl_sampai, $rombel_id);
        $data['grafik']    = $laporanModel->getGrafikSummary($tgl_mulai, $tgl_sampai, $rombel_id);

        $this->view('templates/admin_header', $data);
        $this->view('laporan_absen/index', $data);
        $this->view('templates/admin_footer');
    }

    public function cetakPdf()
    {
        requireAccess('laporan_absen');

        $tgl_mulai  = $_GET['tgl_mulai']  ?? date('Y-m-01');
        $tgl_sampai = $_GET['tgl_sampai'] ?? date('Y-m-t');
        $rombel_id  = $_GET['rombel_id']  ?? '';

        $laporanModel = $this->model('LaporanAbsenModel');
        $mode         = $laporanModel->getModeSiswa();
        $laporan      = $laporanModel->getLaporan($tgl_mulai, $tgl_sampai, $rombel_id);

        $rombelName = 'Semua Kelas';
        if ($rombel_id) {
            $db = new Database();
            $db->query("SELECT nama_rombel FROM rombel WHERE id = :id");
            $db->bind('id', $rombel_id);
            $r = $db->single();
            if ($r) $rombelName = $r['nama_rombel'];
        }

        $html  = '<style>';
        $html .= 'body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 15px; }';
        $html .= 'th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; }';
        $html .= 'th { background-color: #e8f5e9; font-weight: bold; }';
        $html .= 'h2, h3 { text-align: center; margin: 4px 0; }';
        $html .= '</style>';
        $html .= '<h2>Laporan Absensi Siswa</h2>';
        $html .= '<h3>Periode: ' . htmlspecialchars($tgl_mulai) . ' s/d ' . htmlspecialchars($tgl_sampai) . ' | Kelas: ' . htmlspecialchars($rombelName) . '</h3>';
        $html .= '<h3>Mode: ' . htmlspecialchars($mode) . '</h3>';
        $html .= '<table><thead><tr>';
        $html .= '<th>No</th><th>Tanggal</th><th>NISN</th><th>Nama Siswa</th><th>Kelas</th>';

        if ($mode === 'Per Jam Pelajaran') {
            $html .= '<th>Jam Ke</th><th>Mapel</th>';
        }

        $html .= '<th>Status</th><th>Waktu</th>';
        $html .= '</tr></thead><tbody>';

        $no = 1;
        foreach ($laporan as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($row['tanggal']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['nisn']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['kelas']) . '</td>';
            if ($mode === 'Per Jam Pelajaran') {
                $html .= '<td>' . htmlspecialchars($row['jam_ke']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_mapel'] ?? '-') . '</td>';
            }
            $html .= '<td>' . htmlspecialchars($row['status']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['waktu_scan']) . '</td>';
            $html .= '</tr>';
        }

        if (empty($laporan)) {
            $colspan = $mode === 'Per Jam Pelajaran' ? 9 : 7;
            $html .= '<tr><td colspan="' . $colspan . '" style="text-align:center;">Tidak ada data absensi.</td></tr>';
        }

        $html .= '</tbody></table>';

        if (!class_exists('\Dompdf\Dompdf')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
        }

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Absensi_{$tgl_mulai}_{$tgl_sampai}.pdf", ['Attachment' => false]);
        exit;
    }

    public function eksporExcel()
    {
        requireAccess('laporan_absen');

        $tgl_mulai  = $_GET['tgl_mulai']  ?? date('Y-m-01');
        $tgl_sampai = $_GET['tgl_sampai'] ?? date('Y-m-t');
        $rombel_id  = $_GET['rombel_id']  ?? '';

        $laporanModel = $this->model('LaporanAbsenModel');
        $mode         = $laporanModel->getModeSiswa();
        $laporan      = $laporanModel->getLaporan($tgl_mulai, $tgl_sampai, $rombel_id);

        require_once __DIR__ . '/../../vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Absensi');

        $headers = ['No', 'Tanggal', 'NISN', 'Nama Siswa', 'Kelas'];
        if ($mode === 'Per Jam Pelajaran') {
            $headers[] = 'Jam Ke';
            $headers[] = 'Mapel';
        }
        $headers[] = 'Status';
        $headers[] = 'Waktu Scan';

        // Write headers — use numeric column index to avoid deprecated string increment
        $colIdx = 1;
        foreach ($headers as $h) {
            $sheet->setCellValueByColumnAndRow($colIdx, 1, $h);
            $sheet->getStyleByColumnAndRow($colIdx, 1)->getFont()->setBold(true);
            $colIdx++;
        }

        // Write data
        $rowIdx = 2;
        $no     = 1;
        foreach ($laporan as $row) {
            $c = 1;
            $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $no++);
            $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['tanggal']);
            $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['nisn']);
            $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['nama_lengkap']);
            $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['kelas']);
            if ($mode === 'Per Jam Pelajaran') {
                $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['jam_ke']);
                $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['nama_mapel'] ?? '-');
            }
            $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['status']);
            $sheet->setCellValueByColumnAndRow($c++, $rowIdx, $row['waktu_scan']);
            $rowIdx++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan_Absensi_' . $tgl_mulai . '_to_' . $tgl_sampai . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
