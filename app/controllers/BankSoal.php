<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

require_once '../app/helpers/DocxParser.php';

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
            if($this->model('BankSoalModel')->tambahDataSoal($_POST) > 0) {
                Flasher::setFlash('Soal berhasil', 'ditambahkan', 'success');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            } else {
                Flasher::setFlash('Soal gagal', 'ditambahkan', 'danger');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            }
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
            $id_guru = $_SESSION['user_id'] ?? 1; // Fallback jika tidak ada sesi login detail

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
        $sheet->setCellValue('G1', 'Kunci Jawaban (A/B/C/D/E)');
        $sheet->setCellValue('H1', 'Tipe (PG/ESSAY)');
        $sheet->setCellValue('I1', 'Tingkat (Mudah/Sedang/Sulit)');

        // Contoh Data
        $sheet->setCellValue('A2', 'Siapa penemu lampu pijar?');
        $sheet->setCellValue('B2', 'Thomas Edison');
        $sheet->setCellValue('C2', 'Albert Einstein');
        $sheet->setCellValue('D2', 'Isaac Newton');
        $sheet->setCellValue('E2', 'Nikola Tesla');
        $sheet->setCellValue('F2', '');
        $sheet->setCellValue('G2', 'A');
        $sheet->setCellValue('H2', 'PG');
        $sheet->setCellValue('I2', 'Mudah');
        
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(10);
        
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_Soal.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function templateWord()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText("[SOAL]", ['bold' => true]);
        $section->addText("Tulis pertanyaan Anda di sini. Anda dapat menyisipkan gambar (Insert -> Pictures) langsung di bawah tulisan [SOAL] ini.");
        $section->addTextBreak();
        $section->addText("[OPSI_A]", ['bold' => true]);
        $section->addText("Pilihan Jawaban A (Bisa disisipkan gambar)");
        $section->addText("[OPSI_B]", ['bold' => true]);
        $section->addText("Pilihan Jawaban B");
        $section->addText("[OPSI_C]", ['bold' => true]);
        $section->addText("Pilihan Jawaban C");
        $section->addText("[OPSI_D]", ['bold' => true]);
        $section->addText("Pilihan Jawaban D");
        $section->addText("[OPSI_E]", ['bold' => true]);
        $section->addText("Pilihan Jawaban E (Kosongkan/hapus opsi ini jika SMP)");
        $section->addTextBreak();
        $section->addText("[KUNCI] A", ['bold' => true]);
        $section->addText("[TIPE] PG", ['bold' => true]);
        $section->addText("[KESULITAN] Sedang", ['bold' => true]);
        
        $section->addPageBreak();
        
        $section->addText("[SOAL]", ['bold' => true]);
        $section->addText("Jelaskan pengertian dari fotosintesis secara singkat!");
        $section->addTextBreak();
        $section->addText("[TIPE] ESSAY", ['bold' => true]);
        $section->addText("[KESULITAN] Sulit", ['bold' => true]);

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="Template_Import_Soal.docx"');
        header('Cache-Control: max-age=0');

        $writer = WordIOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
}
