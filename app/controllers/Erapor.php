<?php

class Erapor extends Controller {
    
    private $tahunAktif;
    
    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
        
        $this->tahunAktif = $this->model('EraporModel')->getTahunAkademikAktif();
    }

    public function index()
    {
        $data['judul'] = 'E-Rapor';
        $this->view('templates/admin_header', $data);
        $this->view('erapor/index', $data);
        $this->view('templates/admin_footer');
    }

    // ============================================
    // GURU: INPUT NILAI RAPOR
    // ============================================
    public function inputNilai()
    {
        if(!hasMenuAccess('erapor_input_nilai') && $_SESSION['user']['role'] !== 'guru' && $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash'] = ['pesan' => 'Anda tidak memiliki', 'aksi' => 'akses', 'tipe' => 'error'];
            header('Location: ' . BASEURL . '/erapor');
            exit;
        }

        $data['judul'] = 'Input Nilai Rapor';
        $user_id = $_SESSION['user']['id'];
        $guru = $this->model('EraporModel')->getGuruDetail($user_id);
        $guru_id = $guru['id'] ?? 0;
        
        // Asumsi admin
        if($_SESSION['user']['role'] === 'admin') {
            $guru_id = isset($_GET['guru_id']) ? $_GET['guru_id'] : 1; 
        }

        $tahun_id = $this->tahunAktif['id'];
        $data['mapel_list'] = $this->model('EraporModel')->getMapelByGuru($guru_id, $tahun_id);
        
        $data['selected_rombel'] = $_GET['rombel_id'] ?? null;
        $data['selected_mapel'] = $_GET['mapel_id'] ?? null;
        
        if($data['selected_rombel'] && $data['selected_mapel']) {
            $data['siswa_list'] = $this->model('EraporModel')->getSiswaByRombel($data['selected_rombel']);
            
            // Fetch nilai lama
            $nilai_lama = [];
            foreach($data['siswa_list'] as $s) {
                $n = $this->model('EraporModel')->getNilaiRaporBySiswa($s['id'], $data['selected_mapel'], $tahun_id);
                $nilai_lama[$s['id']] = $n ?: ['nilai' => '', 'sikap' => ''];
            }
            $data['nilai_lama'] = $nilai_lama;
        }

        $this->view('templates/admin_header', $data);
        $this->view('erapor/input_nilai', $data);
        $this->view('templates/admin_footer');
    }

    public function saveNilaiAjax()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SESSION['user']['role'] === 'guru' || $_SESSION['user']['role'] === 'admin')) {
            $siswa_id = $_POST['siswa_id'];
            $mapel_id = $_POST['mapel_id'];
            $nilai = $_POST['nilai'];
            $sikap = $_POST['sikap'];
            $tahun_id = $this->tahunAktif['id'];
            
            $user_id = $_SESSION['user']['id'];
            $guru = $this->model('EraporModel')->getGuruDetail($user_id);
            $guru_id = $guru['id'] ?? 1;

            if($this->model('EraporModel')->saveNilaiRapor($siswa_id, $mapel_id, $guru_id, $tahun_id, $nilai, $sikap) >= 0) {
                echo json_encode(['status' => 'success', 'message' => 'Nilai tersimpan']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal simpan']);
            }
        }
    }

    // ============================================
    // GURU/OPERATOR: EKSKUL
    // ============================================
    public function ekskul()
    {
        if(!hasMenuAccess('erapor_ekskul') && $_SESSION['user']['role'] !== 'guru' && $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASEURL . '/erapor');
            exit;
        }
        
        $data['judul'] = 'Input Nilai Ekskul';
        $tahun_id = $this->tahunAktif['id'];
        
        // Untuk pilih rombel dan siswa
        $data['rombel_list'] = $this->model('EraporModel')->getAllRombel();
        $data['selected_rombel'] = $_GET['rombel_id'] ?? null;
        
        if($data['selected_rombel']) {
            $data['siswa_list'] = $this->model('EraporModel')->getSiswaByRombel($data['selected_rombel']);
        }

        $this->view('templates/admin_header', $data);
        $this->view('erapor/input_ekskul', $data);
        $this->view('templates/admin_footer');
    }
    
    public function getEkskulSiswaAjax()
    {
        $siswa_id = $_GET['siswa_id'];
        $tahun_id = $this->tahunAktif['id'];
        $ekskul = $this->model('EraporModel')->getEkskulSiswa($siswa_id, $tahun_id);
        echo json_encode($ekskul);
    }
    
    public function saveEkskul()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $siswa_id = $_POST['siswa_id'];
            $nama_ekskul = $_POST['nama_ekskul'];
            $nilai = $_POST['nilai'];
            $keterangan = $_POST['keterangan'];
            $tahun_id = $this->tahunAktif['id'];
            
            $this->model('EraporModel')->saveEkskulSiswa($siswa_id, $tahun_id, $nama_ekskul, $nilai, $keterangan);
            echo json_encode(['status' => 'success']);
        }
    }
    
    public function hapusEkskul()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $this->model('EraporModel')->deleteEkskul($id);
            echo json_encode(['status' => 'success']);
        }
    }

    // ============================================
    // WALIKELAS: ABSENSI & CATATAN
    // ============================================
    private function checkWalikelas()
    {
        if($_SESSION['user']['role'] === 'admin') {
            return $_GET['rombel_id'] ?? null; // admin bisa milih
        }
        
        $user_id = $_SESSION['user']['id'];
        $guru = $this->model('EraporModel')->getGuruDetail($user_id);
        $rombel = $this->model('EraporModel')->getRombelWaliKelas($guru['id'] ?? 0, $this->tahunAktif['id']);
        
        if(!$rombel) {
            $_SESSION['flash'] = ['pesan' => 'Anda bukan', 'aksi' => 'Wali Kelas di tahun akademik ini', 'tipe' => 'warning'];
            header('Location: ' . BASEURL . '/erapor');
            exit;
        }
        return $rombel['id'];
    }

    public function absensi()
    {
        $rombel_id = $this->checkWalikelas();
        if(!$rombel_id && $_SESSION['user']['role'] === 'admin') {
            // Tampilkan pilihan kelas untuk admin
            $data['rombel_list'] = $this->model('EraporModel')->getAllRombel();
        }

        $data['judul'] = 'Absensi Semester Rapor';
        $tahun_id = $this->tahunAktif['id'];
        
        if($rombel_id) {
            $data['siswa_list'] = $this->model('EraporModel')->getSiswaByRombel($rombel_id);
            $data['absensi'] = [];
            foreach($data['siswa_list'] as $s) {
                $abs = $this->model('EraporModel')->getAbsensiRapor($s['id'], $tahun_id);
                $data['absensi'][$s['id']] = $abs ?: ['sakit' => 0, 'izin' => 0, 'alfa' => 0];
            }
        }

        $this->view('templates/admin_header', $data);
        $this->view('erapor/absensi', $data);
        $this->view('templates/admin_footer');
    }

    public function saveAbsensi()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tahun_id = $this->tahunAktif['id'];
            $sakit = $_POST['sakit'] ?? [];
            $izin = $_POST['izin'] ?? [];
            $alfa = $_POST['alfa'] ?? [];
            
            foreach($sakit as $siswa_id => $val_sakit) {
                $val_izin = $izin[$siswa_id] ?? 0;
                $val_alfa = $alfa[$siswa_id] ?? 0;
                $this->model('EraporModel')->saveAbsensiRapor($siswa_id, $tahun_id, $val_sakit, $val_izin, $val_alfa);
            }
            
            echo json_encode(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Absensi berhasil disimpan']);
        }
    }

    public function catatan()
    {
        $rombel_id = $this->checkWalikelas();
        $data['judul'] = 'Catatan Wali Kelas';
        $tahun_id = $this->tahunAktif['id'];
        
        if($rombel_id) {
            $data['siswa_list'] = $this->model('EraporModel')->getSiswaByRombel($rombel_id);
            $data['catatan'] = [];
            foreach($data['siswa_list'] as $s) {
                $cat = $this->model('EraporModel')->getCatatanWali($s['id'], $tahun_id);
                $data['catatan'][$s['id']] = $cat['catatan'] ?? '';
            }
        }

        $this->view('templates/admin_header', $data);
        $this->view('erapor/catatan_wali', $data);
        $this->view('templates/admin_footer');
    }

    public function saveCatatanAjax()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $siswa_id = $_POST['id_siswa'];
            $catatan = $_POST['catatan'];
            $tahun_id = $this->tahunAktif['id'];
            
            $this->model('EraporModel')->saveCatatanWali($siswa_id, $tahun_id, $catatan);
            echo json_encode(['status' => 'success', 'message' => 'Catatan disimpan']);
        }
    }
    
    public function rekapNilai()
    {
        $rombel_id = $this->checkWalikelas();
        if(!$rombel_id && $_SESSION['user']['role'] === 'admin') {
            $data['rombel_list'] = $this->model('EraporModel')->getAllRombel();
            $rombel_id = $_GET['rombel_id'] ?? null;
        }

        $data['judul'] = 'Rekap Nilai Kelas';
        $tahun_id = $this->tahunAktif['id'];
        
        $data['siswa_list'] = [];
        $data['rekap'] = [];
        
        if($rombel_id) {
            $data['siswa_list'] = $this->model('EraporModel')->getSiswaByRombel($rombel_id);
            foreach($data['siswa_list'] as $s) {
                // We reuse getNilaiKelompokBySiswa to get all nilai for the student
                $data['rekap'][$s['id']] = $this->model('EraporModel')->getNilaiKelompokBySiswa($s['id'], $tahun_id);
            }
        }

        $this->view('templates/admin_header', $data);
        $this->view('erapor/rekap_nilai', $data);
        $this->view('templates/admin_footer');
    }

    // ============================================
    // ADMIN / WALIKELAS: CETAK RAPOR
    // ============================================
    public function cetak()
    {
        $data['judul'] = 'Cetak Rapor';
        $data['rombel_list'] = $this->model('EraporModel')->getAllRombel();
        $data['tahun_list'] = $this->model('EraporModel')->getAllTahunAkademik();
        
        $data['selected_rombel'] = $_GET['rombel'] ?? null;
        $data['selected_tahun'] = $_GET['tahun'] ?? $this->tahunAktif['id'];
        $data['selected_siswa'] = $_GET['siswa'] ?? null;
        $data['type'] = $_GET['type'] ?? 'preview';

        $siswa_data = [];
        if ($data['selected_rombel']) {
            if ($data['selected_siswa']) {
                $siswa_data[] = $this->model('EraporModel')->getSiswaDetail($data['selected_siswa']);
            } else {
                $siswa_data = $this->model('EraporModel')->getSiswaByRombel($data['selected_rombel']);
                // get details for each
                foreach($siswa_data as &$s) {
                    $detail = $this->model('EraporModel')->getSiswaDetail($s['id']);
                    $s['nama_kelas'] = $detail['nama_kelas'];
                    $s['wali_kelas_id'] = $detail['wali_kelas_id'];
                }
            }
        }
        $data['siswa_data'] = $siswa_data;
        $data['pengaturan'] = $this->model('EraporModel')->getSettingSekolah() ?? $GLOBALS['pengaturan'];
        
        // Jika export PDF
        if($data['type'] === 'pdf' && !empty($siswa_data)) {
            $this->generatePdf($data);
            return; // stop execution
        }

        $this->view('templates/admin_header', $data);
        $this->view('erapor/cetak_rapor', $data);
        $this->view('templates/admin_footer');
    }
    
    private function generatePdf($data)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        try {
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 12,
                'margin_bottom' => 12,
                'margin_left' => 10,
                'margin_right' => 10,
                'default_font_size' => 10
            ]);
            
            $html = "";
            $tahun_id = $data['selected_tahun'];
            $pengaturan = $data['pengaturan'];
            $tahun_obj = array_filter($data['tahun_list'], fn($t) => $t['id'] == $tahun_id);
            $tahun_obj = reset($tahun_obj);
            $tahun_name = $tahun_obj ? $tahun_obj['nama_tahun'] : 'Tahun Ajaran';
            $semester = $tahun_obj ? $tahun_obj['semester'] : 'Ganjil';
            
            foreach($data['siswa_data'] as $index => $siswa) {
                // Get all data
                $nilai_data = $this->model('EraporModel')->getNilaiKelompokBySiswa($siswa['id'], $tahun_id);
                $absensi = $this->model('EraporModel')->getAbsensiRapor($siswa['id'], $tahun_id) ?: ['sakit'=>0,'izin'=>0,'alfa'=>0];
                $catatan = $this->model('EraporModel')->getCatatanWali($siswa['id'], $tahun_id);
                $ekskul = $this->model('EraporModel')->getEkskulSiswa($siswa['id'], $tahun_id);
                
                // average
                $total = 0; $count = 0;
                foreach($nilai_data as $kelompok) {
                    foreach($kelompok['mapel'] as $nm) {
                        $total += $nm['nilai']; $count++;
                    }
                }
                $rata_rata = $count > 0 ? round($total/$count, 2) : 0;
                
                // Get walikelas name
                $wali_name = "Wali Kelas";
                if($siswa['wali_kelas_id']) {
                    $wali_guru = $this->model('EraporModel')->getGuruDetail($siswa['wali_kelas_id']);
                    $wali_name = $wali_guru['nama_lengkap'] ?? $wali_name;
                }

                // Render view to string (buffer)
                ob_start();
                extract([
                    'siswa' => $siswa,
                    'nilai_data' => $nilai_data,
                    'absensi' => $absensi,
                    'catatan' => $catatan,
                    'ekskul' => $ekskul,
                    'rata_rata' => $rata_rata,
                    'tahun_name' => $tahun_name,
                    'semester' => $semester,
                    'pengaturan' => $pengaturan,
                    'wali_name' => $wali_name
                ]);
                include '../app/views/erapor/pdf_template.php';
                $content = ob_get_clean();
                
                $mpdf->WriteHTML($content);
                
                if($index < count($data['siswa_data']) - 1) {
                    $mpdf->AddPage();
                }
            }
            
            $filename = count($data['siswa_data']) > 1 
                ? "Rapor_Kelas_{$data['siswa_data'][0]['nama_kelas']}_{$tahun_name}.pdf" 
                : "Rapor_{$data['siswa_data'][0]['nama_lengkap']}_{$tahun_name}.pdf";
                
            $mpdf->Output($filename, 'D');
            
        } catch (\Mpdf\MpdfException $e) {
            echo $e->getMessage();
        }
    }
}
