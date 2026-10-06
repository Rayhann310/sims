<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            width: 210mm;
            height: 297mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cover-wrapper {
            width: 100%;
            height: 297mm;
            padding: 15mm 20mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            border: 3px double #000;
            position: relative;
        }

        /* ---- HEADER SEKOLAH ---- */
        .header {
            text-align: center;
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 8mm;
        }
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10mm;
        }
        .logo {
            width: 25mm;
            height: 25mm;
            flex-shrink: 0;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .logo-placeholder {
            width: 25mm;
            height: 25mm;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #666;
        }
        .sekolah-info { text-align: center; }
        .nama-sekolah { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .alamat-sekolah { font-size: 9pt; margin-top: 2mm; }

        /* ---- JUDUL ---- */
        .judul-box {
            text-align: center;
            margin: 5mm 0;
        }
        .judul-rapor {
            font-size: 20pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 4mm 10mm;
            display: inline-block;
        }
        .sub-judul {
            font-size: 12pt;
            margin-top: 3mm;
        }

        /* ---- LOGO TENGAH ---- */
        .logo-center {
            width: 40mm;
            height: 40mm;
            margin: 0 auto;
        }
        .logo-center img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .logo-center-placeholder {
            width: 40mm;
            height: 40mm;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #999;
            margin: 0 auto;
        }

        /* ---- DATA SISWA ---- */
        .data-siswa {
            width: 100%;
            text-align: center;
        }
        .label-nama { font-size: 10pt; margin-bottom: 2mm; }
        .nama-siswa-box {
            border: 2px solid #000;
            padding: 3mm 8mm;
            display: inline-block;
            min-width: 120mm;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3mm;
        }
        .detail-siswa { font-size: 10pt; margin-top: 2mm; line-height: 1.8; }

        /* ---- FOOTER ---- */
        .footer {
            text-align: center;
            width: 100%;
            border-top: 3px solid #000;
            padding-top: 5mm;
        }
        .tahun-pelajaran { font-size: 12pt; font-weight: bold; }
    </style>
</head>
<body>
<div class="cover-wrapper">

    <!-- Header Sekolah -->
    <div class="header">
        <div class="header-inner">
            <div class="logo">
                <?php if(!empty($pengaturan['logo_sekolah'])): ?>
                <img src="data:image/png;base64,<?= $pengaturan['logo_sekolah'] ?>" alt="Logo">
                <?php else: ?>
                <div class="logo-placeholder">LOGO</div>
                <?php endif; ?>
            </div>
            <div class="sekolah-info">
                <div class="nama-sekolah"><?= htmlspecialchars($pengaturan['nama_aplikasi'] ?? 'SMP Nahdlatul Wathan Jakarta') ?></div>
                <div class="alamat-sekolah"><?= htmlspecialchars($pengaturan['teks_footer'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <!-- Judul -->
    <div class="judul-box">
        <div class="judul-rapor">Rapor</div>
        <div class="sub-judul">Laporan Hasil Belajar Siswa</div>
        <div class="sub-judul">Semester <?= $semester == '1' || strtolower($semester) == 'ganjil' ? 'Ganjil' : 'Genap' ?></div>
    </div>

    <!-- Logo Tengah -->
    <div>
        <?php if(!empty($pengaturan['logo_sekolah'])): ?>
        <div class="logo-center">
            <img src="data:image/png;base64,<?= $pengaturan['logo_sekolah'] ?>" alt="Logo">
        </div>
        <?php else: ?>
        <div class="logo-center-placeholder">Logo Sekolah</div>
        <?php endif; ?>
    </div>

    <!-- Data Siswa -->
    <div class="data-siswa">
        <div class="label-nama">NAMA PESERTA DIDIK</div>
        <div class="nama-siswa-box"><?= strtoupper(htmlspecialchars($siswa['nama_lengkap'])) ?></div>
        <div class="detail-siswa">
            NISN: <strong><?= htmlspecialchars($siswa['nisn'] ?? '-') ?></strong>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Kelas: <strong><?= htmlspecialchars($siswa['nama_kelas'] ?? '-') ?></strong>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="tahun-pelajaran">Tahun Pelajaran <?= htmlspecialchars($tahun_name) ?></div>
    </div>

</div>
</body>
</html>
