<?php
// Asumsi $koneksi sudah tersedia

// Query untuk menampilkan semua data detil kehadiran
$query = mysqli_query($koneksi, "SELECT * FROM detilkehadiran ORDER BY waktuabsen DESC");

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Detil Kehadiran (Absensi)</h3>
    </div>

    <div class="card-body">
        <div class="col">
            <a href="index.php?halaman=tambahdetilkehadiran" class="btn btn-primary float-right btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Detil Kehadiran
            </a>
        </div>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Detil</th>
                    <th>ID Absen</th>
                    <th>ID Kehadiran</th>
                    <th>Waktu Absen</th>
                    <th>Device</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                while ($data = mysqli_fetch_assoc($query)) :
                ?>
                    <tr>
                        <td><?= htmlspecialchars($data['iddetilkehadiran']); ?></td>
                        <td><?= htmlspecialchars($data['idabsen']); ?></td>
                        <td><?= htmlspecialchars($data['idkehadiran']); ?></td>
                        <td><?= htmlspecialchars($data['waktuabsen']); ?></td>
                        <td><?= htmlspecialchars($data['device']); ?></td>
                        <td>
                            <a href="index.php?halaman=editdetilkehadiran&id=<?= $data['iddetilkehadiran']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="db/dbdetilkehadiran.php?hapus=<?= $data['iddetilkehadiran']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus detil kehadiran ini?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>