<?php
// views/absen/absen.php (Asumsi $koneksi sudah tersedia)

// Query tetap sama: Ambil data dari tabel absen, KATEGORI, dan SISWA menggunakan INNER JOIN
$query = mysqli_query($koneksi, "SELECT T1.*, T2.namakategori, T3.namasiswa
                                 FROM absen T1
                                 INNER JOIN kategori T2 ON T1.idkategori = T2.idkategori
                                 INNER JOIN siswa T3 ON T1.idsiswa = T3.idsiswa 
                                 ORDER BY T1.tanggal DESC, T1.jammasuk DESC"); // Tambahkan T1.jammasuk DESC untuk sorting

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Absen</h3>
    </div>

    <div class="card-body">
        <div class="col">
            <a href="index.php?halaman=tambahabsen" class="btn btn-primary float-right btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Absen
            </a>
        </div>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Absen</th>
                    <th>Nama Siswa</th>
                    <th>Pola Kategori</th>
                    <th>Tanggal</th>
                    <th>Jam Absen</th>
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
                        <td><?= htmlspecialchars($data['idabsen']); ?></td>
                        <td><?= htmlspecialchars($data['namasiswa']); ?></td> 
                        <td><?= htmlspecialchars($data['namakategori']); ?></td>
                        <td><?= htmlspecialchars($data['tanggal']); ?></td>
                        
                        <td>
                            <?php 
                            // Pastikan kolom 'jammasuk' dan 'jamkeluar' ada di tabel absen
                            $jamMasuk = htmlspecialchars($data['jammasuk'] ?? '-'); 
                            $jamKeluar = htmlspecialchars($data['jamkeluar'] ?? '-');
                            echo $jamMasuk . " - " . $jamKeluar; 
                            ?>
                        </td>
                        
                        <td>
                            <a href="index.php?halaman=editabsen&id=<?= $data['idabsen']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="db/dbabsen.php?hapus=<?= $data['idabsen']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus data absen tanggal <?= htmlspecialchars($data['tanggal']); ?>?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php
                endwhile;

                // Hitung total kolom (7)
                if ($no == 1) {
                    echo '<tr><td colspan="7" class="text-center">Data absen masih kosong.</td></tr>'; // Ubah colspan menjadi 7
                }
                ?>
            </tbody>
        </table>
    </div>
</div>