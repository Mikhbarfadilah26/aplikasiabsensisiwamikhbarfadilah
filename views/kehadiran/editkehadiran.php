<?php
// Asumsi $koneksi sudah tersedia

// 1. Ambil ID dari URL
$idkehadiran = $_GET['id'] ?? null;

if (!$idkehadiran) {
    echo "<div class='alert alert-danger'>ID Kehadiran tidak ditemukan!</div>";
    exit;
}

// 2. Ambil Data Kehadiran Lama berdasarkan idkehadiran, JOIN dengan siswa
$query_lama = "
    SELECT 
        k.idkehadiran, 
        k.idsiswa, 
        k.idadmin, 
        k.tanggal, 
        k.statuskehadiran, 
        s.namasiswa 
    FROM kehadiran k 
    JOIN siswa s ON k.idsiswa = s.idsiswa 
    WHERE k.idkehadiran = ?
";
$stmt_lama = mysqli_prepare($koneksi, $query_lama);
mysqli_stmt_bind_param($stmt_lama, "i", $idkehadiran);
mysqli_stmt_execute($stmt_lama);
$result_lama = mysqli_stmt_get_result($stmt_lama);

if (mysqli_num_rows($result_lama) == 0) {
    echo "<div class='alert alert-danger'>Data Kehadiran dengan ID " . htmlspecialchars($idkehadiran) . " tidak ditemukan!</div>";
    exit;
}
$data_lama = mysqli_fetch_assoc($result_lama);
mysqli_stmt_close($stmt_lama);

// Ambil data siswa untuk dropdown
$query_siswa = mysqli_query($koneksi, "SELECT idsiswa, namasiswa FROM siswa ORDER BY namasiswa ASC");
$status_options = ['Hadir', 'Izin', 'Sakit', 'Alpha'];
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-warning">
            <h3 class="card-title text-white">Edit Data Kehadiran (ID: <?= htmlspecialchars($data_lama['idkehadiran']) ?>)</h3>
        </div>

        <form action="db/dbkehadiran.php" method="post"> 
            <div class="card-body">
                <input type="hidden" name="idkehadiran" value="<?= htmlspecialchars($data_lama['idkehadiran']) ?>">
                <input type="hidden" name="idadmin" value="<?= htmlspecialchars($data_lama['idadmin']) ?>"> 
                
                <div class="form-group mb-3">
                    <label for="idsiswa" class="font-weight-bold">Nama Siswa</label>
                    <select class="form-control" id="idsiswa" name="idsiswa" required>
                        <?php while($data_siswa = mysqli_fetch_assoc($query_siswa)): ?>
                            <option value="<?= htmlspecialchars($data_siswa['idsiswa']) ?>"
                                <?= ($data_siswa['idsiswa'] == $data_lama['idsiswa']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($data_siswa['namasiswa']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="tanggal" class="font-weight-bold">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                            value="<?= htmlspecialchars($data_lama['tanggal'] ?? '') ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="statuskehadiran" class="font-weight-bold">Status Kehadiran</label>
                    <select class="form-control" id="statuskehadiran" name="statuskehadiran" required>
                        <?php foreach ($status_options as $status): ?>
                            <option value="<?= htmlspecialchars($status) ?>"
                                <?= ($status == $data_lama['statuskehadiran']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status) ?>
                            </option>
                        <?php endforeach; ?>
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