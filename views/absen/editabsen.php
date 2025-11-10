<?php
// views/absen/editabsen.php

if (!isset($koneksi)) {
    include "koneksi.php"; // Sesuaikan path kalau beda
}

$idabsen = $_GET['id'] ?? null;
if (!$idabsen) {
    echo "<p style='color:red;'>ID Absen tidak ditemukan!</p>";
    exit;
}

// ✅ Ambil data absen lama
$query_lama = "
    SELECT idabsen, idsiswa, id_status, id_pola, tanggal, jammasuk, jamkeluar, keterangan, file_bukti
    FROM absen 
    WHERE idabsen = ?
";
$stmt_lama = mysqli_prepare($koneksi, $query_lama);
mysqli_stmt_bind_param($stmt_lama, "i", $idabsen);
mysqli_stmt_execute($stmt_lama);
$result_lama = mysqli_stmt_get_result($stmt_lama);

if (mysqli_num_rows($result_lama) == 0) {
    echo "<p style='color:red;'>Data absen tidak ditemukan.</p>";
    exit;
}

$data_lama = mysqli_fetch_assoc($result_lama);
mysqli_stmt_close($stmt_lama);

// ✅ Ambil data siswa, status, dan pola jadwal
$sql_siswa = mysqli_query($koneksi, "SELECT idsiswa, namasiswa FROM siswa ORDER BY namasiswa ASC");
$sql_status = mysqli_query($koneksi, "SELECT id_status, nama_status FROM status_absen ORDER BY id_status ASC");
$sql_pola   = mysqli_query($koneksi, "SELECT idpola, nama_pola FROM polajadwal ORDER BY idpola ASC");
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-warning">
            <h3 class="card-title text-white">
                Edit Data Absensi Siswa (ID: <?= htmlspecialchars($data_lama['idabsen']) ?>)
            </h3>
        </div>

        <!-- ✅ Tambahkan enctype agar bisa upload file -->
        <form action="db/dbabsen.php" method="post" enctype="multipart/form-data"> 
            <div class="card-body">
                <input type="hidden" name="idabsen" value="<?= htmlspecialchars($data_lama['idabsen']) ?>">

                <!-- NAMA SISWA -->
                <div class="form-group mb-3">
                    <label for="idsiswa" class="font-weight-bold">Nama Siswa</label>
                    <select class="form-control" id="idsiswa" name="idsiswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php while ($s = mysqli_fetch_assoc($sql_siswa)) : ?>
                            <option value="<?= $s['idsiswa']; ?>" <?= ($s['idsiswa'] == $data_lama['idsiswa']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($s['namasiswa']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- STATUS ABSEN -->
                <div class="form-group mb-3">
                    <label for="id_status" class="font-weight-bold">Status Kehadiran</label>
                    <select class="form-control" id="id_status" name="id_status" required onchange="toggleUploadField()">
                        <option value="">-- Pilih Status --</option>
                        <?php while ($st = mysqli_fetch_assoc($sql_status)) : ?>
                            <option value="<?= $st['id_status']; ?>" <?= ($st['id_status'] == $data_lama['id_status']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($st['nama_status']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- POLA JADWAL -->
                <div class="form-group mb-3">
                    <label for="id_pola" class="font-weight-bold">Pola Jadwal</label>
                    <select class="form-control" id="id_pola" name="id_pola">
                        <option value="">-- Pilih Pola Jadwal --</option>
                        <?php while ($p = mysqli_fetch_assoc($sql_pola)) : ?>
                            <option value="<?= $p['idpola']; ?>" <?= ($p['idpola'] == $data_lama['id_pola']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($p['nama_pola']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- TANGGAL ABSENSI -->
                <div class="form-group mb-3">
                    <label for="tanggal" class="font-weight-bold">Tanggal Absensi</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                           value="<?= htmlspecialchars($data_lama['tanggal']); ?>" required>
                </div>

                <!-- JAM MASUK -->
                <div class="form-group mb-3">
                    <label for="jammasuk" class="font-weight-bold">Jam Masuk</label>
                    <input type="time" class="form-control" id="jammasuk" name="jammasuk" 
                           value="<?= htmlspecialchars($data_lama['jammasuk']); ?>">
                </div>

                <!-- JAM KELUAR -->
                <div class="form-group mb-3">
                    <label for="jamkeluar" class="font-weight-bold">Jam Keluar</label>
                    <input type="time" class="form-control" id="jamkeluar" name="jamkeluar" 
                           value="<?= htmlspecialchars($data_lama['jamkeluar']); ?>">
                </div>

                <!-- ✅ KETERANGAN -->
                <div class="form-group mb-3">
                    <label for="keterangan" class="font-weight-bold">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Tambahkan keterangan..."><?= htmlspecialchars($data_lama['keterangan'] ?? '') ?></textarea>
                </div>

                <!-- ✅ UPLOAD FILE BUKTI -->
                <div class="form-group mb-3" id="upload_bukti_field" style="display: none;">
                    <label for="file_bukti" class="font-weight-bold">Upload Bukti (Surat Izin / Sakit)</label><br>
                    
                    <!-- Jika ada file lama, tampilkan -->
                    <?php if (!empty($data_lama['file_bukti'])): ?>
                        <p>File saat ini: 
                            <a href="foto/keterangan/<?= htmlspecialchars($data_lama['file_bukti']); ?>" target="_blank">
                                <?= htmlspecialchars($data_lama['file_bukti']); ?>
                            </a>
                        </p>
                        <input type="hidden" name="file_bukti_lama" value="<?= htmlspecialchars($data_lama['file_bukti']); ?>">
                    <?php endif; ?>

                    <input type="file" class="form-control-file" id="file_bukti" name="file_bukti" accept=".jpg,.jpeg,.png,.pdf">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti file. Maksimal 2 MB.</small>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" name="edit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="index.php?halaman=absen" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</section>

<!-- ✅ Script agar upload hanya tampil saat izin/sakit -->
<script>
function toggleUploadField() {
    const statusSelect = document.getElementById('id_status');
    const uploadField = document.getElementById('upload_bukti_field');
    const selectedText = statusSelect.options[statusSelect.selectedIndex].text.toLowerCase();

    if (selectedText.includes('izin') || selectedText.includes('sakit')) {
        uploadField.style.display = 'block';
    } else {
        uploadField.style.display = 'none';
    }
}

// Panggil langsung saat halaman load agar field sesuai status awal
window.addEventListener('DOMContentLoaded', toggleUploadField);
</script>
