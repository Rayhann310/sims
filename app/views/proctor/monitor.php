<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $data['judul']; ?></h1>
                </div>
                <div class="col-sm-6">
                    <a href="<?= BASEURL; ?>/Proctor" class="btn btn-default float-right">Kembali</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php Flasher::flash(); ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title"><i class="fas fa-users"></i> Monitor Peserta (Refresh halaman untuk update terbaru)</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama Peserta</th>
                                        <th>NISN</th>
                                        <th>Status Ujian</th>
                                        <th>Keterangan Sistem</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['peserta'])): ?>
                                        <tr><td colspan="6" class="text-center">Belum ada peserta yang tergabung di ruangan ini.</td></tr>
                                    <?php else: ?>
                                        <?php $i = 1; foreach($data['peserta'] as $p): ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><strong><?= $p['nama_lengkap']; ?></strong></td>
                                                <td><?= $p['nisn']; ?></td>
                                                <td>
                                                    <?php if($p['status_ujian'] == '1'): ?>
                                                        <span class="badge badge-success">Sedang Mengerjakan</span>
                                                    <?php elseif($p['status_ujian'] == '2'): ?>
                                                        <span class="badge badge-danger">Terkunci (Melanggar)</span>
                                                    <?php elseif($p['status_ujian'] == '3'): ?>
                                                        <span class="badge badge-secondary">Selesai</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Belum Mulai</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($p['status_ujian'] == '2'): ?>
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $p['alasan_terkunci']; ?></span>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($p['status_ujian'] == '2'): ?>
                                                        <a href="<?= BASEURL; ?>/Proctor/unlockSiswa/<?= $p['id_peserta']; ?>/<?= $data['id_jadwal']; ?>" 
                                                           class="btn btn-success btn-sm btn-block"
                                                           onclick="return confirm('Yakin ingin membuka akses ujian siswa ini?');">
                                                           <i class="fas fa-unlock"></i> Buka Kunci Layar
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-default btn-sm btn-block" disabled>Buka Kunci</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
