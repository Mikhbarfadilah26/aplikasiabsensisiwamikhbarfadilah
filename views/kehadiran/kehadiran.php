<?php
// Pastikan koneksi ($koneksi) sudah terdefinisi dan terhubung ke database

// Query yang menggabungkan tabel kehadiran dan siswa untuk menampilkan nama siswa
$query = mysqli_query($koneksi, "
    SELECT 
        k.idkehadiran, 
        s.idsiswa, 
        s.namasiswa, 
        k.tanggal, 
        k.statuskehadiran
    FROM kehadiran k
    JOIN siswa s ON k.idsiswa = s.idsiswa
    ORDER BY k.tanggal DESC
");

// Cek jika ada error pada query
if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Kehadiran Siswa</h3>
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
                    <th>Status</th>
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
                        <td><?= htmlspecialchars($data['statuskehadiran']); ?></td>

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
                <?php endwhile; ?>
                <?php if ($no == 1): ?>
                    <tr><td colspan="6" class="text-center">Data kehadiran masih kosong.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>