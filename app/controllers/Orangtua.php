<?php

class Orangtua extends Controller {
    public function __construct()
    {
        requireAccess('orangtua');
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Data Orang Tua / Wali';
        
        $filters = [
            'kelas' => $_GET['kelas'] ?? '',
            'jk' => $_GET['jk'] ?? '',
            'status' => $_GET['status'] ?? 'Aktif'
        ];
        
        $data['filters'] = $filters;
        $data['siswa'] = $this->model('SiswaModel')->getAllSiswa($filters);
        $data['filter_options'] = $this->model('SiswaModel')->getFilterOptions();

        $this->view('templates/admin_header', $data);
        $this->view('orangtua/index', $data);
        $this->view('templates/admin_footer');
    }

    public function ubah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $nama_wali = $_POST['nama_wali'] ?? '';
            $no_hp_wali = $_POST['no_hp_wali'] ?? '';
            
            $db = new Database();
            $db->query("UPDATE siswa SET nama_wali = :nama_wali, no_hp_wali = :no_hp_wali WHERE id = :id");
            $db->bind('nama_wali', htmlspecialchars($nama_wali));
            $db->bind('no_hp_wali', htmlspecialchars($no_hp_wali));
            $db->bind('id', $id);
            
            try {
                if($db->execute()) {
                    $_SESSION['flash'] = ['pesan' => 'Data Wali berhasil diperbarui', 'aksi' => '', 'tipe' => 'success'];
                }
            } catch (PDOException $e) {
                if(strpos($e->getMessage(), 'Unknown column') !== false) {
                    $db_heal = new Database();
                    $db_heal->query("ALTER TABLE siswa ADD COLUMN no_hp_wali VARCHAR(20) DEFAULT NULL");
                    $db_heal->execute();
                    
                    // Re-execute
                    $db->execute();
                    $_SESSION['flash'] = ['pesan' => 'Data Wali berhasil diperbarui', 'aksi' => '', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'memperbarui data wali', 'tipe' => 'danger'];
                }
            }
            header('Location: ' . BASEURL . '/orangtua');
            exit;
        }
    }
    public function template()
    {
        require_once '../vendor/autoload.php';
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'Nama Siswa (Info Saja)');
        $sheet->setCellValue('C1', 'Nama Wali');
        $sheet->setCellValue('D1', 'No HP Wali (Mulai 62)');

        // Format column
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_OrangTua.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            require_once '../vendor/autoload.php';
            
            $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            
            if(isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {
                $arr_file = explode('.', $_FILES['file_excel']['name']);
                $extension = end($arr_file);
                
                if('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else if('xls' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                
                $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                
                $db = new Database();
                $berhasil = 0;
                
                for($i = 1; $i < count($sheetData); $i++) {
                    $nisn = $sheetData[$i][0] ?? '';
                    $nama_wali = $sheetData[$i][2] ?? '';
                    $no_hp_wali = $sheetData[$i][3] ?? '';
                    
                    if(!empty($nisn) && (!empty($nama_wali) || !empty($no_hp_wali))) {
                        // Bersihkan nomor HP, pastikan format 62
                        if(!empty($no_hp_wali)) {
                            $no_hp_wali = preg_replace('/[^0-9]/', '', $no_hp_wali);
                            if(substr($no_hp_wali, 0, 1) == '0') {
                                $no_hp_wali = '62' . substr($no_hp_wali, 1);
                            } else if (substr($no_hp_wali, 0, 2) != '62') {
                                $no_hp_wali = '62' . $no_hp_wali;
                            }
                        }
                        
                        $db->query("UPDATE siswa SET nama_wali = :nama_wali, no_hp_wali = :no_hp_wali WHERE nisn = :nisn");
                        $db->bind('nama_wali', htmlspecialchars($nama_wali));
                        $db->bind('no_hp_wali', htmlspecialchars($no_hp_wali));
                        $db->bind('nisn', $nisn);
                        
                        try {
                            if($db->execute()) {
                                // Since execute returns void in Database.php, we just check rowCount
                            }
                            if($db->rowCount() > 0) $berhasil++;
                        } catch (PDOException $e) {
                            if(strpos($e->getMessage(), 'Unknown column') !== false) {
                                $db_heal = new Database();
                                $db_heal->query("ALTER TABLE siswa ADD COLUMN no_hp_wali VARCHAR(20) DEFAULT NULL");
                                $db_heal->execute();
                                
                                $db->execute(); // Retry
                                if($db->rowCount() > 0) $berhasil++;
                            }
                        }
                    }
                }
                
                $_SESSION['flash'] = ['pesan' => "Berhasil import data", 'aksi' => "($berhasil data diperbarui)", 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal import', 'aksi' => 'format file tidak didukung', 'tipe' => 'danger'];
            }
            
            header('Location: ' . BASEURL . '/orangtua');
            exit;
        }
    }
}
