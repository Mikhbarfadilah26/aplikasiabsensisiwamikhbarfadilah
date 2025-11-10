<?php
// views/absen/tambahabsen.php

// Pastikan koneksi tersedia
if (!isset($koneksi)) {
    include 'koneksi.php'; 
}

// Ambil data siswa
$sql_siswa = mysqli_query($koneksi, "
    SELECT idsiswa, namasiswa 
    FROM siswa 
    ORDER BY namasiswa ASC
");

// Ambil data status absen (Hadir, Sakit, Izin, Alpha)
$sql_status = mysqli_query($koneksi, "
    SELECT id_status, nama_status 
    FROM status_absen 
    ORDER BY id_status ASC
");

// Ambil data pola jadwal (Pola Normal, Pola Ramadhan, dll)
$sql_pola = mysqli_query($koneksi, "
    SELECT idpola, nama_pola 
    FROM polajadwal 
    ORDER BY idpola ASC
");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary shadow-sm">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-plus-circle"></i> Tambah Data Absensi Siswa
                    </h3>
                </div>

                <!-- ✅ Tambahkan enctype untuk upload file -->
                <form action="db/dbabsen.php" method="POST" enctype="multipart/form-data"> 
                    <div class="card-body">

                        <!-- Tanggal Absensi -->
                        <div class="form-group">
                            <label for="tanggal" class="font-weight-bold">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                   required value="<?= date('Y-m-d'); ?>">
                        </div>

                        <!-- Nama Siswa -->
                        <div class="form-group">
                            <label for="idsiswa" class="font-weight-bold">Nama Siswa</label>
                            <select class="form-control" id="idsiswa" name="idsiswa" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php while ($data_siswa = mysqli_fetch_array($sql_siswa)) : ?>
                                    <option value="<?= $data_siswa['idsiswa']; ?>">
                                        <?= htmlspecialchars($data_siswa['namasiswa']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <!-- Status Kehadiran -->
                        <div class="form-group">
                            <label for="id_status" class="font-weight-bold">Status Kehadiran</label>
                            <select class="form-control" id="id_status" name="id_status" required onchange="toggleUploadField()">
                                <option value="">-- Pilih Status Kehadiran --</option>
                                <?php while ($data_status = mysqli_fetch_array($sql_status)) : ?>
                                    <option value="<?= $data_status['id_status']; ?>">
                                        <?= htmlspecialchars($data_status['nama_status']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Pola Jadwal -->
                        <div class="form-group">
                            <label for="id_pola" class="font-weight-bold">Pola Jadwal</label>
                            <select class="form-control" id="id_pola" name="id_pola">
                                <option value="">-- Pilih Pola Jadwal (Opsional) --</option>
                                <?php while ($data_pola = mysqli_fetch_array($sql_pola)) : ?>
                                    <option value="<?= $data_pola['idpola']; ?>">
                                        <?= htmlspecialchars($data_pola['nama_pola']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <!-- Jam Masuk -->
                        <div class="form-group">
                            <label for="jammasuk" class="font-weight-bold">Jam Masuk</label>
                            <input type="time" class="form-control" id="jammasuk" name="jammasuk" 
                                   value="<?= date('H:i'); ?>">
                            <small class="form-text text-muted">Jika dikosongkan, otomatis jam saat disimpan.</small>
                        </div>
                        
                        <!-- Jam Keluar -->
                        <div class="form-group">
                            <label for="jamkeluar" class="font-weight-bold">Jam Keluar</label>
                            <input type="time" class="form-control" id="jamkeluar" name="jamkeluar">
                        </div>

                        <!-- ✅ Keterangan -->
                        <div class="form-group">
                            <label for="keterangan" class="font-weight-bold">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                        </div>

                        <!-- ✅ Upload Bukti Izin/Sakit -->
                        <div class="form-group" id="upload_bukti_field" style="display: none;">
                            <label for="file_bukti" class="font-weight-bold">Upload Bukti (Surat Izin / Sakit)</label>
                            <input type="file" class="form-control-file" id="file_bukti" name="file_bukti" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="form-text text-muted">Format: JPG, PNG, atau PDF. Maksimal 2 MB.</small>
                        </div>
                        
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="index.php?halaman=absen" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- ✅ Script untuk menampilkan field upload hanya saat status = Izin atau Sakit -->
<script>
function toggleUploadField() {
    const statusSelect = document.getElementById('id_status');
    const uploadField = document.getElementById('upload_bukti_field');
    const selectedText = statusSelect.options[statusSelect.selectedIndex].text.toLowerCase();

    if (selectedText.includes('izin') || selectedText.includes('sakit')) {
        uploadField.style.display = 'block';
    } else {
        uploadField.style.display = 'none';
        document.getElementById('file_bukti').value = ''; // reset file input
    }
}
</script>
