<?php
// views/status_absen/status_absen.php

if (!isset($koneksi)) {
    require_once 'koneksi.php';
}

// Ambil data dari tabel status_absen
$query = mysqli_query($koneksi, "SELECT * FROM status_absen ORDER BY id_status ASC");

// Cek error query
if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clipboard-list mr-1"></i> Data Status Absen</h3>
    </div>

    <div class="card-body">
        <div class="col">
            <a href="index.php?halaman=tambahstatus_absen" class="btn btn-primary float-right btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Status Absen
            </a>
        </div>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Status</th>
                    <th>Nama Status</th>
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
                        <td><?= htmlspecialchars($data['id_status']); ?></td>
                        <td><?= htmlspecialchars($data['nama_status']); ?></td>
                        <td>
                            <a href="index.php?halaman=editstatus_absen&id=<?= $data['id_status']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="db/dbstatus_absen.php?hapus=<?= $data['id_status']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus status <?= htmlspecialchars($data['nama_status']); ?>?');" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile;

                if ($no == 1): ?>
                    <tr><td colspan="4" class="text-center">Belum ada data status absen.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
