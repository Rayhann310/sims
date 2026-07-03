<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $data['judul']; ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Jadwal Pengawasan Anda Hari Ini</h3>
                        </div>
                        <div class="card-body">
                            <?php if(empty($data['jadwal_diawasi'])): ?>
                                <p class="text-muted text-center py-4">Tidak ada jadwal pengawasan aktif untuk Anda hari ini.</p>
                            <?php else: ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Ujian</th>
                                            <th>Waktu Pelaksanaan</th>
                                            <th>Durasi</th>
                                            <th style="width: 20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['jadwal_diawasi'] as $row): ?>
                                            <tr>
                                                <td><strong><?= $row['nama_ujian']; ?></strong></td>
                                                <td>
                                                    Mulai: <?= date('H:i', strtotime($row['waktu_mulai'])); ?> <br>
                                                    Selesai: <?= date('H:i', strtotime($row['waktu_selesai'])); ?>
                                                </td>
                                                <td><?= $row['durasi_menit']; ?> Menit</td>
                                                <td>
                                                    <a href="<?= BASEURL; ?>/Proctor/monitor/<?= $row['id_jadwal']; ?>" class="btn btn-warning btn-block">
                                                        <i class="fas fa-desktop"></i> Monitor Ruangan
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
