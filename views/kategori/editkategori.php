<?php
include __DIR__ . '/../../koneksi.php';

// Ambil ID dari URL
if (!isset($_GET['idkategori']) || empty($_GET['idkategori'])) {
    echo "<div class='alert alert-danger'>ID Kategori tidak ditemukan!</div>";
    exit;
}

$idkategori = mysqli_real_escape_string($koneksi, $_GET['idkategori']);

// Ambil data lama
$query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE idkategori = '$idkategori'");
if (!$query || mysqli_num_rows($query) == 0) {
    echo "<div class='alert alert-danger'>Data kategori tidak ditemukan!</div>";
    exit;
}
$data = mysqli_fetch_assoc($query);

// Proses update jika form disubmit
if (isset($_POST['update_kategori'])) {
    // $idkategori tidak perlu diambil dari POST, karena sudah ada dari GET/URL
    $namakategori = mysqli_real_escape_string($koneksi, $_POST['namakategori']);
    
    if (empty($namakategori)) {
        echo "<div class='alert alert-warning'>Nama kategori tidak boleh kosong!</div>";
    } else {
        $update = mysqli_query($koneksi, "UPDATE kategori SET namakategori='$namakategori' WHERE idkategori='$idkategori'");
        if ($update) {
            echo "<div class='alert alert-success'>Kategori berhasil diperbarui!</div>";
            echo "<meta http-equiv='refresh' content='2;url=index.php?halaman=kategori'>";
        } else {
            echo "<div class='alert alert-danger'>Gagal memperbarui kategori: " . mysqli_error($koneksi) . "</div>";
        }
    }
}
?>

<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Kategori</h3>
        </div>

        <form method="POST">
            <div class="card-body">
                <input type="hidden" name="idkategori" value="<?php echo htmlspecialchars($data['idkategori']); ?>">

                <div class="form-group">
                    <label>ID Kategori (Tidak Dapat Diubah)</label>
                    <p class="form-control-static form-control-lg w-100"><?php echo htmlspecialchars($data['idkategori']); ?></p>
                </div>
                
                <div class="form-group mt-3">
                    <label>Nama Kategori</label>
                    <input type="text" name="namakategori" class="form-control form-control-lg w-100"
                           value="<?php echo htmlspecialchars($data['namakategori']); ?>" placeholder="Masukkan nama kategori">
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" name="update_kategori" class="btn btn-primary">Simpan Perubahan</button>
                <a href="index.php?halaman=kategori" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>