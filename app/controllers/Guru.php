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
        echo json_encode($this->model('GuruModel')->getGuruByIdWithJabatan($id));
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
        $sheet->setCellValue('D1', 'No. HP');
        $sheet->setCellValue('E1', 'Alamat');

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
        $sheet->setCellValue('E1', 'Mata Pelajaran');
        $sheet->setCellValue('F1', 'No. Telepon');

        $row = 2;
        foreach($data as $d) {
            $sheet->setCellValue('A'.$row, $d['nip']);
            $sheet->setCellValue('B'.$row, $d['nama_lengkap']);
            $sheet->setCellValue('C'.$row, $d['jenis_kelamin']);
            $sheet->setCellValue('D'.$row, $d['tanggal_lahir']);
            $sheet->setCellValue('E'.$row, $d['mata_pelajaran']);
            $sheet->setCellValue('F'.$row, $d['nomor_telepon']);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data_Guru.xlsx"');
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
                
                $data['judul'] = 'Preview Import Data Guru';
                $data['preview_data'] = $rows;
                
                // Simpan file sementara
                $tmp_name = time() . '_' . $_FILES['file_excel']['name'];
                move_uploaded_file($file_tmp, 'app/tmp/' . $tmp_name);
                $data['file_tmp'] = $tmp_name;

                $this->view('templates/admin_header', $data);
                $this->view('guru/preview', $data);
                $this->view('templates/admin_footer');
            } else {
                $_SESSION['flash'] = ['pesan' => 'Ekstensi file', 'aksi' => 'tidak didukung', 'tipe' => 'danger'];
                header('Location: ' . BASEURL . '/guru');
                exit;
            }
        }
    }

    public function import()
    {
        if(isset($_POST['file_tmp'])) {
            $file_path = 'app/tmp/' . $_POST['file_tmp'];
            
            if(file_exists($file_path)) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
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
                            'no_hp' => $row[3] ?? '',
                            'alamat' => $row[4] ?? ''
                        ];
                    }
                }

                $hasil = $this->model('GuruModel')->importData($dataArray);
                unlink($file_path);
                
                if($hasil['sukses'] > 0) {
                    $_SESSION['flash'] = ['pesan' => $hasil['sukses'] . ' data diimport, ' . $hasil['gagal'] . ' gagal', 'aksi' => 'diproses', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'semua data gagal diimport', 'tipe' => 'danger'];
                }
            }
        }
        header('Location: ' . BASEURL . '/guru');
        exit;
    }
}
