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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Input Jadwal & Pengawas</h3>
                        </div>
                        <form action="<?= BASEURL; ?>/JadwalUjian/simpan" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Ujian</label>
                                    <input type="text" class="form-control" name="nama_ujian" placeholder="Contoh: Ujian Tengah Semester Ganjil - Matematika" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Mulai</label>
                                            <input type="datetime-local" class="form-control" name="waktu_mulai" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Selesai</label>
                                            <input type="datetime-local" class="form-control" name="waktu_selesai" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Durasi Pengerjaan (Menit)</label>
                                            <input type="number" class="form-control" name="durasi_menit" placeholder="Contoh: 120" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="Draft">Draft (Belum Aktif)</option>
                                                <option value="Aktif">Aktif (Tampil di siswa)</option>
                                                <option value="Selesai">Selesai</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Pilih Guru Pengawas Ruangan</label>
                                    <select class="form-control select2" name="id_guru_pengawas" required>
                                        <option value="">-- Pilih Guru Pengawas --</option>
                                        <?php foreach($data['guru'] as $g) : ?>
                                            <option value="<?= $g['id']; ?>"><?= $g['nama_lengkap']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Hanya guru yang dipilih ini yang memiliki Hak Akses untuk melihat Token dan melakukan Unlock siswa di ruangan ini.</small>
                                </div>
                                
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                                <a href="<?= BASEURL; ?>/JadwalUjian" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
