<?php
// views/absen/absen.php

// Pastikan koneksi tersedia
if (!isset($koneksi)) {
    require_once __DIR__ . '/../../koneksi.php';
}

// Query utama
$query = mysqli_query($koneksi, "
    SELECT 
        a.*, 
        s.nama_status, 
        sw.namasiswa,
        p.nama_pola
    FROM absen a
    INNER JOIN status_absen s ON a.id_status = s.id_status
    INNER JOIN siswa sw ON a.idsiswa = sw.idsiswa 
    LEFT JOIN polajadwal p ON a.id_pola = p.idpola
    ORDER BY a.tanggal DESC, a.jammasuk DESC
");

if (!$query) {
    die('Query Error: ' . mysqli_error($koneksi));
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Data Absen Siswa</h3>
    </div>

    <div class="card-body">
        <div class="mb-3 text-right">
            <a href="index.php?halaman=tambahabsen" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Absen
            </a>
        </div>

        <table id="example1" class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Pola Jadwal</th>
                    <th>Tanggal</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Keterangan</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($data = mysqli_fetch_assoc($query)) :
                    $filePath = !empty($data['file_bukti']) ? 'foto/keterangan/' . htmlspecialchars($data['file_bukti']) : null;
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($data['namasiswa']); ?></td>
                        <td><?= htmlspecialchars($data['nama_status']); ?></td>
                        <td><?= htmlspecialchars($data['nama_pola'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($data['tanggal']); ?></td>
                        <td><?= htmlspecialchars($data['jammasuk'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($data['jamkeluar'] ?? '-'); ?></td>
                        <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                        <td class="text-center">
                            <?php if ($filePath && file_exists($filePath)) : ?>
                                <a href="<?= $filePath; ?>" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-image"></i> Lihat
                                </a>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="index.php?halaman=editabsen&id=<?= $data['idabsen']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="db/dbabsen.php?hapus=<?= $data['idabsen']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus data absen tanggal <?= htmlspecialchars($data['tanggal']); ?> atas nama <?= htmlspecialchars($data['namasiswa']); ?>?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($no == 1): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted">Belum ada data absen.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
