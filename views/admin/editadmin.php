<?php
// Pastikan koneksi.php sudah di-include dan $koneksi sudah tersedia.
// PERBAIKAN PATH INCLUDE
include __DIR__ . '/../../koneksi.php';

// Ambil ID Admin dari URL secara aman
// Link ke halaman ini seharusnya menggunakan parameter 'id', contoh: index.php?halaman=editadmin&id=1
$idadmin = $_GET['id'] ?? null;

// Jika idadmin tidak ada di URL, hentikan dengan pesan
if (!$idadmin) {
    echo "<p style='color:red;'>ID Admin tidak ditemukan di URL.</p>";
    exit;
}

// Ambil data Admin lama dari database
// Menggunakan idadmin (bukan id_admin) sesuai skema tabel yang terlihat
$query = "SELECT * FROM admin WHERE idadmin = '$idadmin'";
$result = mysqli_query($koneksi, $query);
$admin = mysqli_fetch_assoc($result);

// Jika data Admin tidak ditemukan
if (!$admin) {
    echo "<p style='color:red;'>Data Admin dengan ID $idadmin tidak ditemukan!</p>";
    exit;
}

// Catatan: Logika pemrosesan POST (update_admin) dipindahkan ke db/dbadmin.php
// sesuai dengan pola kode Edit Kelas.
?>

<section class="content">

    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title text-white">Edit Profil Admin: <?= htmlspecialchars($admin['namaadmin'] ?? 'Unknown'); ?></h3>
        </div>

        <form action="db/dbadmin.php?proses=edit" method="post" enctype="multipart/form-data">
            <div class="card-body">

                <input type="hidden" name="idadmin" value="<?= htmlspecialchars($admin['idadmin'] ?? '') ?>">

                <div class="form-group mb-3">
                    <label for="namaadmin" class="font-weight-bold">Nama Admin</label>
                    <input type="text" class="form-control" id="namaadmin" name="namaadmin"
                        value="<?= htmlspecialchars($admin['namaadmin'] ?? '') ?>" required
                        placeholder="Masukkan Nama Lengkap Admin">
                </div>

                <div class="form-group mb-3">
                    <label for="nohp" class="font-weight-bold">Nomor HP</label>
                    <input type="text" class="form-control" id="nohp" name="nohp"
                        value="<?= htmlspecialchars($admin['nohp'] ?? '') ?>"
                        placeholder="Nomor HP">
                </div>

                <div class="form-group mb-3">
                    <label for="username" class="font-weight-bold">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="<?= htmlspecialchars($admin['username'] ?? '') ?>" required
                        placeholder="Username untuk Login">
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="font-weight-bold">Password Baru (Opsional)</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Kosongkan jika tidak ingin diubah">
                    <small class="form-text text-warning">Password akan dienkripsi saat disimpan.</small>
                </div>

                <div class="form-group mb-4">
                    <label for="fotoadmin" class="font-weight-bold">Ganti Foto Admin (Opsional)</label>
                    <input type="file" class="form-control-file" id="fotoadmin" name="fotoadmin" accept="image/*">
                </div>

                <?php if (!empty($admin['fotoadmin'])): ?>
                    <div class="mb-3">
                        <label class="font-weight-bold">Foto lama:</label><br>

                        <img src="foto/admin/<?= htmlspecialchars($admin['fotoadmin']) ?>"
                            width="150" height="150" class="img-thumbnail rounded" alt="Foto Admin Lama">
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="index.php?halaman=admin" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>

    </div>
</section>