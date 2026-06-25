<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

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
        // Initial state: don't load any jadwal until a rombel is selected, or we can load by default for active TA
        $data['rombel'] = [];
        $data['jadwal'] = [];
        
        $this->view('templates/admin_header', $data);
        $this->view('jadwal/index', $data);
        $this->view('templates/admin_footer');
    }

    // Ajax endpoint to get jadwal by rombel
    public function getJadwalAjax($rombel_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->model('JadwalModel')->getJadwalByRombel($rombel_id));
        exit;
    }

    public function import()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            $rombel_id = $_POST['rombel_id'];
            $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            
            if(isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {
                $arr_file = explode('.', $_FILES['file_excel']['name']);
                $extension = end($arr_file);
                
                $spreadsheet = IOFactory::load($_FILES['file_excel']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                
                $dataInsert = [];
                // Skip row 1 (header)
                for($i = 1; $i < count($sheetData); $i++) {
                    $mapel_id = $sheetData[$i][0];
                    $guru_id = $sheetData[$i][1];
                    $hari = $sheetData[$i][2];
                    $jam_mulai = $sheetData[$i][3];
                    $jam_selesai = $sheetData[$i][4];
                    
                    if($mapel_id != "" && $guru_id != "" && $hari != "") {
                        $dataInsert[] = [
                            'rombel_id' => $rombel_id,
                            'mapel_id'  => $mapel_id,
                            'guru_id'   => $guru_id,
                            'hari'      => $hari,
                            'jam_mulai' => $jam_mulai,
                            'jam_selesai'=> $jam_selesai
                        ];
                    }
                }
                
                if(count($dataInsert) > 0) {
                    $inserted = $this->model('JadwalModel')->importJadwalMassal($dataInsert);
                    $_SESSION['flash'] = ['pesan' => $inserted . ' data jadwal berhasil', 'aksi' => 'diimport ke rombel ini', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diimport, data kosong/tidak valid', 'tipe' => 'danger'];
                }
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diimport, format file harus .xlsx/.xls', 'tipe' => 'danger'];
            }
            
            header('Location: ' . BASEURL . '/jadwal');
            exit;
        }
    }
}
