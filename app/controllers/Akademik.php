<?php

class Akademik extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        header('Location: ' . BASEURL . '/akademik/tahun');
        exit;
    }

    // ==========================================
    // TAHUN AKADEMIK
    // ==========================================
    public function tahun()
    {
        requireAccess('akademik_tahun');
        $data['judul'] = 'Manajemen Tahun Akademik';
        $data['tahun'] = $this->model('AkademikModel')->getAllTahun();

        $this->view('templates/admin_header', $data);
        $this->view('akademik/tahun', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahTahun()
    {
        requireAccess('akademik_tahun');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('AkademikModel')->tambahTahun($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'ditambahkan', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/akademik/tahun');
            exit;
        }
    }

    public function hapusTahun($id)
    {
        requireAccess('akademik_tahun');
        if($this->model('AkademikModel')->hapusTahun($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/akademik/tahun');
        exit;
    }

    public function setAktifTahun($id)
    {
        requireAccess('akademik_tahun');
        if($this->model('AkademikModel')->setAktifTahun($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'diaktifkan', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diaktifkan', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/akademik/tahun');
        exit;
    }

    // ==========================================
    // MATA PELAJARAN
    // ==========================================
    public function mapel()
    {
        requireAccess('akademik_mapel');
        $data['judul'] = 'Manajemen Mata Pelajaran';
        $data['mapel'] = $this->model('AkademikModel')->getAllMapel();

        $this->view('templates/admin_header', $data);
        $this->view('akademik/mapel', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahMapel()
    {
        requireAccess('akademik_mapel');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('AkademikModel')->tambahMapel($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'ditambahkan', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/akademik/mapel');
            exit;
        }
    }

    public function hapusMapel($id)
    {
        requireAccess('akademik_mapel');
        if($this->model('AkademikModel')->hapusMapel($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/akademik/mapel');
        exit;
    }

    public function exportMapel()
    {
        requireAccess('akademik_mapel');
        $mapel = $this->model('AkademikModel')->getAllMapel();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'Kode Mapel');
        $sheet->setCellValue('B1', 'Nama Mata Pelajaran');
        $sheet->setCellValue('C1', 'Kategori');
        
        $row = 2;
        foreach($mapel as $m) {
            $sheet->setCellValue('A' . $row, $m['kode_mapel']);
            $sheet->setCellValue('B' . $row, $m['nama_mapel']);
            $sheet->setCellValue('C' . $row, $m['kategori']);
            $row++;
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_Mapel_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function importMapel()
    {
        requireAccess('akademik_mapel');
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            $file = $_FILES['file_excel']['tmp_name'];
            $extension = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);
            
            if($extension == 'xlsx' || $extension == 'xls') {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                
                $dataInsert = [];
                // Mulai dari baris ke-2 untuk melewati header
                for($i = 2; $i <= count($sheetData); $i++) {
                    $kode = $sheetData[$i]['A'];
                    $nama = $sheetData[$i]['B'];
                    $kategori = $sheetData[$i]['C'];
                    
                    if(!empty($kode) && !empty($nama) && !empty($kategori)) {
                        $dataInsert[] = [
                            'kode_mapel' => $kode,
                            'nama_mapel' => $nama,
                            'kategori'   => $kategori
                        ];
                    }
                }
                
                if(count($dataInsert) > 0) {
                    $inserted = $this->model('AkademikModel')->importMapelMassal($dataInsert);
                    $_SESSION['flash'] = ['pesan' => $inserted . ' data berhasil', 'aksi' => 'diimport', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diimport, data kosong/tidak valid', 'tipe' => 'danger'];
                }
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diimport, format file harus .xlsx/.xls', 'tipe' => 'danger'];
            }
            
            header('Location: ' . BASEURL . '/akademik/mapel');
            exit;
        }
    }
        // ==========================================
    // ROMBONGAN BELAJAR (ROMBEL)
    // ==========================================
    public function rombel()
    {
        requireAccess('akademik_rombel');
        $data['judul'] = 'Manajemen Rombongan Belajar';
        $data['rombel'] = $this->model('RombelModel')->getAllRombel();
        $data['tahun_akademik'] = $this->model('AkademikModel')->getAllTahun();
        $data['kelas'] = $this->model('AkademikModel')->getAllKelas();
        $data['guru'] = $this->model('RombelModel')->getAllGuru();

        $this->view('templates/admin_header', $data);
        $this->view('akademik/rombel', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahRombel()
    {
        requireAccess('akademik_rombel');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('RombelModel')->tambahRombel($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'ditambahkan', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/akademik/rombel');
            exit;
        }
    }

    public function ubahRombel()
    {
        requireAccess('akademik_rombel');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('RombelModel')->ubahRombel($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'diubah', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal atau tidak ada perubahan', 'aksi' => 'diubah', 'tipe' => 'warning'];
            }
            header('Location: ' . BASEURL . '/akademik/rombel');
            exit;
        }
    }

    public function hapusRombel($id)
    {
        requireAccess('akademik_rombel');
        if($this->model('RombelModel')->hapusRombel($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/akademik/rombel');
        exit;
    }

    // ==========================================
    // KELOLA ANGGOTA ROMBEL
    // ==========================================
    public function anggotaRombel($rombel_id)
    {
        requireAccess('akademik_rombel');
        $data['judul'] = 'Kelola Anggota Rombel';
        $data['rombel'] = $this->model('RombelModel')->getRombelById($rombel_id);
        
        if(!$data['rombel']) {
            header('Location: ' . BASEURL . '/akademik/rombel');
            exit;
        }

        $data['anggota'] = $this->model('RombelModel')->getAnggotaRombel($rombel_id);
        $data['siswa_tersedia'] = $this->model('RombelModel')->getSiswaBelumAdaRombel($data['rombel']['tahun_akademik_id']);

        $this->view('templates/admin_header', $data);
        $this->view('akademik/anggota_rombel', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahAnggotaMasal()
    {
        requireAccess('akademik_rombel');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rombel_id = $_POST['rombel_id'];
            if(isset($_POST['siswa_ids']) && is_array($_POST['siswa_ids'])) {
                $inserted = $this->model('RombelModel')->tambahAnggotaMasal($rombel_id, $_POST['siswa_ids']);
                $_SESSION['flash'] = ['pesan' => $inserted . ' siswa berhasil', 'aksi' => 'ditambahkan ke rombel', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Tidak ada siswa yang', 'aksi' => 'dipilih', 'tipe' => 'warning'];
            }
            header('Location: ' . BASEURL . '/akademik/anggotaRombel/' . $rombel_id);
            exit;
        }
    }

    public function hapusAnggota($rombel_id, $anggota_id)
    {
        requireAccess('akademik_rombel');
        if($this->model('RombelModel')->hapusAnggota($anggota_id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus dari rombel', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/akademik/anggotaRombel/' . $rombel_id);
        exit;
    }

    // ==========================================
    // NAIK KELAS / PROMOSI KELAS
    // ==========================================
    public function naikKelas()
    {
        requireAccess('akademik_naik_kelas');
        $data['judul'] = 'Fitur Naik Kelas / Promosi';
        $data['tahun_akademik'] = $this->model('AkademikModel')->getAllTahun();
        
        $this->view('templates/admin_header', $data);
        $this->view('akademik/naik_kelas', $data);
        $this->view('templates/admin_footer');
    }

    public function prosesNaikKelas()
    {
        requireAccess('akademik_naik_kelas');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dest_rombel_id = $_POST['dest_rombel_id'];
            if(isset($_POST['siswa_ids']) && is_array($_POST['siswa_ids']) && !empty($dest_rombel_id)) {
                if ($dest_rombel_id === 'ALUMNI') {
                    $inserted = $this->model('RombelModel')->luluskanSiswa($_POST['siswa_ids']);
                    $_SESSION['flash'] = ['pesan' => $inserted . ' siswa berhasil', 'aksi' => 'diluluskan menjadi alumni', 'tipe' => 'success'];
                } else {
                    $inserted = $this->model('RombelModel')->prosesNaikKelas($dest_rombel_id, $_POST['siswa_ids']);
                    $_SESSION['flash'] = ['pesan' => $inserted . ' siswa berhasil', 'aksi' => 'dinaikkan kelas (disalin) ke rombel baru', 'tipe' => 'success'];
                }
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'mohon pilih rombel tujuan dan minimal 1 siswa', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/akademik/naikKelas');
            exit;
        }
    }

    // -- API Endpoints for Ajax --
    public function apiGetRombel($ta_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->model('RombelModel')->getRombelByTahunAkademik($ta_id));
        exit;
    }

    public function apiGetAnggota($rombel_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->model('RombelModel')->getAnggotaRombel($rombel_id));
        exit;
    }

    // ==========================================
    // KELAS
    // ==========================================
    public function kelas()
    {
        requireAccess('akademik_kelas');
        $data['judul'] = 'Manajemen Master Kelas';
        $data['kelas'] = $this->model('AkademikModel')->getAllKelas();

        $this->view('templates/admin_header', $data);
        $this->view('akademik/kelas', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahKelas()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('AkademikModel')->tambahKelas($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'ditambahkan', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/akademik/kelas');
            exit;
        }
    }

    public function hapusKelas($id)
    {
        requireAccess('akademik_kelas');
        if($this->model('AkademikModel')->hapusKelas($id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/akademik/kelas');
        exit;
    }
}
