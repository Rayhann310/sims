<?php

class Siswa extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Data Siswa';
        
        $filters = [
            'kelas' => $_GET['kelas'] ?? '',
            'jk' => $_GET['jk'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];
        
        $data['filters'] = $filters;
        $data['siswa'] = $this->model('SiswaModel')->getAllSiswa($filters);
        $data['filter_options'] = $this->model('SiswaModel')->getFilterOptions();
        
        $stats = $this->model('SiswaModel')->getSiswaStats();
        $data['stats'] = $stats;

        // Data for charts
        $chartData = $this->model('SiswaModel')->getSiswaPerKelasStats();
        $data['chart_labels'] = json_encode(array_column($chartData, 'label'));
        $data['chart_data'] = json_encode(array_column($chartData, 'jumlah'));

        $this->view('templates/admin_header', $data);
        $this->view('siswa/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->model('SiswaModel')->tambahDataSiswa($_POST);
            if(isset($result['status']) && $result['status'] === true) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $errorMsg = isset($result['pesan']) ? "gagal ditambahkan (" . $result['pesan'] . ")" : "gagal ditambahkan";
                $_SESSION['flash'] = ['pesan' => $errorMsg, 'aksi' => '', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/siswa');
            exit;
        }
    }

    public function detail($id)
    {
        echo json_encode($this->model('SiswaModel')->getSiswaById($id));
    }

    public function getubah()
    {
        echo json_encode($this->model('SiswaModel')->getSiswaById($_POST['id']));
    }

    public function getulangtahun()
    {
        echo json_encode($this->model('SiswaModel')->getUlangTahunHariIni());
    }

    public function ubah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('SiswaModel')->ubahDataSiswa($_POST)) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'diubah', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diubah', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/siswa');
            exit;
        }
    }

    public function hapus($id)
    {
        if($this->model('SiswaModel')->hapusDataSiswa($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/siswa');
        exit;
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'Jenis Kelamin (L/P)');
        $sheet->setCellValue('D1', 'Tanggal Lahir (YYYY-MM-DD)');
        $sheet->setCellValue('E1', 'Alamat');
        $sheet->setCellValue('F1', 'Nama Wali');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Siswa.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export()
    {
        $data = $this->model('SiswaModel')->getAllSiswa();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'Jenis Kelamin');
        $sheet->setCellValue('D1', 'Tanggal Lahir');
        $sheet->setCellValue('E1', 'Alamat');
        $sheet->setCellValue('F1', 'Nama Wali');

        $row = 2;
        foreach($data as $d) {
            $sheet->setCellValue('A'.$row, $d['nisn']);
            $sheet->setCellValue('B'.$row, $d['nama_lengkap']);
            $sheet->setCellValue('C'.$row, $d['jenis_kelamin']);
            $sheet->setCellValue('D'.$row, $d['tanggal_lahir']);
            $sheet->setCellValue('E'.$row, $d['alamat']);
            $sheet->setCellValue('F'.$row, $d['nama_wali']);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data_Siswa.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function preview()
    {
        if(isset($_FILES['file_excel']['name']) && $_FILES['file_excel']['name'] != '') {
            $file_tmp = $_FILES['file_excel']['tmp_name'];
            $file_ext = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);
            
            if($file_ext == 'xlsx' || $file_ext == 'xls') {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Hapus baris header
                unset($rows[0]);
                
                $data['judul'] = 'Preview Import Data Siswa';
                $data['preview_data'] = $rows;
                
                // Simpan file sementara
                $tmp_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['file_excel']['name']);
                if(move_uploaded_file($file_tmp, __DIR__ . '/../tmp/' . $tmp_name)) {
                    $data['file_tmp'] = $tmp_name;
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Gagal menyimpan file', 'aksi' => 'ke direktori tmp', 'tipe' => 'danger'];
                    header('Location: ' . BASEURL . '/siswa');
                    exit;
                }

                $this->view('templates/admin_header', $data);
                $this->view('siswa/preview', $data);
                $this->view('templates/admin_footer');
            } else {
                $_SESSION['flash'] = ['pesan' => 'Ekstensi file', 'aksi' => 'tidak didukung', 'tipe' => 'danger'];
                header('Location: ' . BASEURL . '/siswa');
                exit;
            }
        }
    }

    public function import()
    {
        if(isset($_POST['file_tmp'])) {
            $file_path = __DIR__ . '/../tmp/' . $_POST['file_tmp'];
            
            if(file_exists($file_path)) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                unset($rows[0]); // hapus header
                
                $dataArray = [];
                foreach($rows as $row) {
                    if(!empty($row[0])) { // validasi NISN
                        $dataArray[] = [
                            'nisn' => $row[0],
                            'nama_lengkap' => $row[1],
                            'jenis_kelamin' => $row[2],
                            'tanggal_lahir' => $row[3],
                            'alamat' => $row[4],
                            'nama_wali' => $row[5]
                        ];
                    }
                }

                $hasil = $this->model('SiswaModel')->importData($dataArray);
                unlink($file_path);
                
                if($hasil['sukses'] > 0) {
                    $_SESSION['flash'] = ['pesan' => $hasil['sukses'] . ' data diimport, ' . $hasil['gagal'] . ' gagal', 'aksi' => 'diproses', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'semua data gagal diimport', 'tipe' => 'danger'];
                }
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'File tidak ditemukan', 'tipe' => 'danger'];
            }
        } else {
            $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'Tidak ada file', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/siswa');
        exit;
    }
}
