<?php
// 1. PERBAIKAN PATH INCLUDE
// Path ini MUNDUR DUA KALI untuk mencapai ROOT PROYEK (ABSENSI SISWA IKBAR)
include __DIR__ . '/../../koneksi.php';

// Ambil ID kelas dari URL secara aman
// Perhatikan, link di kelas.php menggunakan parameter 'id'
$idkelas = $_GET['id'] ?? null;

// Jika idkelas tidak ada di URL, hentikan dengan pesan
if (!$idkelas) {
    echo "<p style='color:red;'>ID kelas tidak ditemukan di URL.</p>";
    exit;
}

// Ambil data kelas dari database
$query = "SELECT * FROM kelas WHERE idkelas = '$idkelas'";
// 2. PERBAIKAN NAMA VARIABEL KONEKSI ($conn menjadi $koneksi)
$result = mysqli_query($koneksi, $query);
$kelas = mysqli_fetch_assoc($result);

// Jika data kelas tidak ditemukan
if (!$kelas) {
    echo "<p style='color:red;'>Data kelas tidak ditemukan di database.</p>";
    exit;
}
?>

<section class="content">

    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title text-white">Edit Kelas</h3>
        </div>

        <form action="db/dbkelas.php?proses=edit" method="post" enctype="multipart/form-data">
            <div class="card-body">

                <input type="hidden" name="idkelas" value="<?= htmlspecialchars($kelas['idkelas'] ?? '') ?>">

                <div class="form-group mb-3">
                    <label for="namakelas" class="font-weight-bold">Nama Kelas</label>
                    <input type="text" class="form-control" id="namakelas" name="namakelas"
                        value="<?= htmlspecialchars($kelas['namakelas'] ?? '') ?>" required
                        placeholder="Contoh: X IPA 1">
                </div>

                <div class="form-group mb-4">
                    <label for="fotokelas" class="font-weight-bold">Ganti Foto Kelas (Opsional)</label>
                    <input type="file" class="form-control-file" id="fotokelas" name="fotokelas" accept="image/*">
                </div>

                <?php if (!empty($kelas['fotokelas'])): ?>
                    <div class="mb-3">
                        <label class="font-weight-bold">Foto lama:</label>

                        <img src="foto/kelas/<?= htmlspecialchars($kelas['fotokelas']) ?>"
                            width="150" height="150" class="img-thumbnail" alt="Foto Kelas Lama">
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="index.php?halaman=kelas" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>

    </div>
</section>