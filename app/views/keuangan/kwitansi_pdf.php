<?php
$logoBase64 = '';
if (!empty($data['pengaturan']['logo_sekolah'])) {
    $logoPath = $_SERVER['DOCUMENT_ROOT'] . parse_url($data['pengaturan']['logo_sekolah'], PHP_URL_PATH);
    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $imgData = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
    }
}

function terbilang($angka) {
    $angka = abs($angka);
    $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $terbilang = "";
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    }
    return $terbilang;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran</title>
    <style>
        @page { margin: 15px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; margin: 0; padding: 0; color: #333; }
        .header { width: 100%; border-bottom: 3px solid #333; padding-bottom: 5px; margin-bottom: 15px; text-align: center; }
        .header table { width: 100%; }
        .header table td { vertical-align: middle; }
        .logo { width: 80px; height: auto; }
        .school-name { font-size: 22px; font-weight: bold; margin: 0; color: #2c3e50; text-transform: uppercase; }
        .school-address { font-size: 13px; margin: 5px 0 0 0; color: #444; }
        .title { text-align: center; font-size: 20px; font-weight: bold; margin: 20px 0; text-decoration: underline; letter-spacing: 2px; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content-table td { padding: 8px 5px; vertical-align: top; }
        .label { width: 150px; font-weight: bold; }
        .colon { width: 20px; text-align: center; font-weight: bold; }
        .value { border-bottom: 1px dotted #ccc; }
        .amount-box { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; font-size: 18px; font-weight: bold; text-align: center; margin: 30px 0; border-radius: 5px; }
        .footer { width: 100%; margin-top: 50px; }
        .footer-table { width: 100%; }
        .footer-table td { text-align: center; width: 50%; }
        .signature-area { height: 80px; }
        .signature-line { border-top: 1px solid #333; width: 200px; margin: 0 auto; display: inline-block; padding-top: 5px; font-weight: bold; }
        .terbilang-box { background-color: #eef2f5; padding: 10px; border-radius: 4px; font-style: italic; border-left: 4px solid #3b82f6; margin-bottom: 20px; font-size: 13px; line-height: 1.5; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="width: 15%; text-align: left;">
                    <?php if (!empty($logoBase64)): ?>
                        <img src="<?= $logoBase64 ?>" class="logo" alt="Logo">
                    <?php endif; ?>
                </td>
                <td style="width: 70%; text-align: center;">
                    <h1 class="school-name"><?= htmlspecialchars($data['pengaturan']['nama_aplikasi'] ?? 'SEKOLAH') ?></h1>
                    <p class="school-address"><?= htmlspecialchars($data['pengaturan']['alamat'] ?? 'Alamat Sekolah Belum Diatur') ?></p>
                    <p style="font-size: 12px; margin: 3px 0 0 0; color: #666;">
                        Telp: <?= htmlspecialchars($data['pengaturan']['telepon'] ?? '-') ?> | Email: <?= htmlspecialchars($data['pengaturan']['email'] ?? '-') ?>
                    </p>
                </td>
                <td style="width: 15%;"></td>
            </tr>
        </table>
    </div>

    <div class="title">KWITANSI PEMBAYARAN</div>

    <table class="content-table">
        <tr>
            <td class="label">Telah Terima Dari</td>
            <td class="colon">:</td>
            <td class="value"><?= htmlspecialchars($data['tagihan']['nama_lengkap']) ?> (NISN: <?= htmlspecialchars($data['tagihan']['nisn']) ?>)</td>
        </tr>
        <tr>
            <td class="label">Untuk Pembayaran</td>
            <td class="colon">:</td>
            <td class="value">
                <?= htmlspecialchars(!empty($data['tagihan']['nama_kategori']) ? $data['tagihan']['nama_kategori'] : 'SPP Bulanan') ?> - 
                Bulan <?= htmlspecialchars($data['tagihan']['bulan']) ?> Tahun <?= htmlspecialchars($data['tagihan']['tahun']) ?>
            </td>
        </tr>
        <tr>
            <td class="label">Tanggal Bayar</td>
            <td class="colon">:</td>
            <td class="value">
                <?php 
                if(!empty($data['pembayaran'])) {
                    echo date('d F Y', strtotime($data['pembayaran'][0]['tanggal_bayar']));
                } else {
                    echo date('d F Y');
                }
                ?>
            </td>
        </tr>
    </table>

    <div class="amount-box">
        Terbilang: Rp <?= number_format($data['tagihan']['nominal'], 0, ',', '.') ?>,-
    </div>

    <div class="terbilang-box">
        <strong>Uang Sejumlah:</strong> <br>
        <?= ucwords(trim(terbilang($data['tagihan']['nominal']))) ?> Rupiah
    </div>

    <table class="footer-table">
        <tr>
            <td>
                <!-- Cap Lunas atau informasi bank -->
                <?php if($data['tagihan']['status'] == 'Lunas'): ?>
                    <h2 style="color: #10b981; border: 3px solid #10b981; padding: 10px; display: inline-block; transform: rotate(-10deg); text-transform: uppercase;">LUNAS</h2>
                <?php endif; ?>
            </td>
            <td>
                <p>Penerima,</p>
                <div class="signature-area"></div>
                <div class="signature-line">
                    ( Bag. Administrasi Keuangan )
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
