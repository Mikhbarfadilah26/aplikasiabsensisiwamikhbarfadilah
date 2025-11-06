<?php
// Asumsi $koneksi sudah tersedia

// Ambil data ID Absen (dari tabel 'absen') untuk dropdown
$query_absen = mysqli_query($koneksi, "SELECT idabsen FROM absen ORDER BY idabsen DESC");
// Ambil data ID Kehadiran (dari tabel 'kehadiran') untuk dropdown
$query_kehadiran = mysqli_query($koneksi, "SELECT idkehadiran FROM kehadiran ORDER BY idkehadiran DESC");

$device_options = ['Mobile iOS', 'Mobile Android', 'Web Browser'];
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title text-white">Tambah Detil Kehadiran</h3>
        </div>

        <form action="db/dbdetilkehadiran.php" method="post"> 
            <div class="card-body">
                
                <div class="form-group mb-3">
                    <label for="idabsen" class="font-weight-bold">ID Absen</label>
                    <select class="form-control" id="idabsen" name="idabsen" required>
                        <option value="">-- Pilih ID Absen --</option>
                        <?php while($data_absen = mysqli_fetch_assoc($query_absen)): ?>
                            <option value="<?= htmlspecialchars($data_absen['idabsen']) ?>">
                                <?= htmlspecialchars($data_absen['idabsen']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="idkehadiran" class="font-weight-bold">ID Kehadiran</label>
                    <select class="form-control" id="idkehadiran" name="idkehadiran" required>
                        <option value="">-- Pilih ID Kehadiran --</option>
                        <?php while($data_kehadiran = mysqli_fetch_assoc($query_kehadiran)): ?>
                            <option value="<?= htmlspecialchars($data_kehadiran['idkehadiran']) ?>">
                                <?= htmlspecialchars($data_kehadiran['idkehadiran']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="waktuabsen" class="font-weight-bold">Waktu Absen</label>
                    <input type="datetime-local" class="form-control" id="waktuabsen" name="waktuabsen" 
                            value="<?= date('Y-m-d\TH:i') ?>" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="fotopath" class="font-weight-bold">Foto Path (Simulasi)</label>
                    <input type="text" class="form-control" id="fotopath" name="fotopath" 
                            placeholder="Contoh: path/to/foto/filename.jpg" required>
                </div>

                <div class="form-group mb-4">
                    <label for="device" class="font-weight-bold">Device</label>
                    <select class="form-control" id="device" name="device" required>
                        <?php foreach ($device_options as $device): ?>
                            <option value="<?= htmlspecialchars($device) ?>">
                                <?= htmlspecialchars($device) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="card-footer text-right">
                <button type="submit" name="simpan" class="btn btn-primary"> 
                    <i class="fas fa-save"></i> Simpan Detil Data
                </button>
                <a href="index.php?halaman=detilkehadiran" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</section>