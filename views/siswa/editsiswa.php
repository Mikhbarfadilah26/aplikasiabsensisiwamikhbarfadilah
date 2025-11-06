<?php
// views/siswa/editsiswa.php

// Pastikan koneksi sudah ada
if (!isset($koneksi)) {
    require_once 'koneksi.php'; 
}

// Ambil ID Siswa dari URL
$idsiswa = (int)($_GET['id'] ?? 0);

if ($idsiswa === 0) {
    echo '<div class="alert alert-danger">ID Siswa tidak ditemukan.</div>';
    exit;
}

// 1. Ambil data siswa yang akan diedit
$sql_data = mysqli_query($koneksi, "SELECT * FROM siswa WHERE idsiswa='$idsiswa'");
$data_siswa = mysqli_fetch_assoc($sql_data);

if (!$data_siswa) {
    echo '<div class="alert alert-danger">Data siswa tidak ditemukan.</div>';
    exit;
}

// 2. Ambil data kelas untuk dropdown
$sql_kelas = mysqli_query($koneksi, "SELECT idkelas, namakelas FROM kelas ORDER BY namakelas ASC");

// Tampilkan pesan error jika gagal edit
$status = $_GET['status'] ?? '';
$error_message = '';
if ($status == 'gagal_edit') {
    $error_message = 'Gagal memperbarui data siswa. Silakan coba lagi.';
}
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Siswa: <?= htmlspecialchars($data_siswa['namasiswa']); ?></h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit Data Siswa</h3>
            </div>
            
            <form action="db/dbsiswa.php?proses=editsiswa" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    
                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error_message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" name="idsiswa" value="<?= $data_siswa['idsiswa']; ?>">

                    <div class="form-group">
                        <label for="idkelas">Kelas</label>
                        <select class="form-control" id="idkelas" name="idkelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php while ($data_kelas = mysqli_fetch_assoc($sql_kelas)) : ?>
                                <option value="<?= $data_kelas['idkelas']; ?>" 
                                    <?= ($data_siswa['idkelas'] == $data_kelas['idkelas']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($data_kelas['namakelas']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="NIS">NIS (Nomor Induk Siswa)</label>
                        <input type="text" class="form-control" id="NIS" name="NIS" value="<?= htmlspecialchars($data_siswa['NIS']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="namasiswa">Nama Siswa</label>
                        <input type="text" class="form-control" id="namasiswa" name="namasiswa" value="<?= htmlspecialchars($data_siswa['namasiswa']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Isi password jika ingin mengganti password lama.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="fotosiswa">Foto Siswa Saat Ini</label>
                        <?php if (!empty($data_siswa['fotosiswa'])) : ?>
                            <div class="mb-2">
                                <img src="foto/siswa/<?= htmlspecialchars($data_siswa['fotosiswa']); ?>" 
                                     alt="Foto Siswa" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                            </div>
                        <?php else : ?>
                            <p class="text-muted">Belum ada foto.</p>
                        <?php endif; ?>

                        <label for="fotosiswa_new">Ganti Foto</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fotosiswa_new" name="fotosiswa" accept="image/*">
                                <label class="custom-file-label" for="fotosiswa_new">Pilih file baru</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Foto lama akan diganti jika Anda mengunggah file baru.</small>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Update</button>
                    <a href="index.php?halaman=siswa" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>