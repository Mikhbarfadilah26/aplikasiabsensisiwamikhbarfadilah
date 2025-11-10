<?php
// views/kehadiran/editkehadiran.php
require_once 'koneksi.php';

// Jalankan session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek ID kehadiran
$idkehadiran = $_GET['id'] ?? null;
if (!$idkehadiran) {
    echo "<div class='alert alert-danger'>ID Kehadiran tidak ditemukan!</div>";
    exit;
}

// Ambil data kehadiran + siswa + status absensi
$query = "
    SELECT k.*, s.namasiswa, sa.nama_status 
    FROM kehadiran k
    JOIN siswa s ON k.idsiswa = s.idsiswa
    JOIN status_absen sa ON k.id_status = sa.id_status
    WHERE k.idkehadiran = ?
";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $idkehadiran);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Ambil daftar siswa & status absensi
$query_siswa = mysqli_query($koneksi, "SELECT idsiswa, namasiswa FROM siswa ORDER BY namasiswa ASC");
$query_status = mysqli_query($koneksi, "SELECT id_status, nama_status FROM status_absen ORDER BY id_status ASC");
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-warning">
            <h3 class="card-title text-white">Edit Data Kehadiran (ID: <?= htmlspecialchars($data['idkehadiran']) ?>)</h3>
        </div>

        <form action="db/dbkehadiran.php" method="post">
            <div class="card-body">
                <input type="hidden" name="idkehadiran" value="<?= htmlspecialchars($data['idkehadiran']) ?>">
                <input type="hidden" name="idadmin" value="<?= htmlspecialchars($data['idadmin']) ?>">

                <!-- Nama Siswa -->
                <div class="form-group mb-3">
                    <label for="idsiswa" class="font-weight-bold">Nama Siswa</label>
                    <select class="form-control" id="idsiswa" name="idsiswa" required>
                        <?php while ($siswa = mysqli_fetch_assoc($query_siswa)): ?>
                            <option value="<?= $siswa['idsiswa'] ?>"
                                <?= ($siswa['idsiswa'] == $data['idsiswa']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($siswa['namasiswa']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="form-group mb-3">
                    <label for="tanggal" class="font-weight-bold">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                        value="<?= htmlspecialchars($data['tanggal']) ?>" required>
                </div>

                <!-- Status Kehadiran -->
                <div class="form-group mb-4">
                    <label for="id_status" class="font-weight-bold">Status Kehadiran</label>
                    <select class="form-control" id="id_status" name="id_status" required>
                        <?php while ($status = mysqli_fetch_assoc($query_status)): ?>
                            <option value="<?= $status['id_status'] ?>"
                                <?= ($status['id_status'] == $data['id_status']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['nama_status']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

            </div>

            <div class="card-footer text-right">
                <button type="submit" name="edit" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Simpan Perubahan
                </button>
                <a href="index.php?halaman=kehadiran" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
