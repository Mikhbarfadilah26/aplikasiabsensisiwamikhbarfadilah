<?php
// views/kehadiran/tambahkehadiran.php
require_once 'koneksi.php';

// Jalankan session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data siswa dan status absen
$query_siswa = mysqli_query($koneksi, "SELECT idsiswa, namasiswa FROM siswa ORDER BY namasiswa ASC");
$query_status = mysqli_query($koneksi, "SELECT id_status, nama_status FROM status_absen ORDER BY id_status ASC");

$idadmin_aktif = $_SESSION['idadmin'] ?? 1; // Default jika belum login admin
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title text-white">Tambah Data Kehadiran Siswa</h3>
        </div>

        <form action="db/dbkehadiran.php" method="post">
            <div class="card-body">

                <input type="hidden" name="idadmin" value="<?= htmlspecialchars($idadmin_aktif) ?>">

                <!-- Nama Siswa -->
                <div class="form-group mb-3">
                    <label for="idsiswa" class="font-weight-bold">Nama Siswa</label>
                    <select class="form-control" id="idsiswa" name="idsiswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php while ($siswa = mysqli_fetch_assoc($query_siswa)): ?>
                            <option value="<?= $siswa['idsiswa'] ?>"><?= htmlspecialchars($siswa['namasiswa']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="form-group mb-3">
                    <label for="tanggal" class="font-weight-bold">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                        value="<?= date('Y-m-d') ?>" required>
                </div>

                <!-- Status Kehadiran -->
                <div class="form-group mb-4">
                    <label for="id_status" class="font-weight-bold">Status Kehadiran</label>
                    <select class="form-control" id="id_status" name="id_status" required>
                        <option value="">-- Pilih Status --</option>
                        <?php while ($status = mysqli_fetch_assoc($query_status)): ?>
                            <option value="<?= $status['id_status'] ?>"><?= htmlspecialchars($status['nama_status']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

            </div>

            <div class="card-footer text-right">
                <button type="submit" name="simpan" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
                <a href="index.php?halaman=kehadiran" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
