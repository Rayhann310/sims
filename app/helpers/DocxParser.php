<?php

class DocxParser
{
    private $file;
    private $uploadDir;
    private $extractedImages = [];

    public function __construct($file)
    {
        $this->file = $file;
        $this->uploadDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/smanw/public/uploads/soal/';
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function parse()
    {
        $zip = new ZipArchive();
        if ($zip->open($this->file) !== true) {
            throw new Exception("Gagal membuka file Word.");
        }

        // Baca relasi gambar
        $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
        $relations = [];
        if ($relsXml) {
            $rels = simplexml_load_string($relsXml);
            if ($rels) {
                foreach ($rels->Relationship as $rel) {
                    $type = (string) $rel['Type'];
                    if (strpos($type, 'image') !== false) {
                        $id = (string) $rel['Id'];
                        $target = (string) $rel['Target'];
                        $relations[$id] = $target;
                    }
                }
            }
        }

        // Ekstrak dan simpan gambar
        foreach ($relations as $id => $target) {
            $imagePath = 'word/' . $target;
            $imageData = $zip->getFromName($imagePath);
            if ($imageData) {
                $ext = pathinfo($target, PATHINFO_EXTENSION);
                $newFileName = 'soal_' . time() . '_' . uniqid() . '.' . $ext;
                $fullPath = $this->uploadDir . $newFileName;
                file_put_contents($fullPath, $imageData);
                
                // Simpan URL gambar untuk direplace
                $this->extractedImages[$id] = BASEURL . '/uploads/soal/' . $newFileName;
            }
        }

        // Baca document.xml
        $docXml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (!$docXml) {
            throw new Exception("Format file Word tidak valid.");
        }

        // Convert XML ke string dengan img tags
        $docXml = preg_replace_callback('/<w:drawing>.*?<a:blip r:embed="([^"]+)".*?<\/w:drawing>/s', function($matches) {
            $rId = $matches[1];
            if (isset($this->extractedImages[$rId])) {
                return '[GAMBAR:' . $this->extractedImages[$rId] . ']';
            }
            return '';
        }, $docXml);

        // Hapus semua XML tags dan sisakan teks (dengan newline untuk paragraf)
        $docXml = str_replace(['</w:p>', '<w:br/>'], "\n", $docXml);
        $text = strip_tags($docXml);
        
        // Kembalikan tag gambar menjadi HTML
        $text = preg_replace('/\[GAMBAR:(.*?)\]/', '<br><img src="$1" style="max-width:300px; margin:10px 0;"><br>', $text);
        
        return $this->parseSoalBlocks($text);
    }

    private function parseSoalBlocks($text)
    {
        $soals = [];
        // Normalisasi spasi dan tag
        $text = str_replace("\r", "", $text);
        
        // Cari blok-blok soal
        $blocks = explode('[SOAL]', $text);
        
        foreach ($blocks as $block) {
            $block = trim($block);
            if (empty($block)) continue;
            
            $soal = [
                'pertanyaan' => '',
                'opsi_a' => '',
                'opsi_b' => '',
                'opsi_c' => '',
                'opsi_d' => '',
                'opsi_e' => '',
                'kunci_jawaban' => '',
                'tipe_soal' => 'PG',
                'tingkat_kesulitan' => 'Sedang'
            ];

            // Ekstrak Opsi
            $parts = explode('[OPSI_A]', $block);
            $soal['pertanyaan'] = trim($parts[0] ?? '');
            
            if (count($parts) > 1) {
                $block = $parts[1];
                
                $opsiParts = explode('[OPSI_B]', $block);
                $soal['opsi_a'] = trim($opsiParts[0] ?? '');
                $block = $opsiParts[1] ?? '';

                $opsiParts = explode('[OPSI_C]', $block);
                $soal['opsi_b'] = trim($opsiParts[0] ?? '');
                $block = $opsiParts[1] ?? '';

                $opsiParts = explode('[OPSI_D]', $block);
                $soal['opsi_c'] = trim($opsiParts[0] ?? '');
                $block = $opsiParts[1] ?? '';

                $opsiParts = explode('[OPSI_E]', $block);
                $soal['opsi_d'] = trim($opsiParts[0] ?? '');
                $block = $opsiParts[1] ?? '';

                $opsiParts = explode('[KUNCI]', $block);
                $soal['opsi_e'] = trim($opsiParts[0] ?? '');
                $block = $opsiParts[1] ?? '';
                
                // Parse Kunci, Tipe, Kesulitan dari sisa block
                $lines = explode("\n", trim($block));
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (str_starts_with($line, '[TIPE]')) {
                        $soal['tipe_soal'] = trim(str_replace('[TIPE]', '', $line));
                    } elseif (str_starts_with($line, '[KESULITAN]')) {
                        $soal['tingkat_kesulitan'] = trim(str_replace('[KESULITAN]', '', $line));
                    } else if (empty($soal['kunci_jawaban']) && preg_match('/^[A-E]$/i', trim(strip_tags($line)))) {
                        $soal['kunci_jawaban'] = strtoupper(trim(strip_tags($line)));
                    }
                }
            } else {
                // Jika tidak ada Opsi A, coba parse kunci dkk
                $lines = explode("\n", trim($block));
                $pertanyaanLines = [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (str_starts_with($line, '[KUNCI]')) {
                        $soal['kunci_jawaban'] = trim(str_replace('[KUNCI]', '', $line));
                    } elseif (str_starts_with($line, '[TIPE]')) {
                        $soal['tipe_soal'] = trim(str_replace('[TIPE]', '', $line));
                    } elseif (str_starts_with($line, '[KESULITAN]')) {
                        $soal['tingkat_kesulitan'] = trim(str_replace('[KESULITAN]', '', $line));
                    } else {
                        $pertanyaanLines[] = $line;
                    }
                }
                $soal['pertanyaan'] = implode("\n", $pertanyaanLines);
            }
            
            // Format teks menjadi paragraf HTML
            $soal['pertanyaan'] = $this->nl2p($soal['pertanyaan']);
            $soal['opsi_a'] = $this->nl2p($soal['opsi_a']);
            $soal['opsi_b'] = $this->nl2p($soal['opsi_b']);
            $soal['opsi_c'] = $this->nl2p($soal['opsi_c']);
            $soal['opsi_d'] = $this->nl2p($soal['opsi_d']);
            $soal['opsi_e'] = $this->nl2p($soal['opsi_e']);

            $soals[] = $soal;
        }
        return $soals;
    }

    private function nl2p($string)
    {
        $string = trim($string);
        if (empty($string)) return '';
        // Ubah \n menjadi <br> jika bukan gambar
        $string = nl2br($string);
        // Hapus <br> yang bersebelahan dengan <img>
        $string = preg_replace('/<br\s*\/?>\s*<img/', '<img', $string);
        $string = preg_replace('/<img([^>]+)>\s*<br\s*\/?>/', '<img$1>', $string);
        return $string;
    }
}
