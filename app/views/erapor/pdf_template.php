<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor - <?= $siswa['nama_lengkap'] ?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 15px; 
            font-size: 10pt; 
            line-height: 1.3;
        }
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
        }
        .header-table td { 
            padding: 4px; 
            border: none; 
            vertical-align: top; 
        }
        .header-left { width: 60%; }
        .header-right { width: 40%; }
        
        .info-row { 
            margin-bottom: 3px; 
        }
        .label { 
            font-weight: bold; 
            width: 120px; 
            display: inline-block;
        }
        .colon { margin: 0 5px; }
        
        .nilai-table, .kehadiran-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
            font-size: 9pt;
        }
        .nilai-table th, .nilai-table td, .kehadiran-table th, .kehadiran-table td { 
            border: 1px solid #000; 
            padding: 4px; 
        }
        .nilai-table th { 
            background-color: #f0f0f0; 
            text-align: center; 
            font-weight: bold;
        }
        .kelompok-header { 
            background-color: #e0e0e0; 
            font-weight: bold; 
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        .catatan-section { margin-bottom: 15px; }
        .catatan-section h4 { font-size: 10pt; margin-bottom: 5px; }
        .catatan-section p { 
            border: 1px solid #000; 
            padding: 8px; 
            min-height: 50px;
            font-size: 9pt;
            margin: 0;
        }
        h4 { font-size: 10pt; margin: 8px 0 5px 0; }
        
        /* TANDA TANGAN */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        .signature-table td {
            vertical-align: top;
            padding: 0;
        }
        .signature-cell {
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            height: 1px;
            border-bottom: 1px solid #000;
            margin: 50px auto 5px auto;
        }
        .signature-name {
            margin-top: 5px;
            font-weight: bold;
        }
        .signature-role {
            margin-top: 2px;
            font-size: 9pt;
        }
        .know-text {
            margin-bottom: 40px;
            font-weight: bold;
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
                    <span class="value"><?= htmlspecialchars($pengaturan['nama_aplikasi'] ?? 'SMA Nahdlatul Wathan') ?></span>
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
                <th width="15%">Nilai</th>
                <th width="10%">Sikap</th>
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
    <h4>Ekstrakurikuler:</h4>
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

    <h4>Kehadiran:</h4>
    <table class="kehadiran-table">
        <thead>
            <tr><th width="70%">Jenis</th><th width="30%">Jumlah</th></tr>
        </thead>
        <tbody>
            <tr><td>Sakit</td><td class="text-center"><?= $absensi['sakit'] ?? 0 ?></td></tr>
            <tr><td>Izin</td><td class="text-center"><?= $absensi['izin'] ?? 0 ?></td></tr>
            <tr><td>Alfa</td><td class="text-center"><?= $absensi['alfa'] ?? 0 ?></td></tr>
        </tbody>
    </table>

    <div class="catatan-section">
        <h4>Catatan Wali Kelas:</h4>
        <p><?= nl2br(htmlspecialchars($catatan['catatan'] ?? 'Belum ada catatan')) ?></p>
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td width="50%" style="text-align: center; vertical-align: top; padding: 0;">
                    <div class="signature-cell">
                        <div class="signature-role">Orang Tua/Wali</div><br><br><br>
                        <div class="signature-line"></div>
                        <div class="signature-name" style="margin-top: 5px;">(___________________)</div>
                    </div>
                </td>
                
                <td width="50%" style="text-align: center; vertical-align: top; padding: 0;">
                    <div class="signature-cell">
                        <div class="signature-role">Wali Kelas</div><br><br><br>
                        <div class="signature-line"></div>
                        <div class="signature-name" style="margin-top: 5px;"><u><?= htmlspecialchars($wali_name) ?></u></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; vertical-align: top; padding: 20px 0 0 0;">
                    <div class="signature-cell">
                        <div class="know-text" style="margin-bottom: 20px;">Mengetahui</div>
                        <div class="signature-role">Kepala Sekolah</div><br><br><br>
                        <div class="signature-line"></div>
                        <div class="signature-name" style="margin-top: 5px;"><u><?= htmlspecialchars($kepsek_name) ?></u></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
