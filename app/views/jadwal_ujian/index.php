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
                    <?php Flasher::flash(); ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Ujian Terjadwal</h3>
                            <div class="card-tools">
                                <a href="<?= BASEURL; ?>/JadwalUjian/tambah" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Buat Jadwal Baru
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tableJadwal">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama Ujian</th>
                                        <th>Waktu Pelaksanaan</th>
                                        <th>Durasi (Menit)</th>
                                        <th>Pengawas Ruangan</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($data['jadwal'] as $row) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><strong><?= $row['nama_ujian']; ?></strong></td>
                                        <td>
                                            Mulai: <?= date('d M Y, H:i', strtotime($row['waktu_mulai'])); ?><br>
                                            Selesai: <?= date('d M Y, H:i', strtotime($row['waktu_selesai'])); ?>
                                        </td>
                                        <td><?= $row['durasi_menit']; ?></td>
                                        <td><span class="badge badge-info"><?= $row['nama_pengawas']; ?></span></td>
                                        <td>
                                            <?php if($row['status'] == 'Aktif'): ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php elseif($row['status'] == 'Draft'): ?>
                                                <span class="badge badge-warning">Draft</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Selesai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= BASEURL; ?>/JadwalUjian/hapus/<?= $row['id_jadwal']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus jadwal ini?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
