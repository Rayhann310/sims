<?php

class Keuangan extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        // Default ke halaman tagihan
        header('Location: ' . BASEURL . '/keuangan/tagihan');
        exit;
    }

    public function tagihan()
    {
        requireAccess('keuangan_tagihan');
        $data['judul'] = 'Data Tagihan & Pembayaran';
        $data['tagihan'] = $this->model('KeuanganModel')->getAllTagihan();
        $data['kategori'] = $this->model('KeuanganModel')->getAllKategori();
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/tagihan', $data);
        $this->view('templates/admin_footer');
    }

    public function tarif()
    {
        requireAccess('keuangan_tarif');
        $data['judul'] = 'Master Tarif Keuangan';
        $data['kategori'] = $this->model('KeuanganModel')->getAllKategori();
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/tarif', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahTarif()
    {
        if(isset($_POST['nama_kategori'])) {
            if($this->model('KeuanganModel')->tambahKategori($_POST) > 0) {
                Flasher::setFlash('Tarif berhasil', 'ditambahkan', 'success');
            } else {
                Flasher::setFlash('Tarif gagal', 'ditambahkan', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/keuangan/tarif');
        exit;
    }

    public function ubahTarif()
    {
        requireAccess('keuangan_tarif');
        if(isset($_POST['id']) && isset($_POST['nama_kategori'])) {
            if($this->model('KeuanganModel')->ubahKategori($_POST) > 0) {
                Flasher::setFlash('Tarif berhasil', 'diubah', 'success');
            } else {
                Flasher::setFlash('Tarif gagal', 'diubah', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/keuangan/tarif');
        exit;
    }

    public function hapusTarif($id)
    {
        requireAccess('keuangan_tarif');
        if($this->model('KeuanganModel')->hapusKategori($id) > 0) {
            Flasher::setFlash('Tarif berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Tarif gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/keuangan/tarif');
        exit;
    }

    public function riwayat()
    {
        requireAccess('keuangan_riwayat');
        $data['judul'] = 'Riwayat Pembayaran';
        
        $tahun_list = $this->model('KeuanganModel')->getTahunPembayaran();
        $data['tahun_tersedia'] = array_column($tahun_list, 'tahun');
        
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : (isset($data['tahun_tersedia'][0]) ? $data['tahun_tersedia'][0] : date('Y'));
        $data['tahun_aktif'] = $tahun;
        
        $data['riwayat_siswa'] = $this->model('KeuanganModel')->getRiwayatPembayaranBySiswa($tahun);
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/riwayat', $data);
        $this->view('templates/admin_footer');
    }

    public function generateTagihan()
    {
        requireAccess('keuangan_tagihan');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inserted = $this->model('KeuanganModel')->generateTagihanMasal($_POST);
            
            if($inserted > 0) {
                $_SESSION['flash'] = ['pesan' => "$inserted tagihan baru", 'aksi' => 'berhasil digenerate', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Generate gagal', 'aksi' => 'atau tagihan untuk bulan tersebut sudah ada untuk semua siswa', 'tipe' => 'warning'];
            }
            header('Location: ' . BASEURL . '/keuangan/tagihan');
            exit;
        }
    }

    public function bayar()
    {
        requireAccess('keuangan_tagihan');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('KeuanganModel')->prosesPembayaran($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'Pembayaran', 'aksi' => 'berhasil diproses', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Pembayaran', 'aksi' => 'gagal diproses', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/keuangan/tagihan');
            exit;
        }
    }
    public function kirimWA($tagihan_id)
    {
        requireAccess('keuangan_tagihan');
        if(isset($tagihan_id)) {
            $this->model('KeuanganModel')->sendFonnteWA($tagihan_id);
            $_SESSION['flash'] = ['pesan' => 'Notifikasi WA', 'aksi' => 'sedang dikirim di latar belakang', 'tipe' => 'success'];
        }
        header('Location: ' . BASEURL . '/keuangan/tagihan');
        exit;
    }

    public function kirimTagihanWA($tagihan_id)
    {
        requireAccess('keuangan_tagihan');
        if(isset($tagihan_id)) {
            if($this->model('KeuanganModel')->sendFonnteTagihanWA($tagihan_id)) {
                $_SESSION['flash'] = ['pesan' => 'Tagihan WA', 'aksi' => 'berhasil dikirim ke orang tua', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Tagihan WA', 'aksi' => 'gagal dikirim (cek token/nomor)', 'tipe' => 'danger'];
            }
        }
        header('Location: ' . BASEURL . '/keuangan/tagihan');
        exit;
    }

    public function batalBayar($tagihan_id)
    {
        requireAccess('keuangan_tagihan');
        if(isset($tagihan_id)) {
            if($this->model('KeuanganModel')->batalBayarTagihan($tagihan_id)) {
                $_SESSION['flash'] = ['pesan' => 'Pembayaran', 'aksi' => 'berhasil dibatalkan dan notifikasi WA telah terkirim', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Pembatalan', 'aksi' => 'gagal diproses', 'tipe' => 'danger'];
            }
        }
        header('Location: ' . BASEURL . '/keuangan/tagihan');
        exit;
    }

    // ==========================================
    // BUKU KAS & ANALISA KEUANGAN
    // ==========================================

    public function bukuKas()
    {
        requireAccess('keuangan_bukukas');
        $data['judul'] = 'Buku Kas & Analisa Keuangan';
        
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
        
        if (isset($_GET['filter']) && $_GET['filter'] == 'semua') {
            $bulan = '';
            $tahun = '';
        }

        $data['kas'] = $this->model('KeuanganModel')->getAllKas($bulan, $tahun);
        $data['statistik'] = $this->model('KeuanganModel')->getStatistikKas();
        $data['chart'] = $this->model('KeuanganModel')->getChartData(date('Y'));
        
        $data['filter_bulan'] = $bulan;
        $data['filter_tahun'] = $tahun ?: date('Y');

        $this->view('templates/admin_header', $data);
        $this->view('keuangan/buku_kas', $data);
        $this->view('templates/admin_footer');
    }

    public function prosesTambahKas()
    {
        requireAccess('keuangan_bukukas');
        if(isset($_POST['jenis'])) {
            if($this->model('KeuanganModel')->tambahKas($_POST) > 0) {
                Flasher::setFlash('Data Kas berhasil', 'ditambahkan', 'success');
            } else {
                Flasher::setFlash('Data Kas gagal', 'ditambahkan', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/keuangan/bukuKas');
        exit;
    }

    public function hapusKas($id)
    {
        requireAccess('keuangan_bukukas');
        if($this->model('KeuanganModel')->hapusKas($id) > 0) {
            Flasher::setFlash('Data Kas berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Data Kas gagal', 'dihapus (Mungkin terikat dengan SPP)', 'danger');
        }
        header('Location: ' . BASEURL . '/keuangan/bukuKas');
        exit;
    }

    public function exportExcelKas()
    {
        requireAccess('keuangan_bukukas');
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
        
        $kas = $this->model('KeuanganModel')->getAllKas($bulan, $tahun);
        
        $filename = "Buku_Kas_" . ($bulan ? $bulan . "_" : "") . ($tahun ? $tahun : "Semua") . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Tanggal', 'Jenis', 'Sumber', 'Keterangan', 'Nominal']);
        
        foreach($kas as $row) {
            fputcsv($output, [
                $row['id'],
                $row['tanggal'],
                $row['jenis'],
                $row['sumber'],
                $row['keterangan'],
                $row['nominal']
            ]);
        }
        fclose($output);
        exit;
    }
    public function cetakKwitansi($id)
    {
        try {
            requireAccess('keuangan_tagihan');
            
            $db = new Database();
            $db->query("
                SELECT t.*, s.nisn, u.nama_lengkap, k.nama_kategori 
                FROM tagihan_spp t 
                JOIN siswa s ON t.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN keuangan_kategori k ON t.kategori_id = k.id 
                WHERE t.id = :id
            ");
            $db->bind('id', $id);
            $tagihan = $db->single();
            
            if(!$tagihan) {
                die("Data tagihan tidak ditemukan.");
            }

            $db->query("SELECT * FROM pembayaran_spp WHERE tagihan_id = :id ORDER BY created_at DESC");
            $db->bind('id', $id);
            $pembayaran = $db->resultSet();
            
            $db->query("SELECT * FROM pengaturan ORDER BY id ASC LIMIT 1");
            $pengaturan = $db->single();
            
            $data['tagihan'] = $tagihan;
            $data['pembayaran'] = $pembayaran;
            $data['pengaturan'] = $pengaturan;
            
            ob_start();
            require_once __DIR__ . '/../views/keuangan/kwitansi_pdf.php';
            $html = ob_get_clean();
            
            if (!class_exists('\Dompdf\Dompdf')) {
                require_once __DIR__ . '/../../vendor/autoload.php';
            }
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A5', 'landscape');
            $dompdf->render();
            $dompdf->stream('Kwitansi_' . $tagihan['nisn'] . '_' . $tagihan['bulan'] . '.pdf', array("Attachment" => false));
        } catch (\Throwable $e) {
            die("ERROR: " . $e->getMessage() . " di " . $e->getFile() . " baris " . $e->getLine());
        }
    }

    // ==========================================
    // TUNGGAKAN
    // ==========================================

    public function tunggakan()
    {
        requireAccess('keuangan_tagihan'); // Use the same access right as Tagihan
        $data['judul'] = 'Manajemen Tunggakan';
        
        $db = new Database();
        // Ambil semua siswa termasuk alumni untuk input tunggakan
        $db->query("SELECT id, nisn, status, user_id FROM siswa ORDER BY status ASC, id DESC");
        $siswaRaw = $db->resultSet();
        
        // Populate names
        $siswa = [];
        foreach($siswaRaw as $s) {
            $db->query("SELECT nama_lengkap FROM users WHERE id = :id");
            $db->bind('id', $s['user_id']);
            $u = $db->single();
            $s['nama_lengkap'] = $u ? $u['nama_lengkap'] : 'Tanpa Nama';
            $siswa[] = $s;
        }
        $data['siswa'] = $siswa;
        $data['kategori'] = $this->model('KeuanganModel')->getAllKategori();
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/tunggakan', $data);
        $this->view('templates/admin_footer');
    }

    public function prosesTunggakan()
    {
        requireAccess('keuangan_tagihan');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inserted = $this->model('KeuanganModel')->tambahTunggakan($_POST);
            
            if($inserted > 0) {
                $_SESSION['flash'] = ['pesan' => "$inserted bulan tunggakan", 'aksi' => 'berhasil ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'menambahkan tunggakan (mungkin tagihan sudah ada)', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/keuangan/tunggakan');
            exit;
        }
    }

    public function downloadTemplateTunggakan()
    {
        requireAccess('keuangan_tagihan');
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        // Sheet 1: Template Input
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Tunggakan');
        
        $headers = ['Siswa ID', 'Kategori ID', 'Bulan', 'Tahun', 'Nominal', 'Tanggal Jatuh Tempo (1-31)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        
        // Buat Sheet 2: Referensi Siswa
        $sheetSiswa = $spreadsheet->createSheet();
        $sheetSiswa->setTitle('Referensi Siswa');
        $sheetSiswa->setCellValue('A1', 'Siswa ID');
        $sheetSiswa->setCellValue('B1', 'NISN');
        $sheetSiswa->setCellValue('C1', 'Nama Siswa');
        $sheetSiswa->setCellValue('D1', 'Status');
        
        $db = new Database();
        $db->query("SELECT s.id, s.nisn, s.status, u.nama_lengkap FROM siswa s JOIN users u ON s.user_id = u.id ORDER BY s.status ASC, u.nama_lengkap ASC");
        $siswaList = $db->resultSet();
        $row = 2;
        foreach ($siswaList as $s) {
            $sheetSiswa->setCellValue('A' . $row, $s['id']);
            $sheetSiswa->setCellValue('B' . $row, $s['nisn']);
            $sheetSiswa->setCellValue('C' . $row, $s['nama_lengkap']);
            $sheetSiswa->setCellValue('D' . $row, $s['status']);
            $row++;
        }
        
        // Buat Sheet 3: Referensi Kategori
        $sheetKategori = $spreadsheet->createSheet();
        $sheetKategori->setTitle('Referensi Kategori');
        $sheetKategori->setCellValue('A1', 'Kategori ID (Kosongkan jika SPP Biasa)');
        $sheetKategori->setCellValue('B1', 'Nama Kategori');
        
        $kategoriList = $this->model('KeuanganModel')->getAllKategori();
        $row = 2;
        foreach ($kategoriList as $k) {
            $sheetKategori->setCellValue('A' . $row, $k['id']);
            $sheetKategori->setCellValue('B' . $row, $k['nama_kategori']);
            $row++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_Tunggakan.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function previewImportTunggakan()
    {
        requireAccess('keuangan_tagihan');
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            $data['judul'] = 'Preview Import Tunggakan';
            
            require_once __DIR__ . '/../../vendor/autoload.php';
            $file = $_FILES['file_excel']['tmp_name'];
            
            $preview_data = [];
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $sheet = $spreadsheet->getSheetByName('Template Tunggakan');
                if (!$sheet) $sheet = $spreadsheet->getActiveSheet();
                
                $highestRow = $sheet->getHighestDataRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $siswa_id = $sheet->getCell('A' . $row)->getValue();
                    if(empty($siswa_id)) continue;
                    
                    $kategori_id = $sheet->getCell('B' . $row)->getValue();
                    $bulan = $sheet->getCell('C' . $row)->getValue();
                    $tahun = $sheet->getCell('D' . $row)->getValue();
                    $nominal = $sheet->getCell('E' . $row)->getValue();
                    $tgl_jt = $sheet->getCell('F' . $row)->getValue();
                    
                    if(empty($bulan) || empty($tahun) || empty($nominal)) continue;
                    
                    $tgl = empty($tgl_jt) ? 10 : (int)$tgl_jt;
                    $bulanNum = array_search(ucfirst(strtolower($bulan)), ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']) + 1;
                    if($bulanNum === false) $bulanNum = 1;
                    $jatuh_tempo = sprintf("%04d-%02d-%02d", $tahun, $bulanNum, $tgl);
                    
                    $preview_data[] = [
                        'siswa_id' => $siswa_id,
                        'kategori_id' => empty($kategori_id) ? null : $kategori_id,
                        'bulan' => ucfirst(strtolower($bulan)),
                        'tahun' => $tahun,
                        'nominal' => $nominal,
                        'jatuh_tempo' => $jatuh_tempo
                    ];
                }
            } catch(Exception $e) {
                Flasher::setFlash('Error membaca file Excel', 'pastikan format benar', 'danger');
                header('Location: ' . BASEURL . '/keuangan/tunggakan');
                exit;
            }
            
            $data['preview_data'] = $preview_data;
            
            $this->view('templates/admin_header', $data);
            $this->view('keuangan/tunggakan_preview', $data);
            $this->view('templates/admin_footer');
        } else {
            header('Location: ' . BASEURL . '/keuangan/tunggakan');
            exit;
        }
    }

    public function prosesImportTunggakan()
    {
        requireAccess('keuangan_tagihan');
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['import_data'])) {
            $data = json_decode(base64_decode($_POST['import_data']), true);
            if(!is_array($data) || empty($data)) {
                Flasher::setFlash('Data kosong', 'gagal import', 'danger');
                header('Location: ' . BASEURL . '/keuangan/tunggakan');
                exit;
            }
            
            $inserted = 0;
            $db = new Database();
            foreach($data as $row) {
                // Check exist
                if (!empty($row['kategori_id'])) {
                    $db->query("SELECT id FROM tagihan_spp WHERE siswa_id = :siswa_id AND bulan = :bulan AND tahun = :tahun AND kategori_id = :kategori_id");
                    $db->bind('kategori_id', $row['kategori_id']);
                } else {
                    $db->query("SELECT id FROM tagihan_spp WHERE siswa_id = :siswa_id AND bulan = :bulan AND tahun = :tahun AND kategori_id IS NULL");
                }
                $db->bind('siswa_id', $row['siswa_id']);
                $db->bind('bulan', $row['bulan']);
                $db->bind('tahun', $row['tahun']);
                $db->single();
                
                if($db->rowCount() == 0) {
                    $db->query("INSERT INTO tagihan_spp (siswa_id, kategori_id, bulan, tahun, nominal, jatuh_tempo) VALUES (:siswa_id, :kategori_id, :bulan, :tahun, :nominal, :jatuh_tempo)");
                    $db->bind('siswa_id', $row['siswa_id']);
                    $db->bind('kategori_id', $row['kategori_id']);
                    $db->bind('bulan', $row['bulan']);
                    $db->bind('tahun', $row['tahun']);
                    $db->bind('nominal', $row['nominal']);
                    $db->bind('jatuh_tempo', $row['jatuh_tempo']);
                    $db->execute();
                    $inserted++;
                }
            }
            
            Flasher::setFlash("$inserted tagihan/tunggakan", 'berhasil diimpor', 'success');
            header('Location: ' . BASEURL . '/keuangan/tunggakan');
            exit;
        }
    }
}
