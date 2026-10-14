<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor - <?= $siswa['nama_lengkap'] ?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0;
            font-size: 8pt; 
            line-height: 1.15;
            color: #111;
        }
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 6px; 
        }
        .header-table td { 
            padding: 1px 2px; 
            border: none; 
            vertical-align: top; 
        }
        .header-left { width: 55%; }
        .header-right { width: 45%; }
        
        .info-row { 
            margin-bottom: 1px; 
        }
        .label { 
            font-weight: bold; 
            width: 90px; 
            display: inline-block;
        }
        .colon { margin: 0 4px; }
        
        .nilai-table, .kehadiran-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 6px; 
            font-size: 7.5pt;
        }
        .nilai-table th, .nilai-table td, .kehadiran-table th, .kehadiran-table td { 
            border: 1px solid #000; 
            padding: 1.5px 3px; 
        }
        .nilai-table th { 
            background-color: #f0f0f0; 
            text-align: center; 
            font-weight: bold;
            padding: 2px 3px;
        }
        .kelompok-header { 
            background-color: #e8e8e8; 
            font-weight: bold; 
        }
        .kelompok-header td {
            padding: 1.5px 3px;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        .section-title { 
            font-size: 7.5pt; 
            margin: 0 0 2px 0; 
            font-weight: bold; 
        }

        .catatan-box { 
            border: 1px solid #000; 
            padding: 3px 5px; 
            min-height: 52px; 
            height: 52px;
            font-size: 7.5pt;
            margin: 0;
            line-height: 1.15;
            box-sizing: border-box;
        }
        
        /* TANDA TANGAN (COMPACT & TIDAK PECAH HALAMAN) */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            page-break-inside: avoid;
        }
        .signature-table td {
            vertical-align: top;
            padding: 0;
            border: none;
        }
        .signature-cell {
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            width: 150px;
            height: 1px;
            border-bottom: 1px solid #000;
            margin: 28px auto 2px auto;
        }
        .signature-name {
            margin-top: 2px;
            font-weight: bold;
            font-size: 7.5pt;
        }
        .signature-role {
            font-size: 7.5pt;
            font-weight: 500;
        }
        .know-text {
            margin-bottom: 1px;
            font-weight: bold;
            font-size: 7.5pt;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="info-row">
                    <span class="label">Nama</span><span class="colon">:</span>
                    <span class="value"><?= htmlspecialchars($siswa['nama_lengkap']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">NIS/NISN</span><span class="colon">:</span>
                    <span class="value"><?= htmlspecialchars($siswa['nis'] ?? '-') ?> / <?= htmlspecialchars($siswa['nisn'] ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Sekolah</span><span class="colon">:</span>
                    <span class="value"><?= htmlspecialchars(!empty($pengaturan['nama_aplikasi']) && strtoupper($pengaturan['nama_aplikasi']) !== 'SIMS' && strtoupper($pengaturan['nama_aplikasi']) !== 'BISMILLAH' ? $pengaturan['nama_aplikasi'] : 'SMA NAHDLATUL WATHAN JAKARTA') ?></span>
                </div>
            </td>
            <td class="header-right">
                <div class="info-row">
                    <span class="label">Kelas</span><span class="colon">:</span>
                    <span class="value"><?= htmlspecialchars($siswa['nama_kelas']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Semester</span><span class="colon">:</span>
                    <span class="value"><?= htmlspecialchars($semester == '1' || strtolower($semester) == 'ganjil' ? 'Ganjil' : 'Genap') ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Tahun Ajaran</span><span class="colon">:</span>
                    <span class="value"><?= htmlspecialchars($tahun_name) ?></span>
                </div>
            </td>
        </tr>
    </table>

    <table class="nilai-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="60%">Mata Pelajaran</th>
                <th width="10%">KKM</th>
                <th width="13%">Nilai</th>
                <th width="12%">Sikap</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($nilai_data)): ?>
                <tr><td colspan="5" class="text-center">Belum ada nilai</td></tr>
            <?php else: ?>
                <?php 
                $huruf = 'A';
                foreach($nilai_data as $kelompok): 
                ?>
                <tr class="kelompok-header">
                    <td colspan="5" class="text-left"><?= $huruf++ ?>. <?= htmlspecialchars($kelompok['nama_kelompok']) ?></td>
                </tr>
                <?php $no = 1; foreach($kelompok['mapel'] as $m): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-left"><?= htmlspecialchars($m['nama_mapel']) ?></td>
                    <td class="text-center"><?= $m['kkm'] ?></td>
                    <td class="text-center"><?= $m['nilai'] ?></td>
                    <td class="text-center"><?= htmlspecialchars($m['sikap'] ?? '-') ?></td>
                </tr>
                <?php endforeach; endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if(!empty($ekskul)): ?>
    <div class="section-title">Ekstrakurikuler:</div>
    <table class="nilai-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Kegiatan Ekstrakurikuler</th>
                <th width="15%">Nilai</th>
                <th width="45%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($ekskul as $e): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($e['nama_ekskul']) ?></td>
                <td class="text-center"><b><?= $e['nilai'] ?></b></td>
                <td><?= htmlspecialchars($e['keterangan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <!-- KEHADIRAN & CATATAN WALI KELAS (SIDE-BY-SIDE UNTUK HEMAT RUANG VERTIKAL) -->
    <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 6px;">
        <tr>
            <td style="width: 44%; vertical-align: top; border: none; padding: 0 6px 0 0;">
                <div class="section-title">Kehadiran:</div>
                <table class="kehadiran-table" style="margin-bottom: 0;">
                    <thead>
                        <tr><th width="65%">Jenis</th><th width="35%">Jumlah</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Sakit</td><td class="text-center"><?= $absensi['sakit'] ?? 0 ?></td></tr>
                        <tr><td>Izin</td><td class="text-center"><?= $absensi['izin'] ?? 0 ?></td></tr>
                        <tr><td>Alfa</td><td class="text-center"><?= $absensi['alfa'] ?? 0 ?></td></tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 56%; vertical-align: top; border: none; padding: 0 0 0 6px;">
                <div class="section-title">Catatan Wali Kelas:</div>
                <div class="catatan-box"><?= nl2br(htmlspecialchars($catatan['catatan'] ?? 'Tingkatkan terus prestasi dan semangat belajarnya.')) ?></div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td width="50%" style="text-align: center; vertical-align: top; padding: 0;">
                    <div class="signature-cell">
                        <div class="signature-role">Orang Tua/Wali</div>
                        <div class="signature-line"></div>
                        <div class="signature-name">(___________________)</div>
                    </div>
                </td>
                
                <td width="50%" style="text-align: center; vertical-align: top; padding: 0;">
                    <div class="signature-cell">
                        <div class="signature-role">Wali Kelas</div>
                        <div class="signature-line"></div>
                        <div class="signature-name"><u><?= htmlspecialchars($wali_name) ?></u></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; vertical-align: top; padding: 6px 0 0 0;">
                    <div class="signature-cell">
                        <div class="know-text">Mengetahui,</div>
                        <div class="signature-role">Kepala Sekolah</div>
                        <div class="signature-line"></div>
                        <div class="signature-name"><u><?= htmlspecialchars($kepsek_name) ?></u></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
