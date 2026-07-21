<?php

class ApiAbsensi extends Controller {
    public function __construct()
    {
        header('Content-Type: application/json');
    }

    public function syncKiosk()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['data']) && is_array($input['data'])) {
                $model = $this->model('AbsensiGuruModel');
                $sukses = 0;
                
                foreach ($input['data'] as $item) {
                    $data = [
                        'guru_id' => $item['guru_id'],
                        'status' => $item['status']
                    ];
                    $res = $model->absen($data);
                    if ($res['status']) $sukses++;
                }

                echo json_encode(['status' => true, 'message' => "$sukses data tersinkronisasi"]);
                exit;
            }
        }
        echo json_encode(['status' => false, 'message' => 'Invalid request']);
    }

    public function syncScanner()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['data']) && is_array($input['data'])) {
                $model = $this->model('AbsensiSiswaModel');
                $sukses = 0;
                $lastResult = null;
                
                foreach ($input['data'] as $item) {
                    $data = [
                        'qr_token' => $item['qr_token'],
                        'waktu_scan' => $item['waktu_scan']
                    ];
                    $res = $model->absenScan($data);
                    if ($res['status']) {
                        $sukses++;
                        $lastResult = $res;
                    }
                }

                $response = ['status' => true, 'message' => "$sukses data tersinkronisasi"];
                if ($lastResult) {
                    $response['pesan'] = $lastResult['pesan'];
                    $response['tipe'] = $lastResult['tipe'] ?? 'masuk';
                }
                echo json_encode($response);
                exit;
            }
        }
        echo json_encode(['status' => false, 'message' => 'Invalid request']);
    }
}
