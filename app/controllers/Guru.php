<?php

class Guru extends Controller {
    public function __construct()
    {
        // Hanya admin yang bisa akses
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Data Guru';
        
        $filters = [
            'jk' => $_GET['jk'] ?? ''
        ];
        
        $data['filters'] = $filters;
        $data['guru'] = $this->model('GuruModel')->getAllGuru($filters);
        
        $data['stats'] = $this->model('GuruModel')->getGuruStats();
        
        $chartData = $this->model('GuruModel')->getGuruChartStats();
        $data['chart_labels'] = json_encode(array_column($chartData, 'label'));
        $data['chart_data'] = json_encode(array_column($chartData, 'jumlah'));
        $data['jabatan_list'] = $this->model('GuruModel')->getJabatanList();

        $this->view('templates/admin_header', $data);
        $this->view('guru/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->model('GuruModel')->tambahDataGuru($_POST);
            if(isset($result['status']) && $result['status'] === true) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $errorMsg = isset($result['pesan']) ? "gagal ditambahkan (" . $result['pesan'] . ")" : "gagal ditambahkan";
                $_SESSION['flash'] = ['pesan' => $errorMsg, 'aksi' => '', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/guru');
            exit;
        }
    }

    public function detail($id)
    {
        header('Content-Type: application/json');
        try {
            $guru = $this->model('GuruModel')->getGuruByIdWithJabatan($id);
            if (!$guru) {
                echo json_encode(['error' => 'Data tidak ditemukan']);
            } else {
                echo json_encode($guru);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function getubah()
    {
        echo json_encode($this->model('GuruModel')->getGuruById($_POST['id']));
    }

    public function getwalikelas()
    {
        echo json_encode($this->model('GuruModel')->getWaliKelasList());
    }

    public function getulangtahun()
    {
        echo json_encode($this->model('GuruModel')->getUlangTahunHariIni());
    }

    public function ubah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('GuruModel')->ubahDataGuru($_POST)) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'diubah', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diubah', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/guru');
            exit;
        }
    }

    public function hapus($id)
    {
        if($this->model('GuruModel')->hapusDataGuru($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/guru');
        exit;
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'Jenis Kelamin (L/P)');
        $sheet->setCellValue('D1', 'Tanggal Lahir (YYYY-MM-DD)');
        $sheet->setCellValue('E1', 'No. HP');
        $sheet->setCellValue('F1', 'Alamat');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Guru.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export()
    {
        $data = $this->model('GuruModel')->getAllGuru();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'Jenis Kelamin');
        $sheet->setCellValue('D1', 'Tanggal Lahir');
        $sheet->setCellValue('E1', 'No. HP');
        $sheet->setCellValue('F1', 'Alamat');

        $row = 2;
        foreach($data as $d) {
            $sheet->setCellValue('A'.$row, $d['nip']);
            $sheet->setCellValue('B'.$row, $d['nama_lengkap']);
            $sheet->setCellValue('C'.$row, $d['jenis_kelamin']);
            $sheet->setCellValue('D'.$row, $d['tanggal_lahir']);
            $sheet->setCellValue('E'.$row, $d['no_hp']);
            $sheet->setCellValue('F'.$row, $d['alamat']);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data_Guru.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        if(isset($_FILES['file_excel']['name']) && $_FILES['file_excel']['name'] != '') {
            $file_tmp = $_FILES['file_excel']['tmp_name'];
            $file_ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            
            if($file_ext == 'xlsx' || $file_ext == 'xls') {
                try {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $rows = $worksheet->toArray();
                    
                    unset($rows[0]); // hapus header
                    
                    $dataArray = [];
                    foreach($rows as $row) {
                        if(!empty($row[0])) { // validasi NIP
                            $dataArray[] = [
                                'nip' => $row[0],
                                'nama_lengkap' => $row[1],
                                'jenis_kelamin' => $row[2],
                                'tanggal_lahir' => $row[3] ?? null,
                                'no_hp' => $row[4] ?? '',
                                'alamat' => $row[5] ?? ''
                            ];
                        }
                    }

                    if(count($dataArray) > 0) {
                        $hasil = $this->model('GuruModel')->importData($dataArray);
                        if($hasil['sukses'] > 0) {
                            $_SESSION['flash'] = ['pesan' => $hasil['sukses'] . ' data diimport, ' . $hasil['gagal'] . ' gagal', 'aksi' => 'diproses', 'tipe' => 'success'];
                        } else {
                            $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'semua data gagal diimport (mungkin duplikat)', 'tipe' => 'danger'];
                        }
                    } else {
                        $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'Tidak ada data valid di dalam file', 'tipe' => 'danger'];
                    }
                } catch (Exception $e) {
                    $_SESSION['flash'] = ['pesan' => 'Gagal membaca file', 'aksi' => $e->getMessage(), 'tipe' => 'danger'];
                }
            } else {
                $_SESSION['flash'] = ['pesan' => 'Ekstensi file', 'aksi' => 'tidak didukung', 'tipe' => 'danger'];
            }
        } else {
            $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'Tidak ada file yang diunggah', 'tipe' => 'danger'];
        }
        
        header('Location: ' . BASEURL . '/guru');
        exit;
    }
    public function hapus_massal()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guru_ids'])) {
            $ids = $_POST['guru_ids'];
            if(is_array($ids) && count($ids) > 0) {
                $sukses = 0;
                $gagal = 0;
                foreach($ids as $id) {
                    if($this->model('GuruModel')->hapusDataGuru($id) > 0) {
                        $sukses++;
                    } else {
                        $gagal++;
                    }
                }
                
                if($sukses > 0) {
                    $_SESSION['flash'] = ['pesan' => $sukses . ' data guru', 'aksi' => 'berhasil dihapus massal', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Data guru', 'aksi' => 'gagal dihapus massal', 'tipe' => 'danger'];
                }
            }
        }
        header('Location: ' . BASEURL . '/guru');
        exit;
    }
}
