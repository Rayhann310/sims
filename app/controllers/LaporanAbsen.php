<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanAbsen extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        require_once 'app/core/HakAksesHelper.php';
        if ($_SESSION['user']['role'] === 'guru' && !hasMenuAccess('laporan_absen')) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Laporan Absensi';
        
        $tgl_mulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
        $tgl_sampai = $_GET['tgl_sampai'] ?? date('Y-m-t');
        $rombel_id = $_GET['rombel_id'] ?? '';

        $data['tgl_mulai'] = $tgl_mulai;
        $data['tgl_sampai'] = $tgl_sampai;
        $data['rombel_id'] = $rombel_id;

        $db = new Database();
        $db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        $data['rombels'] = $db->resultSet();

        $laporanModel = $this->model('LaporanAbsenModel');
        $data['mode'] = $laporanModel->getModeSiswa();
        $data['laporan'] = $laporanModel->getLaporan($tgl_mulai, $tgl_sampai, $rombel_id);
        $data['grafik'] = $laporanModel->getGrafikSummary($tgl_mulai, $tgl_sampai, $rombel_id);

        $this->view('templates/admin_header', $data);
        $this->view('laporan_absen/index', $data);
        $this->view('templates/admin_footer');
    }

    public function cetakPdf()
    {
        $tgl_mulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
        $tgl_sampai = $_GET['tgl_sampai'] ?? date('Y-m-t');
        $rombel_id = $_GET['rombel_id'] ?? '';

        $laporanModel = $this->model('LaporanAbsenModel');
        $mode = $laporanModel->getModeSiswa();
        $laporan = $laporanModel->getLaporan($tgl_mulai, $tgl_sampai, $rombel_id);
        
        $rombelName = 'Semua Kelas';
        if ($rombel_id) {
            $db = new Database();
            $db->query("SELECT nama_rombel FROM rombel WHERE id = :id");
            $db->bind('id', $rombel_id);
            $r = $db->single();
            if ($r) $rombelName = $r['nama_rombel'];
        }

        $html = '<style>
            body { font-family: sans-serif; font-size: 12px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #000; padding: 5px; text-align: left; }
            th { background-color: #f2f2f2; }
            h2, h3 { text-align: center; margin: 5px 0; }
        </style>';
        $html .= '<h2>Laporan Absensi Siswa</h2>';
        $html .= '<h3>Periode: ' . $tgl_mulai . ' s/d ' . $tgl_sampai . ' | Kelas: ' . $rombelName . '</h3>';
        
        $html .= '<table><thead><tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>';
        
        if ($mode === 'Per Jam Pelajaran') {
            $html .= '<th>Jam Ke</th><th>Mapel</th>';
        }

        $html .= '<th>Status</th>
            <th>Waktu</th>
        </tr></thead><tbody>';

        $no = 1;
        foreach ($laporan as $row) {
            $html .= '<tr>
                <td>'.$no++.'</td>
                <td>'.$row['tanggal'].'</td>
                <td>'.$row['nisn'].'</td>
                <td>'.$row['nama_lengkap'].'</td>
                <td>'.$row['kelas'].'</td>';
            if ($mode === 'Per Jam Pelajaran') {
                $html .= '<td>'.$row['jam_ke'].'</td><td>'.$row['nama_mapel'].'</td>';
            }
            $html .= '<td>'.$row['status'].'</td>
                <td>'.$row['waktu_scan'].'</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        require_once 'vendor/autoload.php';
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Absensi_{$tgl_mulai}_{$tgl_sampai}.pdf", array("Attachment" => false));
    }

    public function eksporExcel()
    {
        $tgl_mulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
        $tgl_sampai = $_GET['tgl_sampai'] ?? date('Y-m-t');
        $rombel_id = $_GET['rombel_id'] ?? '';

        $laporanModel = $this->model('LaporanAbsenModel');
        $mode = $laporanModel->getModeSiswa();
        $laporan = $laporanModel->getLaporan($tgl_mulai, $tgl_sampai, $rombel_id);

        require_once 'vendor/autoload.php';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Absensi');

        $headers = ['No', 'Tanggal', 'NISN', 'Nama Siswa', 'Kelas'];
        if ($mode === 'Per Jam Pelajaran') {
            $headers[] = 'Jam Ke';
            $headers[] = 'Mapel';
        }
        $headers[] = 'Status';
        $headers[] = 'Waktu Scan';

        // Write headers
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Write data
        $rowIdx = 2;
        $no = 1;
        foreach ($laporan as $row) {
            $col = 'A';
            $sheet->setCellValue($col++ . $rowIdx, $no++);
            $sheet->setCellValue($col++ . $rowIdx, $row['tanggal']);
            $sheet->setCellValue($col++ . $rowIdx, $row['nisn']);
            $sheet->setCellValue($col++ . $rowIdx, $row['nama_lengkap']);
            $sheet->setCellValue($col++ . $rowIdx, $row['kelas']);
            if ($mode === 'Per Jam Pelajaran') {
                $sheet->setCellValue($col++ . $rowIdx, $row['jam_ke']);
                $sheet->setCellValue($col++ . $rowIdx, $row['nama_mapel']);
            }
            $sheet->setCellValue($col++ . $rowIdx, $row['status']);
            $sheet->setCellValue($col++ . $rowIdx, $row['waktu_scan']);
            $rowIdx++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan_Absensi_'.$tgl_mulai.'_to_'.$tgl_sampai.'.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
