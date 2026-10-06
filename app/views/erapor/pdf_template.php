<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor - <?= $siswa['nama_lengkap'] ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.4; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-5 { margin-top: 20px; }
        .mt-10 { margin-top: 40px; }
        .mb-5 { margin-bottom: 20px; }
        .border-bottom { border-bottom: 2px solid #000; margin-bottom: 15px; padding-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-data td { padding: 3px; vertical-align: top; }
        
        .table-nilai { border: 1px solid #000; }
        .table-nilai th, .table-nilai td { border: 1px solid #000; padding: 6px; }
        .table-nilai th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .table-nilai td.center { text-align: center; }
        
        .header-sekolah { font-size: 14pt; font-weight: bold; text-align: center; text-transform: uppercase; }
        .header-alamat { font-size: 10pt; text-align: center; margin-bottom: 10px; }
        
        .title { font-size: 12pt; font-weight: bold; text-align: center; margin: 15px 0; }
        
        .ttd-box { width: 100%; margin-top: 40px; }
        .ttd-col { width: 33.33%; float: left; text-align: center; }
        .clearfix { clear: both; }
    </style>
</head>
<body>

    <div class="border-bottom">
        <div class="header-sekolah"><?= $pengaturan['nama_aplikasi'] ?? 'SMANW' ?></div>
        <div class="header-alamat"><?= $pengaturan['teks_footer'] ?? 'Alamat Sekolah' ?></div>
    </div>

    <div class="title">LAPORAN HASIL BELAJAR SISWA</div>

    <table class="table-data" style="margin-bottom: 20px;">
        <tr>
            <td width="20%">Nama Peserta Didik</td>
            <td width="2%">:</td>
            <td width="48%" class="font-bold"><?= strtoupper($siswa['nama_lengkap']) ?></td>
            
            <td width="15%">Kelas</td>
            <td width="2%">:</td>
            <td width="13%"><?= $siswa['nama_kelas'] ?></td>
        </tr>
        <tr>
            <td>NISN / NIS</td>
            <td>:</td>
            <td><?= $siswa['nisn'] ?> / <?= $siswa['nis'] ?></td>
            
            <td>Semester</td>
            <td>:</td>
            <td><?= $semester == '1' ? '1 (Ganjil)' : '2 (Genap)' ?></td>
        </tr>
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td><?= $pengaturan['nama_aplikasi'] ?? 'SMANW' ?></td>
            
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td><?= $tahun_name ?></td>
        </tr>
    </table>

    <table class="table-nilai">
        <thead>
            <tr>
                <th width="5%" rowspan="2">No</th>
                <th width="45%" rowspan="2">Mata Pelajaran</th>
                <th width="10%" rowspan="2">KKM</th>
                <th width="40%" colspan="2">Nilai Akhir</th>
            </tr>
            <tr>
                <th width="20%">Angka</th>
                <th width="20%">Sikap</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($nilai_data as $kelompok): ?>
                <tr>
                    <td colspan="5" class="font-bold bg-light" style="background-color: #f9f9f9;">
                        <?= $kelompok['nama_kelompok'] ?>
                    </td>
                </tr>
                <?php $no = 1; foreach($kelompok['mapel'] as $m): ?>
                <tr>
                    <td class="center"><?= $no++ ?></td>
                    <td><?= $m['nama_mapel'] ?></td>
                    <td class="center"><?= $m['kkm'] ?></td>
                    <td class="center font-bold"><?= $m['nilai'] ?></td>
                    <td class="center font-bold"><?= $m['sikap'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            
            <!-- Rata-rata -->
            <tr>
                <td colspan="3" class="text-right font-bold pr-2">RATA - RATA :</td>
                <td class="center font-bold"><?= $rata_rata ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- Ekstrakurikuler -->
    <div class="mt-5 font-bold">Ekstrakurikuler:</div>
    <table class="table-nilai">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Kegiatan Ekstrakurikuler</th>
                <th width="15%">Nilai</th>
                <th width="45%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($ekskul)): ?>
            <tr>
                <td colspan="4" class="center text-muted">Belum ada data ekstrakurikuler</td>
            </tr>
            <?php else: ?>
                <?php $no=1; foreach($ekskul as $e): ?>
                <tr>
                    <td class="center"><?= $no++ ?></td>
                    <td><?= $e['nama_ekskul'] ?></td>
                    <td class="center font-bold"><?= $e['nilai'] ?></td>
                    <td><?= $e['keterangan'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Ketidakhadiran -->
    <div style="width: 45%; float: left; margin-top: 20px;">
        <div class="font-bold mb-2">Ketidakhadiran:</div>
        <table class="table-nilai">
            <tr>
                <td width="60%">Sakit</td>
                <td width="40%" class="center"><?= $absensi['sakit'] ?> hari</td>
            </tr>
            <tr>
                <td>Izin</td>
                <td class="center"><?= $absensi['izin'] ?> hari</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td class="center"><?= $absensi['alfa'] ?> hari</td>
            </tr>
        </table>
    </div>

    <!-- Catatan Wali Kelas -->
    <div style="width: 50%; float: right; margin-top: 20px;">
        <div class="font-bold mb-2">Catatan Wali Kelas:</div>
        <div style="border: 1px solid #000; min-height: 85px; padding: 10px;">
            <?= nl2br(htmlspecialchars($catatan['catatan'] ?? '')) ?>
        </div>
    </div>
    
    <div class="clearfix"></div>

    <!-- Tanda Tangan -->
    <div class="ttd-box">
        <div class="ttd-col">
            Mengetahui,<br>
            Orang Tua / Wali
            <br><br><br><br><br>
            ( ......................................... )
        </div>
        <div class="ttd-col">
            <br>
            Wali Kelas
            <br><br><br><br><br>
            <b><u><?= $wali_name ?></u></b>
        </div>
        <div class="ttd-col">
            .................., <?= date('d F Y') ?><br>
            Kepala Sekolah
            <br><br><br><br><br>
            <b><u><?= $kepsek_name ?? 'Kepala Sekolah' ?></u></b>
        </div>
        <div class="clearfix"></div>
    </div>


</body>
</html>
