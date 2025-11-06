<?php
// Ganti include __DIR__ . '/../../koneksi.php'; jika ini adalah form di dalam folder halaman
include __DIR__ . '/../../koneksi.php'; 

if (isset($_POST['simpan'])) {
    // Hapus baris $idkategori
    // $idkategori   = mysqli_real_escape_string($koneksi, $_POST['idkategori']);
    $namakategori = mysqli_real_escape_string($koneksi, $_POST['namakategori']);

    // Validasi input
    if (empty($namakategori)) { // Hanya cek Nama Kategori
        echo "<div class='alert alert-warning'>Nama kategori tidak boleh kosong!</div>";
    } else {
        // Hapus Cek ID sudah ada
        // $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE idkategori='$idkategori'");
        // if (mysqli_num_rows($cek) > 0) {
        //     echo "<div class='alert alert-danger'>ID Kategori <strong>$idkategori</strong> sudah ada!</div>";
        // } else {
            // Simpan data baru tanpa menyertakan idkategori
            $query = mysqli_query($koneksi, "INSERT INTO kategori (namakategori) VALUES ('$namakategori')");
            if ($query) {
                echo "<div class='alert alert-success'>Kategori berhasil ditambahkan!</div>";
                echo "<meta http-equiv='refresh' content='2;url=index.php?halaman=kategori'>";
            } else {
                echo "<div class='alert alert-danger'>Gagal menambah kategori: " . mysqli_error($koneksi) . "</div>";
            }
        // }
    }
}
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Kategori</h3>
    </div>

    <form method="POST">
        <div class="card-body">
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="namakategori" class="form-control" placeholder="Masukkan nama kategori">
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php?halaman=kategori" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>