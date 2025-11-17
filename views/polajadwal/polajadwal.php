<?php
// views/polajadwal/polajadwal.php

if (!isset($koneksi)) {
    require_once 'koneksi.php';
}

// Ambil semua data pola jadwal
$query = mysqli_query($koneksi, "SELECT * FROM polajadwal ORDER BY idpola ASC");

// Cek error query
if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clock mr-1"></i> Data Pola Jadwal/kategori</h3>
    </div>

    <div class="card-body">
        <div class="col">
            <a href="index.php?halaman=tambahpolajadwal" class="btn btn-primary float-right btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Pola Jadwal
            </a>
        </div>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Pola</th>
                    <th>Nama Pola</th>
                    <th>Jam Masuk Ideal</th>
                    <th>Jam Pulang Ideal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;
                while ($data = mysqli_fetch_assoc($query)) :
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($data['idpola']); ?></td>
                        <td><?= htmlspecialchars($data['nama_pola']); ?></td>
                        <td><?= htmlspecialchars($data['jam_masuk_ideal']); ?></td>
                        <td><?= htmlspecialchars($data['jam_pulang_ideal']); ?></td>
                        <td>
                            <a href="index.php?halaman=editpolajadwal&id=<?= $data['idpola']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="db/dbpolajadwal.php?hapus=<?= $data['idpola']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus pola <?= htmlspecialchars($data['nama_pola']); ?>?');" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile;

                if ($no == 1): ?>
                    <tr><td colspan="6" class="text-center">Belum ada data pola jadwal.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
