<?php
include "koneksi.php";

// Validasi ID dari URL
$idpola = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idpola > 0) {
    $query = mysqli_query($koneksi, "SELECT * FROM polajadwal WHERE idpola = $idpola");
    $data = mysqli_fetch_assoc($query);
} else {
    $data = null;
}
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h3 class="card-title">Edit Pola Jadwal</h3>
        </div>

        <?php if (!$data): ?>
            <div class="card-body text-center">
                <div class="alert alert-danger">
                    <strong>Data tidak ditemukan!</strong><br>
                    Pastikan URL berisi parameter <code>?halaman=editpolajadwal&id=[idpola]</code> yang valid.
                </div>
                <a href="index.php?halaman=polajadwal" class="btn btn-secondary">Kembali</a>
            </div>
        <?php else: ?>
            <form action="db/dbpolajadwal.php?proses=edit" method="POST">
                <div class="card-body">
                    <input type="hidden" name="idpola" value="<?= htmlspecialchars($data['idpola']); ?>">

                    <div class="form-group mb-3">
                        <label>Nama Pola</label>
                        <input type="text" name="nama_pola" 
                            value="<?= htmlspecialchars($data['nama_pola']); ?>" 
                            class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Jam Masuk Ideal</label>
                        <input type="time" name="jam_masuk_ideal" 
                            value="<?= htmlspecialchars($data['jam_masuk_ideal']); ?>" 
                            class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Jam Pulang Ideal</label>
                        <input type="time" name="jam_pulang_ideal" 
                            value="<?= htmlspecialchars($data['jam_pulang_ideal']); ?>" 
                            class="form-control" required>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php?halaman=polajadwal" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>
