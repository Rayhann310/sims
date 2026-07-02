<?php

class Alumni extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Data Alumni';
        $data['alumni'] = $this->model('AlumniModel')->getAllAlumni();

        $db = new Database();
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni'");
        $data['total_alumni'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni' AND jenis_kelamin = 'L'");
        $data['alumni_l'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni' AND jenis_kelamin = 'P'");
        $data['alumni_p'] = $db->single()['total'] ?? 0;

        $this->view('templates/admin_header', $data);
        $this->view('alumni/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->model('AlumniModel')->tambahDataAlumni($_POST);
            if(isset($result['status']) && $result['status'] === true) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $errorMsg = isset($result['pesan']) ? "gagal ditambahkan (" . $result['pesan'] . ")" : "gagal ditambahkan";
                $_SESSION['flash'] = ['pesan' => $errorMsg, 'aksi' => '', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/alumni');
            exit;
        }
    }

    public function getubah()
    {
        echo json_encode($this->model('AlumniModel')->getAlumniById($_POST['id']));
    }

    public function ubah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('AlumniModel')->ubahDataAlumni($_POST)) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'diubah', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diubah', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/alumni');
            exit;
        }
    }

    public function hapus($id)
    {
        if($this->model('AlumniModel')->hapusDataAlumni($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/alumni');
        exit;
    }

    public function pindahSiswa($id)
    {
        if($this->model('AlumniModel')->pindahKeSiswa($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'Data Alumni', 'aksi' => 'berhasil dipindahkan kembali ke Siswa Aktif', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'Data Alumni', 'aksi' => 'gagal dipindahkan', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/alumni');
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
        $sheet->setCellValue('G1', 'No HP Wali (Mulai dengan 62)');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_Alumni.xlsx"');
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
                        if(!empty($row[0])) { // validasi NISN
                            $dataArray[] = [
                                'nisn' => $row[0],
                                'nama_lengkap' => $row[1] ?? '',
                                'jenis_kelamin' => $row[2] ?? 'L',
                                'tanggal_lahir' => $row[3] ?? null,
                                'alamat' => $row[4] ?? '',
                                'nama_wali' => $row[5] ?? '',
                                'no_hp_wali' => $row[6] ?? ''
                            ];
                        }
                    }

                    if(count($dataArray) > 0) {
                        $hasil = $this->model('AlumniModel')->importData($dataArray);
                        if($hasil['sukses'] > 0) {
                            $_SESSION['flash'] = ['pesan' => $hasil['sukses'] . ' data alumni diimport, ' . $hasil['gagal'] . ' gagal', 'aksi' => 'diproses', 'tipe' => 'success'];
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
        header('Location: ' . BASEURL . '/alumni');
        exit;
    }
}
