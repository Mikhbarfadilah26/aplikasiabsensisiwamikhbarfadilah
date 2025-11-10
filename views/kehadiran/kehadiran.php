<?php
// views/kehadiran/kehadiran.php

if (!isset($koneksi)) {
    require_once 'koneksi.php';
}

// ✅ Query yang benar
$query = mysqli_query($koneksi, "
    SELECT 
        k.idkehadiran, 
        s.namasiswa, 
        k.tanggal, 
        sa.nama_status
    FROM kehadiran k
    JOIN siswa s ON k.idsiswa = s.idsiswa
    JOIN status_absen sa ON k.id_status = sa.id_status
    ORDER BY k.tanggal DESC
");

// ✅ Cek error query
if (!$query) {
    die('Query Error: ' . mysqli_error($koneksi));
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-check mr-1"></i> Data Kehadiran Siswa</h3>
    </div>

    <div class="card-body">
        <div class="col">
            <a href="index.php?halaman=tambahkehadiran" class="btn btn-primary float-right btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Kehadiran
            </a>
        </div>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Kehadiran</th>
                    <th>Nama Siswa</th>
                    <th>Tanggal</th>
                    <th>Status Kehadiran</th>
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
                        <td><?= htmlspecialchars($data['idkehadiran']); ?></td>
                        <td><?= htmlspecialchars($data['namasiswa']); ?></td>
                        <td><?= htmlspecialchars($data['tanggal']); ?></td>
                        <td><?= htmlspecialchars($data['nama_status']); ?></td>
                        <td>
                            <a href="index.php?halaman=editkehadiran&id=<?= $data['idkehadiran']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="db/dbkehadiran.php?hapus=<?= $data['idkehadiran']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus data kehadiran tanggal <?= htmlspecialchars($data['tanggal']); ?>?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile;

                if ($no == 1): ?>
                    <tr><td colspan="6" class="text-center">Data kehadiran masih kosong.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
