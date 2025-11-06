<?php
// views/siswa/tambahsiswa.php

// Pastikan koneksi sudah ada
if (!isset($koneksi)) {
    require_once 'koneksi.php'; 
}

// Ambil data kelas untuk dropdown
$sql_kelas = mysqli_query($koneksi, "SELECT idkelas, namakelas FROM kelas ORDER BY namakelas ASC");

// Tampilkan pesan error jika gagal tambah
$status = $_GET['status'] ?? '';
$error_message = '';
if ($status == 'gagal_tambah') {
    $error_message = 'Gagal menambahkan data siswa. Silakan coba lagi.';
}
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tambah Siswa Baru</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Data Siswa</h3>
            </div>
            
            <form action="db/dbsiswa.php?proses=tambahsiswa" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    
                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error_message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="idkelas">Kelas</label>
                        <select class="form-control" id="idkelas" name="idkelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php while ($data_kelas = mysqli_fetch_assoc($sql_kelas)) : ?>
                                <option value="<?= $data_kelas['idkelas']; ?>">
                                    <?= htmlspecialchars($data_kelas['namakelas']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="NIS">NIS (Nomor Induk Siswa)</label>
                        <input type="text" class="form-control" id="NIS" name="NIS" required>
                    </div>

                    <div class="form-group">
                        <label for="namasiswa">Nama Siswa</label>
                        <input type="text" class="form-control" id="namasiswa" name="namasiswa" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password (Default Login)</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fotosiswa">Foto Siswa</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fotosiswa" name="fotosiswa" accept="image/*">
                                <label class="custom-file-label" for="fotosiswa">Pilih file</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Maksimal 2MB. Format: JPG/PNG</small>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="index.php?halaman=siswa" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>