<?php
include __DIR__ . '/../../koneksi.php';

// ===========================================
// LOGIKA TAMBAH KATEGORI (Bagian Form Tambah Kategori)
// ===========================================
if (isset($_POST['simpan'])) {
    // HAPUS: $idkategori = mysqli_real_escape_string($koneksi, $_POST['idkategori']);
    $namakategori = mysqli_real_escape_string($koneksi, $_POST['namakategori']);

    // Validasi input
    if (empty($namakategori)) { // Hanya cek Nama Kategori
        echo "<div class='alert alert-warning'>Nama kategori tidak boleh kosong!</div>";
    } else {
        // Simpan data baru (Hanya namakategori, idkategori akan di-auto-increment)
        $query = mysqli_query($koneksi, "INSERT INTO kategori (namakategori) VALUES ('$namakategori')");
        if ($query) {
            echo "<div class='alert alert-success'>Kategori berhasil ditambahkan!</div>";
            echo "<meta http-equiv='refresh' content='2;url=index.php?halaman=kategori'>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menambah kategori: " . mysqli_error($koneksi) . "</div>";
        }
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
                <input type="text" name="namakategori" class="form-control" placeholder="Masukkan nama kategori" required>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php?halaman=kategori" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php
// ======== LOGIKA HAPUS DATA ========
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $idkategori = mysqli_real_escape_string($koneksi, $_GET['idkategori']);
    $hapus = mysqli_query($koneksi, "DELETE FROM kategori WHERE idkategori='$idkategori'");
    if ($hapus) {
        echo "<div class='alert alert-success'>Kategori berhasil dihapus!</div>";
        // Refresh 2 detik agar pesan terlihat sebelum kembali
        echo "<meta http-equiv='refresh' content='2;url=index.php?halaman=kategori'>"; 
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus kategori: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Kategori</h3>
        </div>

    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY idkategori ASC");
                while ($data = mysqli_fetch_assoc($query)) :
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($data['namakategori']); ?></td>
                        <td>
                            <a href="index.php?halaman=editkategori&idkategori=<?= $data['idkategori']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?halaman=kategori&aksi=hapus&idkategori=<?= $data['idkategori']; ?>"
                               onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                               class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>