<?php
$nama_sekolah = !empty($pengaturan['nama_aplikasi']) ? $pengaturan['nama_aplikasi'] : "SMA Nahdlatul Wathan Jakarta";
$yayasan = "Yayasan Mi'rajus Shibyan";

// Cari path logo fisik di smanw
$logo_path = '';
$possible_paths = [
    realpath(__DIR__ . '/../../../public/img/logo.png'),
    realpath(__DIR__ . '/../../../public/images/logo.png'),
    realpath(__DIR__ . '/../../../../eraporsmanw/assets/images/logo.png'),
];

foreach ($possible_paths as $path) {
    if ($path && file_exists($path)) {
        $logo_path = $path;
        break;
    }
}

$logo_src = '';
if ($logo_path && file_exists($logo_path)) {
    $logo_data = base64_encode(file_get_contents($logo_path));
    $logo_src = 'data:image/png;base64,' . $logo_data;
} elseif (!empty($pengaturan['logo_sekolah'])) {
    if (strpos($pengaturan['logo_sekolah'], 'data:image') === 0) {
        $logo_src = $pengaturan['logo_sekolah'];
    } else {
        $logo_src = 'data:image/png;base64,' . $pengaturan['logo_sekolah'];
    }
} else {
    $logo_src = $logo_path;
}

$nama_siswa = $siswa['nama_siswa'] ?? $siswa['nama_lengkap'] ?? '';
$tahun_ajaran_name = $tahun_name ?? '2025/2026';
?>
<div style="width:210mm;height:297mm;padding:20mm;box-sizing:border-box;font-family:Times New Roman;">
    <div style="text-align:center;">
        <h1 style="margin-bottom:5mm;">RAPOR PENILAIAN TENGAH SEMESTER</h1>
        <h2 style="margin-bottom:5mm;">SEKOLAH MENENGAH ATAS</h2>
        <h3 style="margin-bottom:10mm;">(SMA)</h3>
        <h3 style="margin-bottom:10mm;">Tahun Pelajaran <?= htmlspecialchars($tahun_ajaran_name) ?></h3>

        <div style="width:60mm;height:60mm;margin:0 auto 10mm;">
            <img src="<?= $logo_src ?>" style="width:100%;height:100%;object-fit:cover;" alt="Logo Sekolah">
        </div><br><br><br>

        <div style="margin-bottom:2mm;font-weight:bold;">NAMA SISWA</div>
        <div style="border:2px solid #000;padding:10px;font-size:20pt;font-weight:bold;margin-bottom:10mm;">
            <?= htmlspecialchars($nama_siswa) ?>
        </div>
        <div style="margin-bottom:2mm;font-weight:bold;"><?= htmlspecialchars($yayasan) ?></div>
        <div style="font-weight:bold;font-size:16pt;"><?= htmlspecialchars($nama_sekolah) ?></div>
    </div>
</div>
