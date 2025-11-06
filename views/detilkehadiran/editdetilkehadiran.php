<?php
// Asumsi $koneksi sudah tersedia

$iddetilkehadiran = $_GET['id'] ?? null;

if (!$iddetilkehadiran) {
    echo "<div class='alert alert-danger'>ID Detil Kehadiran tidak ditemukan!</div>";
    exit;
}

// 1. Ambil Data Lama
$query_lama = mysqli_query($koneksi, "SELECT * FROM detilkehadiran WHERE iddetilkehadiran = '$iddetilkehadiran'");
if (mysqli_num_rows($query_lama) == 0) {
    echo "<div class='alert alert-danger'>Data Detil Kehadiran tidak ditemukan!</div>";
    exit;
}
$data_lama = mysqli_fetch_assoc($query_lama);

// 2. Ambil Pilihan Data
$query_absen = mysqli_query($koneksi, "SELECT idabsen FROM absen ORDER BY idabsen DESC");
$query_kehadiran = mysqli_query($koneksi, "SELECT idkehadiran FROM kehadiran ORDER BY idkehadiran DESC");
$device_options = ['Mobile iOS', 'Mobile Android', 'Web Browser'];

// Format waktuabsen agar kompatibel dengan input type="datetime-local"
$waktuabsen_formatted = date('Y-m-d\TH:i', strtotime($data_lama['waktuabsen']));
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-warning">
            <h3 class="card-title text-white">Edit Detil Kehadiran (ID: <?= htmlspecialchars($data_lama['iddetilkehadiran']) ?>)</h3>
        </div>

        <form action="db/dbdetilkehadiran.php" method="post"> 
            <div class="card-body">
                <input type="hidden" name="iddetilkehadiran" value="<?= htmlspecialchars($data_lama['iddetilkehadiran']) ?>">
                
                <div class="form-group mb-3">
                    <label for="idabsen" class="font-weight-bold">ID Absen</label>
                    <select class="form-control" id="idabsen" name="idabsen" required>
                        <?php 
                        mysqli_data_seek($query_absen, 0); // Reset pointer
                        while($data_absen = mysqli_fetch_assoc($query_absen)): ?>
                            <option value="<?= htmlspecialchars($data_absen['idabsen']) ?>"
                                <?= ($data_absen['idabsen'] == $data_lama['idabsen']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($data_absen['idabsen']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="idkehadiran" class="font-weight-bold">ID Kehadiran</label>
                    <select class="form-control" id="idkehadiran" name="idkehadiran" required>
                        <?php 
                        mysqli_data_seek($query_kehadiran, 0); // Reset pointer
                        while($data_kehadiran = mysqli_fetch_assoc($query_kehadiran)): ?>
                            <option value="<?= htmlspecialchars($data_kehadiran['idkehadiran']) ?>"
                                <?= ($data_kehadiran['idkehadiran'] == $data_lama['idkehadiran']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($data_kehadiran['idkehadiran']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="waktuabsen" class="font-weight-bold">Waktu Absen</label>
                    <input type="datetime-local" class="form-control" id="waktuabsen" name="waktuabsen" 
                            value="<?= htmlspecialchars($waktuabsen_formatted) ?>" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="fotopath" class="font-weight-bold">Foto Path (Simulasi)</label>
                    <input type="text" class="form-control" id="fotopath" name="fotopath" 
                            value="<?= htmlspecialchars($data_lama['fotopath'] ?? '') ?>" required>
                </div>

                <div class="form-group mb-4">
                    <label for="device" class="font-weight-bold">Device</label>
                    <select class="form-control" id="device" name="device" required>
                        <?php foreach ($device_options as $device): ?>
                            <option value="<?= htmlspecialchars($device) ?>"
                                <?= ($device == $data_lama['device']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($device) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="card-footer text-right">
                <button type="submit" name="edit" class="btn btn-warning"> 
                    <i class="fas fa-edit"></i> Simpan Perubahan
                </button>
                <a href="index.php?halaman=detilkehadiran" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</section>