<?php
// views/absen/editabsen.php
// ... (Bagian atas kode tetap sama: mengambil $data_lama dan data siswa/kategori)

// PASTIKAN $koneksi sudah tersedia dari index.php
$idabsen = $_GET['id'] ?? null;
if (!$idabsen) { exit; }

$query_lama = "SELECT idabsen, idsiswa, idkategori, tanggal, jammasuk, jamkeluar FROM absen WHERE idabsen = ?";
$stmt_lama = mysqli_prepare($koneksi, $query_lama);
mysqli_stmt_bind_param($stmt_lama, "i", $idabsen); 
mysqli_stmt_execute($stmt_lama);
$result_lama = mysqli_stmt_get_result($stmt_lama);

if (mysqli_num_rows($result_lama) == 0) { exit; }
$data_lama = mysqli_fetch_assoc($result_lama);
mysqli_stmt_close($stmt_lama);

$sql_siswa = mysqli_query($koneksi, "SELECT idsiswa, namasiswa FROM siswa ORDER BY namasiswa ASC");
$sql_kategori = mysqli_query($koneksi, "SELECT idkategori, namakategori FROM kategori ORDER BY idkategori ASC");
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-warning">
            <h3 class="card-title text-white">Edit Data Absensi Siswa (ID: <?= htmlspecialchars($data_lama['idabsen']) ?>)</h3>
        </div>

        <form action="db/dbabsen.php" method="post"> 
        
            <div class="card-body">
                <input type="hidden" name="idabsen" value="<?= htmlspecialchars($data_lama['idabsen']) ?>">
                
                <div class="form-group mb-3">
                    <label for="idsiswa" class="font-weight-bold">Nama Siswa</label>
                    <select class="form-control" id="idsiswa" name="idsiswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php
                        while ($data_siswa = mysqli_fetch_array($sql_siswa)) {
                            $selected = ($data_siswa['idsiswa'] == $data_lama['idsiswa']) ? 'selected' : '';
                            echo '<option value="' . $data_siswa['idsiswa'] . '" ' . $selected . '>' . htmlspecialchars($data_siswa['namasiswa']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="idkategori" class="font-weight-bold">Pola Kategori</label>
                    <select class="form-control" id="idkategori" name="idkategori" required>
                        <option value="">-- Pilih Pola Absensi --</option>
                        <?php
                        while ($data_kat = mysqli_fetch_array($sql_kategori)) {
                            $selected = ($data_kat['idkategori'] == $data_lama['idkategori']) ? 'selected' : '';
                            echo '<option value="' . $data_kat['idkategori'] . '" ' . $selected . '>' . htmlspecialchars($data_kat['namakategori']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="tanggal" class="font-weight-bold">Tanggal Absensi</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                            value="<?= htmlspecialchars($data_lama['tanggal'] ?? '') ?>" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="jammasuk" class="font-weight-bold">Jam Masuk</label>
                    <input type="time" class="form-control" id="jammasuk" name="jammasuk" 
                            value="<?= htmlspecialchars($data_lama['jammasuk'] ?? '') ?>">
                </div>
                
                <div class="form-group mb-3">
                    <label for="jamkeluar" class="font-weight-bold">Jam Keluar</label>
                    <input type="time" class="form-control" id="jamkeluar" name="jamkeluar" 
                            value="<?= htmlspecialchars($data_lama['jamkeluar'] ?? '') ?>">
                </div>
                
                </div>
            
            <div class="card-footer text-right">
                <button type="submit" name="edit" class="btn btn-warning"> 
                    <i class="fas fa-edit"></i> Simpan Perubahan
                </button>
                <a href="index.php?halaman=absen" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

        </form> </div>
</section>