<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

require_once __DIR__ . '/../helpers/DocxParser.php';

class BankSoal extends Controller {

    public function __construct()
    {
        requireAccess('cbt_bank_soal');
    }

    public function index()
    {
        requireAccess('cbt_bank_soal');
        $data['judul'] = 'Bank Soal CBT';
        $data['soal'] = $this->model('BankSoalModel')->getAllSoal();
        $data['mapel'] = $this->model('BankSoalModel')->getAllMapel();
        
        $this->view('templates/admin_header', $data);
        $this->view('bank_soal/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        $data['judul'] = 'Tambah Soal Baru';
        $data['mapel'] = $this->model('BankSoalModel')->getAllMapel();
        
        $this->view('templates/admin_header', $data);
        $this->view('bank_soal/form', $data);
        $this->view('templates/admin_footer');
    }

    public function simpan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_guru = $_SESSION['user']['id'] ?? 1;
            $id_mapel = $_POST['id_mapel'] ?? 0;
            $soal_array = $_POST['soal'] ?? [];
            
            if($this->model('BankSoalModel')->tambahBanyakSoal($id_mapel, $id_guru, $soal_array) > 0) {
                Flasher::setFlash(count($soal_array) . ' Soal berhasil', 'ditambahkan', 'success');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            } else {
                Flasher::setFlash('Gagal menambah soal', 'Pastikan minimal ada 1 soal yang valid', 'danger');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            }
        }
    }

    public function uploadImageApi()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $dir = __DIR__ . '/../../public/img/cbt/';
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            
            $file = $_FILES['file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = uniqid('soal_') . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $dir . $filename)) {
                    // Return raw URL as expected by Summernote default or custom handler
                    echo BASEURL . '/img/cbt/' . $filename;
                    exit;
                }
            }
            http_response_code(400);
            echo "Format gambar tidak didukung atau upload gagal.";
            exit;
        }
    }

    public function hapus($id)
    {
        if($this->model('BankSoalModel')->hapusDataSoal($id) > 0) {
            Flasher::setFlash('Soal berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/BankSoal');
            exit;
        } else {
            Flasher::setFlash('Soal gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/BankSoal');
            exit;
        }
    }

    public function edit($id)
    {
        $data['judul'] = 'Edit Soal';
        $data['mapel'] = $this->model('BankSoalModel')->getAllMapel();
        $data['soal'] = $this->model('BankSoalModel')->getSoalById($id);
        
        if(!$data['soal']) {
            Flasher::setFlash('Soal', 'tidak ditemukan', 'danger');
            header('Location: ' . BASEURL . '/BankSoal');
            exit;
        }
        
        $this->view('templates/admin_header', $data);
        $this->view('bank_soal/edit', $data);
        $this->view('templates/admin_footer');
    }

    public function update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('BankSoalModel')->updateDataSoal($_POST) > 0) {
                Flasher::setFlash('Soal berhasil', 'diperbarui', 'success');
            } else {
                Flasher::setFlash('Soal gagal', 'diperbarui / tidak ada perubahan', 'warning');
            }
            header('Location: ' . BASEURL . '/BankSoal');
            exit;
        }
    }

    public function hapusMassal()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_ids'])) {
            $ids = json_decode($_POST['selected_ids'], true);
            if(is_array($ids) && count($ids) > 0) {
                $deleted = $this->model('BankSoalModel')->hapusMassalSoal($ids);
                Flasher::setFlash("$deleted Soal berhasil", 'dihapus secara massal', 'success');
            }
        }
        header('Location: ' . BASEURL . '/BankSoal');
        exit;
    }

    public function editMassal()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_ids'])) {
            $ids = json_decode($_POST['selected_ids'], true);
            if(is_array($ids) && count($ids) > 0) {
                $data['judul'] = 'Edit Massal Soal';
                $data['mapel'] = $this->model('BankSoalModel')->getAllMapel();
                $data['soal'] = $this->model('BankSoalModel')->getSoalByIds($ids);
                
                $this->view('templates/admin_header', $data);
                $this->view('bank_soal/form_edit_massal', $data);
                $this->view('templates/admin_footer');
                return;
            }
        }
        header('Location: ' . BASEURL . '/BankSoal');
        exit;
    }

    public function updateMassal()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['soal'])) {
            $model = $this->model('BankSoalModel');
            $updated = 0;
            foreach($_POST['soal'] as $item) {
                if(isset($item['id_soal'])) {
                    // Inject mapel id to item to match updateDataSoal expectation
                    $item['id_mapel'] = $_POST['id_mapel'];
                    if($model->updateDataSoal($item) > 0) {
                        $updated++;
                    }
                }
            }
            Flasher::setFlash("$updated Soal", 'berhasil diperbarui', 'success');
            header('Location: ' . BASEURL . '/BankSoal');
            exit;
        }
    }

    public function importPreview()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_soal'])) {
            $id_mapel = $_POST['id_mapel'];
            if (empty($id_mapel)) {
                Flasher::setFlash('Mata Pelajaran', 'belum dipilih', 'danger');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            }

            $ext = strtolower(pathinfo($_FILES['file_soal']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, ['xls', 'xlsx', 'docx'])) {
                Flasher::setFlash('Format file', 'harus .xlsx atau .docx', 'danger');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            }

            $dataSoal = [];

            try {
                if ($ext === 'docx') {
                    $parser = new DocxParser($_FILES['file_soal']['tmp_name']);
                    $dataSoal = $parser->parse();
                } else {
                    // Excel Parser
                    $spreadsheet = IOFactory::load($_FILES['file_soal']['tmp_name']);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray();

                    // Skip header
                    for ($i = 1; $i < count($sheetData); $i++) {
                        $pertanyaan = trim($sheetData[$i][0] ?? '');
                        if (empty($pertanyaan)) continue;

                        $dataSoal[] = [
                            'pertanyaan' => $pertanyaan,
                            'opsi_a' => trim($sheetData[$i][1] ?? ''),
                            'opsi_b' => trim($sheetData[$i][2] ?? ''),
                            'opsi_c' => trim($sheetData[$i][3] ?? ''),
                            'opsi_d' => trim($sheetData[$i][4] ?? ''),
                            'opsi_e' => trim($sheetData[$i][5] ?? ''),
                            'kunci_jawaban' => strtoupper(trim($sheetData[$i][6] ?? '')),
                            'tipe_soal' => strtoupper(trim($sheetData[$i][7] ?? 'PG')),
                            'tingkat_kesulitan' => ucfirst(trim($sheetData[$i][8] ?? 'Sedang')),
                        ];
                    }
                }

                if (count($dataSoal) > 0) {
                    $_SESSION['preview_import_soal'] = [
                        'id_mapel' => $id_mapel,
                        'soal' => $dataSoal
                    ];
                    
                    // Ambil detail mapel
                    $db = new Database();
                    $db->query("SELECT nama_mapel FROM mata_pelajaran WHERE id = :id");
                    $db->bind('id', $id_mapel);
                    $mapel = $db->single();
                    
                    $data['judul'] = 'Preview Import Soal';
                    $data['mapel'] = $mapel;
                    $data['soal'] = $dataSoal;

                    $this->view('templates/admin_header', $data);
                    $this->view('bank_soal/preview_import', $data);
                    $this->view('templates/admin_footer');
                } else {
                    Flasher::setFlash('Gagal membaca data', 'file kosong atau format tidak sesuai', 'danger');
                    header('Location: ' . BASEURL . '/BankSoal');
                }
            } catch (Exception $e) {
                Flasher::setFlash('Error', $e->getMessage(), 'danger');
                header('Location: ' . BASEURL . '/BankSoal');
            }
        }
    }

    public function simpanImport()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['preview_import_soal'])) {
            $id_mapel = $_SESSION['preview_import_soal']['id_mapel'];
            $soal = $_SESSION['preview_import_soal']['soal'];
            $id_guru = $_SESSION['user']['id'] ?? 1; // Fallback jika tidak ada sesi login detail

            $result = $this->model('BankSoalModel')->importSoalMassal($id_mapel, $id_guru, $soal);
            
            Flasher::setFlash($result . ' Soal', 'berhasil diimport', 'success');
            unset($_SESSION['preview_import_soal']);
        }
        header('Location: ' . BASEURL . '/BankSoal');
        exit;
    }

    public function templateExcel()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'Pertanyaan');
        $sheet->setCellValue('B1', 'Opsi A');
        $sheet->setCellValue('C1', 'Opsi B');
        $sheet->setCellValue('D1', 'Opsi C');
        $sheet->setCellValue('E1', 'Opsi D');
        $sheet->setCellValue('F1', 'Opsi E (Kosongkan jika SMP/MTS)');
        $sheet->setCellValue('G1', 'Kunci (Contoh: A, atau A,B,C untuk kompleks)');
        $sheet->setCellValue('H1', 'Tipe (PG / PG_KOMPLEKS / ESSAY)');
        $sheet->setCellValue('I1', 'Tingkat (Mudah/Sedang/Sulit)');

        // Contoh Data 1 (PG Biasa)
        $sheet->setCellValue('A2', 'Siapa penemu bola lampu pijar pertama yang sukses secara komersial?');
        $sheet->setCellValue('B2', 'Thomas Alva Edison');
        $sheet->setCellValue('C2', 'Albert Einstein');
        $sheet->setCellValue('D2', 'Isaac Newton');
        $sheet->setCellValue('E2', 'Nikola Tesla');
        $sheet->setCellValue('F2', '');
        $sheet->setCellValue('G2', 'A');
        $sheet->setCellValue('H2', 'PG');
        $sheet->setCellValue('I2', 'Mudah');
        
        // Contoh Data 2 (PG Kompleks)
        $sheet->setCellValue('A3', 'Manakah di bawah ini yang merupakan bilangan prima? (Pilih lebih dari satu)');
        $sheet->setCellValue('B3', '2');
        $sheet->setCellValue('C3', '4');
        $sheet->setCellValue('D3', '5');
        $sheet->setCellValue('E3', '9');
        $sheet->setCellValue('F3', '11');
        $sheet->setCellValue('G3', 'A,C,E');
        $sheet->setCellValue('H3', 'PG_KOMPLEKS');
        $sheet->setCellValue('I3', 'Sedang');
        
        // Contoh Data 3 (Essay)
        $sheet->setCellValue('A4', 'Jelaskan secara singkat bagaimana proses terjadinya fotosintesis pada tumbuhan hijau!');
        $sheet->setCellValue('B4', '');
        $sheet->setCellValue('C4', '');
        $sheet->setCellValue('D4', '');
        $sheet->setCellValue('E4', '');
        $sheet->setCellValue('F4', '');
        $sheet->setCellValue('G4', 'klorofil, matahari, air, oksigen');
        $sheet->setCellValue('H4', 'ESSAY');
        $sheet->setCellValue('I4', 'Sulit');
        
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(25);
        
        $sheet->getStyle('A1:I1')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F46E5'); // Indigo color

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_Soal_Maju.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function templateWord()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Panduan
        $section->addText("PANDUAN PEMBUATAN SOAL VIA WORD (SANGAT MUDAH!)", ['bold' => true, 'size' => 14, 'color' => '4F46E5']);
        $section->addText("1. Anda bebas menggunakan format teks tebal (bold), miring (italic), atau memasukkan GAMBAR (Insert -> Picture).");
        $section->addText("2. Pastikan tag seperti [SOAL], [OPSI_A], [KUNCI], dll tetap berada di AWAL BARI baru.");
        $section->addText("3. Jika menyisipkan gambar, sisipkan TEPAT DI BAWAH baris teks Opsi atau Soal yang bersangkutan.");
        $section->addTextBreak();
        $section->addText("=============================================", ['bold' => true]);
        $section->addTextBreak();

        // Contoh PG
        $section->addText("[SOAL]", ['bold' => true]);
        $section->addText("Perhatikan gambar di bawah ini. Organel sel yang berfungsi untuk respirasi ditunjukkan oleh nomor...");
        $section->addText("(Silakan Insert Picture di sini)");
        $section->addTextBreak();
        $section->addText("[OPSI_A]", ['bold' => true]);
        $section->addText("Nomor 1");
        $section->addText("[OPSI_B]", ['bold' => true]);
        $section->addText("Nomor 2 (Bisa juga disisipkan gambar)");
        $section->addText("[OPSI_C]", ['bold' => true]);
        $section->addText("Nomor 3");
        $section->addText("[OPSI_D]", ['bold' => true]);
        $section->addText("Nomor 4");
        $section->addText("[OPSI_E]", ['bold' => true]);
        $section->addText("Nomor 5");
        $section->addTextBreak();
        $section->addText("[KUNCI] A", ['bold' => true]);
        $section->addText("[TIPE] PG", ['bold' => true]);
        $section->addText("[KESULITAN] Sedang", ['bold' => true]);
        
        $section->addPageBreak();
        
        // Contoh PG Kompleks
        $section->addText("[SOAL]", ['bold' => true]);
        $section->addText("Pilihlah semua jawaban yang benar mengenai negara-negara di Asia Tenggara!");
        $section->addTextBreak();
        $section->addText("[OPSI_A]", ['bold' => true]);
        $section->addText("Indonesia");
        $section->addText("[OPSI_B]", ['bold' => true]);
        $section->addText("Jepang");
        $section->addText("[OPSI_C]", ['bold' => true]);
        $section->addText("Malaysia");
        $section->addText("[OPSI_D]", ['bold' => true]);
        $section->addText("Thailand");
        $section->addTextBreak();
        $section->addText("[KUNCI] A,C,D", ['bold' => true]);
        $section->addText("[TIPE] PG_KOMPLEKS", ['bold' => true]);
        $section->addText("[KESULITAN] Sedang", ['bold' => true]);

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="Template_Import_Soal_Maju.docx"');
        header('Cache-Control: max-age=0');

        $writer = WordIOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
}
