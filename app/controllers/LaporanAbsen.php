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

        $tgl_mulai = date('Y-m-01');
        $tgl_sampai = date('Y-m-t');

        $filter_type = $_GET['filter_type'] ?? 'rentang';
        $data['filter_type'] = $filter_type;
        $rombel_id  = $_GET['rombel_id']  ?? '';
        $data['rombel_id'] = $rombel_id;

        $db = new Database();

        if ($filter_type === 'rentang') {
            $tgl_mulai  = $_GET['tgl_mulai']  ?? date('Y-m-01');
            $tgl_sampai = $_GET['tgl_sampai'] ?? date('Y-m-t');
        } elseif ($filter_type === 'mingguan') {
            $bulan = $_GET['bulan'] ?? date('Y-m');
            $minggu_ke = $_GET['minggu_ke'] ?? 1;
            $data['bulan'] = $bulan;
            $data['minggu_ke'] = $minggu_ke;
            
            $year = date('Y', strtotime($bulan));
            $month = date('m', strtotime($bulan));
            
            if ($minggu_ke == 1) {
                $tgl_mulai = "$year-$month-01";
                $tgl_sampai = "$year-$month-07";
            } elseif ($minggu_ke == 2) {
                $tgl_mulai = "$year-$month-08";
                $tgl_sampai = "$year-$month-14";
            } elseif ($minggu_ke == 3) {
                $tgl_mulai = "$year-$month-15";
                $tgl_sampai = "$year-$month-21";
            } elseif ($minggu_ke == 4) {
                $tgl_mulai = "$year-$month-22";
                $tgl_sampai = "$year-$month-28";
            } elseif ($minggu_ke == 5) {
                $tgl_mulai = "$year-$month-29";
                $tgl_sampai = date('Y-m-t', strtotime("$year-$month-01"));
            }
        } elseif ($filter_type === 'semester') {
            $tahun_akademik_id = $_GET['tahun_akademik_id'] ?? '';
            $data['tahun_akademik_id'] = $tahun_akademik_id;
            
            if ($tahun_akademik_id) {
                $db->query("SELECT * FROM tahun_akademik WHERE id = :id");
                $db->bind('id', $tahun_akademik_id);
                $ta = $db->single();
                if ($ta) {
                    $parts = explode('/', $ta['nama_tahun']);
                    if (count($parts) == 2) {
                        $tahun_mulai = trim($parts[0]);
                        $tahun_akhir = trim($parts[1]);
                        if ($ta['semester'] == 1) {
                            $tgl_mulai = "$tahun_mulai-07-01";
                            $tgl_sampai = "$tahun_mulai-12-31";
                        } else {
                            $tgl_mulai = "$tahun_akhir-01-01";
                            $tgl_sampai = "$tahun_akhir-06-30";
                        }
                    }
                }
            }
        }

        $data['tgl_mulai']  = $tgl_mulai;
        $data['tgl_sampai'] = $tgl_sampai;

        $db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        $data['rombels'] = $db->resultSet();

        $akademikModel = $this->model('AkademikModel');
        $data['tahun_akademik'] = $akademikModel->getAllTahun();

        $laporanModel      = $this->model('LaporanAbsenModel');
        $data['mode']      = $laporanModel->getModeSiswa();
        $data['laporan']   = $laporanModel->getSummarySiswa($tgl_mulai, $tgl_sampai, $rombel_id);
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

    // =========================================================
    // REKAP PER KELAS (Akumulasi bulanan / semester)
    // =========================================================

    public function rekapKelas()
    {
        requireAccess('laporan_absen');

        $data['judul'] = 'Rekap Absensi Per Kelas';

        $laporanModel  = $this->model('LaporanAbsenModel');
        $semester      = $laporanModel->getRentangSemester();

        $mode_filter = $_GET['mode_filter'] ?? 'bulan';
        $rombel_id   = $_GET['rombel_id']   ?? '';
        $bulan       = $_GET['bulan']        ?? date('Y-m');

        if ($mode_filter === 'semester') {
            $tgl_mulai     = $semester['mulai'];
            $tgl_sampai    = $semester['sampai'];
            $label_periode = $semester['label'];
        } else {
            $tgl_mulai     = $bulan . '-01';
            $tgl_sampai    = date('Y-m-t', strtotime($tgl_mulai));
            $label_periode = date('F Y', strtotime($tgl_mulai));
        }

        $data['mode_filter']   = $mode_filter;
        $data['rombel_id']     = $rombel_id;
        $data['bulan']         = $bulan;
        $data['tgl_mulai']     = $tgl_mulai;
        $data['tgl_sampai']    = $tgl_sampai;
        $data['label_periode'] = $label_periode;
        $data['semester']      = $semester;
        $data['mode']          = $laporanModel->getModeSiswa();

        $db = new Database();
        $db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        $data['rombels'] = $db->resultSet();

        $data['rekap']     = [];
        $data['rombel_nama'] = '';
        if ($rombel_id) {
            $data['rekap'] = $laporanModel->getAkumulasiPerKelas($rombel_id, $tgl_mulai, $tgl_sampai);
            foreach ($data['rombels'] as $r) {
                if ($r['id'] == $rombel_id) {
                    $data['rombel_nama'] = $r['nama_rombel'];
                    break;
                }
            }
        }

        $this->view('templates/admin_header', $data);
        $this->view('laporan_absen/rekap_kelas', $data);
        $this->view('templates/admin_footer');
    }

    public function cetakRekapKelas()
    {
        requireAccess('laporan_absen');

        $laporanModel = $this->model('LaporanAbsenModel');
        $semester     = $laporanModel->getRentangSemester();

        $mode_filter = $_GET['mode_filter'] ?? 'bulan';
        $rombel_id   = $_GET['rombel_id']   ?? '';
        $bulan       = $_GET['bulan']        ?? date('Y-m');

        if ($mode_filter === 'semester') {
            $tgl_mulai     = $semester['mulai'];
            $tgl_sampai    = $semester['sampai'];
            $label_periode = $semester['label'];
        } else {
            $tgl_mulai     = $bulan . '-01';
            $tgl_sampai    = date('Y-m-t', strtotime($tgl_mulai));
            $label_periode = date('F Y', strtotime($tgl_mulai));
        }

        $rombelName = 'Semua Kelas';
        if ($rombel_id) {
            $db = new Database();
            $db->query("SELECT nama_rombel FROM rombel WHERE id = :id");
            $db->bind('id', $rombel_id);
            $r = $db->single();
            if ($r) $rombelName = $r['nama_rombel'];
        }

        $rekap = $rombel_id
            ? $laporanModel->getAkumulasiPerKelas($rombel_id, $tgl_mulai, $tgl_sampai)
            : [];

        $html  = '<style>';
        $html .= 'body{font-family:DejaVu Sans,sans-serif;font-size:11px;}';
        $html .= 'h2,h3{text-align:center;margin:3px 0;}';
        $html .= 'table{width:100%;border-collapse:collapse;margin-top:14px;}';
        $html .= 'th,td{border:1px solid #444;padding:5px 7px;}';
        $html .= 'th{background:#e8f5e9;text-align:center;font-weight:bold;}';
        $html .= '.c{text-align:center;} .alpa{color:#c0392b;font-weight:bold;}';
        $html .= '</style>';
        $html .= '<h2>REKAP ABSENSI SISWA PER KELAS</h2>';
        $html .= '<h3>Kelas: ' . htmlspecialchars($rombelName) . '</h3>';
        $html .= '<h3>Periode: ' . htmlspecialchars($label_periode) . '</h3>';
        $html .= '<table><thead><tr>';
        $html .= '<th>No</th><th>Nama Siswa</th><th>NISN</th>';
        $html .= '<th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpa</th><th>Total</th>';
        $html .= '</tr></thead><tbody>';

        $no = 1;
        foreach ($rekap as $row) {
            $html .= '<tr>';
            $html .= '<td class="c">' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . '</td>';
            $html .= '<td class="c">' . htmlspecialchars($row['nisn']) . '</td>';
            $html .= '<td class="c">' . $row['hadir'] . '</td>';
            $html .= '<td class="c">' . $row['sakit'] . '</td>';
            $html .= '<td class="c">' . $row['izin'] . '</td>';
            $html .= '<td class="c alpa">' . $row['alpa'] . '</td>';
            $html .= '<td class="c">' . $row['total'] . '</td>';
            $html .= '</tr>';
        }

        if (empty($rekap)) {
            $html .= '<tr><td colspan="8" style="text-align:center;padding:14px;">Tidak ada data untuk periode ini.</td></tr>';
        }

        $html .= '</tbody></table>';
        $html .= '<p style="margin-top:18px;font-size:9px;color:#555;">Dicetak: ' . date('d/m/Y H:i') . '</p>';

        if (!class_exists('\Dompdf\Dompdf')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
        }

        $opts = new \Dompdf\Options();
        $opts->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($opts);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fname = 'Rekap_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $rombelName) . '_' . str_replace(' ', '_', $label_periode) . '.pdf';
        $dompdf->stream($fname, ['Attachment' => false]);
        exit;
    }
}

