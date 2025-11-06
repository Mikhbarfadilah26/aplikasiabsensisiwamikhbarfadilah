<?php
// views/absen/tambahabsen.php

// Pastikan koneksi ($koneksi) sudah terdefinisi
if (!isset($koneksi)) {
    // Sesuaikan path ke koneksi.php jika perlu
    include 'koneksi.php'; 
}

// Query untuk mengambil data siswa
$sql_siswa = mysqli_query($koneksi, "SELECT idsiswa, namasiswa FROM siswa ORDER BY namasiswa ASC");
// Query untuk mengambil data kategori (Tetap sama)
$sql_kategori = mysqli_query($koneksi, "SELECT idkategori, namakategori FROM kategori ORDER BY idkategori ASC");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Data Absen</h3>
                </div>
                <form action="db/dbabsen.php" method="POST"> 
                    <div class="card-body">

                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required value="<?= date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="idsiswa">Nama Siswa</label>
                            <select class="form-control" id="idsiswa" name="idsiswa" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php
                                while ($data_siswa = mysqli_fetch_array($sql_siswa)) {
                                    echo '<option value="' . $data_siswa['idsiswa'] . '">' . htmlspecialchars($data_siswa['namasiswa']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="idkategori">Pola Absensi Harian</label>
                            <select class="form-control" id="idkategori" name="idkategori" required>
                                <option value="">-- Pilih Pola Absensi --</option>
                                <?php
                                while ($data_kat = mysqli_fetch_array($sql_kategori)) {
                                    echo '<option value="' . $data_kat['idkategori'] . '">' . htmlspecialchars($data_kat['namakategori']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="jammasuk">Jam Masuk (Opsional)</label>
                            <input type="time" class="form-control" id="jammasuk" name="jammasuk" value="<?= date('H:i'); ?>">
                            <small class="form-text text-muted">Akan diisi otomatis waktu saat ini jika dikosongkan.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="jamkeluar">Jam Keluar (Opsional)</label>
                            <input type="time" class="form-control" id="jamkeluar" name="jamkeluar">
                        </div>
                        
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        <a href="index.php?halaman=absen" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>