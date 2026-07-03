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
                            <h3 class="card-title">Form Input Soal</h3>
                        </div>
                        <form action="<?= BASEURL; ?>/BankSoal/simpan" method="POST">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipe Soal</label>
                                            <select class="form-control" name="tipe_soal" required>
                                                <option value="PG">Pilihan Ganda (PG)</option>
                                                <option value="PG_KOMPLEKS">PG Kompleks (Banyak Jawaban Benar)</option>
                                                <option value="ESSAY">Esai</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tingkat Kesulitan</label>
                                            <select class="form-control" name="tingkat_kesulitan" required>
                                                <option value="Mudah">Mudah</option>
                                                <option value="Sedang" selected>Sedang</option>
                                                <option value="Sulit">Sulit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Pertanyaan (Mendukung Teks Biasa & Editor Nanti)</label>
                                    <textarea class="form-control" name="pertanyaan" rows="5" required placeholder="Ketikkan pertanyaan disini..."></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Opsi A</label>
                                            <input type="text" class="form-control" name="opsi_a" placeholder="Jawaban A">
                                        </div>
                                        <div class="form-group">
                                            <label>Opsi B</label>
                                            <input type="text" class="form-control" name="opsi_b" placeholder="Jawaban B">
                                        </div>
                                        <div class="form-group">
                                            <label>Opsi C</label>
                                            <input type="text" class="form-control" name="opsi_c" placeholder="Jawaban C">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Opsi D</label>
                                            <input type="text" class="form-control" name="opsi_d" placeholder="Jawaban D">
                                        </div>
                                        <div class="form-group">
                                            <label>Opsi E (Opsional)</label>
                                            <input type="text" class="form-control" name="opsi_e" placeholder="Jawaban E">
                                        </div>
                                        <div class="form-group">
                                            <label>Kunci Jawaban (A/B/C/D/E)</label>
                                            <input type="text" class="form-control" name="kunci_jawaban" placeholder="Contoh: A">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan Soal</button>
                                <a href="<?= BASEURL; ?>/BankSoal" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
