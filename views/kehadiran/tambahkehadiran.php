<?php
// Asumsi $koneksi sudah tersedia

// Ambil data siswa untuk dropdown
$query_siswa = mysqli_query($koneksi, "SELECT idsiswa, namasiswa FROM siswa ORDER BY namasiswa ASC");

$idadmin_aktif = $_SESSION['idadmin'] ?? 11; // <--- Cek nilai ini!
?>


<?php
// ... (PHP Logic) ...
?>

<section class="content">
    <form action="db/dbkehadiran.php" method="post"> 
        <div class="card-body">
            <input type="hidden" name="idadmin" value="<?= htmlspecialchars($idadmin_aktif) ?>">
            </div>
    </form>
</section>
<section class="content">
    <div class="card shadow-sm">
        </div>
</section>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title text-white">Tambah Data Kehadiran Siswa</h3>
        </div>

        <form action="db/dbkehadiran.php" method="post"> 
            <div class="card-body">
                
                <input type="hidden" name="idadmin" value="<?= htmlspecialchars($idadmin_aktif) ?>">

                <div class="form-group mb-3">
                    <label for="idsiswa" class="font-weight-bold">Nama Siswa</label>
                    <select class="form-control" id="idsiswa" name="idsiswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php while($data_siswa = mysqli_fetch_assoc($query_siswa)): ?>
                            <option value="<?= htmlspecialchars($data_siswa['idsiswa']) ?>">
                                <?= htmlspecialchars($data_siswa['namasiswa']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="tanggal" class="font-weight-bold">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                            value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="statuskehadiran" class="font-weight-bold">Status Kehadiran</label>
                    <select class="form-control" id="statuskehadiran" name="statuskehadiran" required>
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Alpha">Alpha</option>
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



