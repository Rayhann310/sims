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
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Penting!</h5>
                        Pastikan koneksi internet Anda stabil sebelum memulai ujian. Jika Anda keluar dari layar ujian, akun Anda akan <b>TERKUNCI</b>.
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Daftar Ujian Tersedia</h3>
                        </div>
                        <div class="card-body">
                            <?php if(empty($data['jadwal'])): ?>
                                <div class="text-center py-5">
                                    <h5 class="text-muted">Tidak ada jadwal ujian yang aktif saat ini.</h5>
                                </div>
                            <?php else: ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Ujian</th>
                                            <th>Waktu Pelaksanaan</th>
                                            <th>Durasi</th>
                                            <th style="width: 15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['jadwal'] as $row) : ?>
                                        <tr>
                                            <td><strong><?= $row['nama_ujian']; ?></strong></td>
                                            <td>
                                                <?= date('d M Y, H:i', strtotime($row['waktu_mulai'])); ?> s/d <br>
                                                <?= date('d M Y, H:i', strtotime($row['waktu_selesai'])); ?>
                                            </td>
                                            <td><?= $row['durasi_menit']; ?> Menit</td>
                                            <td>
                                                <a href="<?= BASEURL; ?>/UjianSiswa/mulai/<?= $row['id_jadwal']; ?>" class="btn btn-success btn-block">
                                                    Mulai Ujian <i class="fas fa-arrow-right"></i>
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
