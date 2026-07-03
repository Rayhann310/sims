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
                            <h3 class="card-title">Daftar Bank Soal CBT</h3>
                            <div class="card-tools">
                                <a href="<?= BASEURL; ?>/BankSoal/tambah" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Soal
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tableSoal">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Pertanyaan</th>
                                        <th style="width: 15%">Tipe</th>
                                        <th style="width: 15%">Tingkat</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($data['soal'] as $row) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= strip_tags(substr($row['pertanyaan'], 0, 100)) . '...'; ?></td>
                                        <td><span class="badge badge-info"><?= $row['tipe_soal']; ?></span></td>
                                        <td>
                                            <?php if($row['tingkat_kesulitan'] == 'Mudah'): ?>
                                                <span class="badge badge-success">Mudah</span>
                                            <?php elseif($row['tingkat_kesulitan'] == 'Sedang'): ?>
                                                <span class="badge badge-warning">Sedang</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Sulit</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= BASEURL; ?>/BankSoal/hapus/<?= $row['id_soal']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus soal ini?');">
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
