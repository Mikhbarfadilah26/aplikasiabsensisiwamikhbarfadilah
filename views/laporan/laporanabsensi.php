<?php
// PERBAIKAN PATH KONEKSI: Jika file ini berada di /views/laporan/, kita harus mundur 2 folder ke root, lalu masuk ke /db/.
// Saya mengembalikan path seperti yang Anda gunakan di input, tapi ini mungkin menyebabkan error koneksi.
// Jika koneksi gagal, ganti lagi menjadi: include '../../db/koneksi.php'; 
include 'koneksi.php'; 

// Ambil data filter
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
$idkelas = isset($_GET['idkelas']) ? $_GET['idkelas'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Ambil parameter halaman saat ini dari URL agar tidak hilang saat filter
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 'laporanabsensi'; 
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Laporan Absensi Siswa</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="form-inline mb-3">
                    
                    <input type="hidden" name="halaman" value="<?= $halaman ?>"> 

                    <label class="mr-2">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control mr-3" value="<?= $tgl_mulai ?>">
                    
                    <label class="mr-2">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control mr-3" value="<?= $tgl_selesai ?>">

                    <label class="mr-2">Kelas</label>
                    <select name="idkelas" class="form-control mr-3">
                        <option value="">Semua Kelas</option>
                        <?php
                        if (isset($koneksi) && $koneksi) {
                            $kelas_q = mysqli_query($koneksi, "SELECT * FROM kelas");
                            if ($kelas_q) {
                                while ($k = mysqli_fetch_assoc($kelas_q)) {
                                    $selected = ($idkelas == $k['idkelas']) ? 'selected' : '';
                                    echo "<option value='{$k['idkelas']}' $selected>{$k['namakelas']}</option>";
                                }
                            }
                        }
                        ?>
                    </select>

                    <label class="mr-2">Status</label>
                    <select name="status" class="form-control mr-3">
                        <option value="">Semua</option>
                        <option value="Hadir" <?= ($status == 'Hadir') ? 'selected' : '' ?>>Hadir</option>
                        <option value="Izin" <?= ($status == 'Izin') ? 'selected' : '' ?>>Izin</option>
                        <option value="Alpa" <?= ($status == 'Alpa') ? 'selected' : '' ?>>Alpa</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="laporanabsensi.php" class="btn btn-secondary ml-2">Reset</a>
                    <a href="export_pdf_absensi.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>&idkelas=<?= $idkelas ?>&status=<?= $status ?>" target="_blank" class="btn btn-danger ml-2">Export PDF</a>
                </form>

                <div class="table-responsive">
                    <table id="absensiTable" class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status Kehadiran</th>
                                <th>Admin Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // ... (Logika query database tetap sama)
                            if (isset($koneksi) && $koneksi) {
                                $query = "SELECT s.namasiswa, k.namakelas, a.tanggal, ab.jammasuk, ab.jamkeluar, a.statuskehadiran, ad.namaadmin 
                                        FROM kehadiran a
                                        JOIN siswa s ON a.idsiswa = s.idsiswa
                                        JOIN kelas k ON s.idkelas = k.idkelas
                                        JOIN admin ad ON a.idadmin = ad.idadmin
                                        LEFT JOIN absen ab ON a.idkehadiran = ab.idabsen
                                        WHERE 1=1";

                                if ($tgl_mulai && $tgl_selesai) {
                                    $query .= " AND a.tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
                                }
                                if ($idkelas) {
                                    $query .= " AND k.idkelas = '$idkelas'";
                                }
                                if ($status) {
                                    $query .= " AND a.statuskehadiran = '$status'";
                                }

                                $result = mysqli_query($koneksi, $query);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td>{$no}</td>
                                            <td>{$row['namasiswa']}</td>
                                            <td>{$row['namakelas']}</td>
                                            <td>{$row['tanggal']}</td>
                                            <td>{$row['jammasuk']}</td>
                                            <td>{$row['jamkeluar']}</td>
                                            <td>{$row['statuskehadiran']}</td>
                                            <td>{$row['namaadmin']}</td>
                                        </tr>";
                                        $no++;
                                    }
                                } else {
                                     echo "<tr><td colspan='8'>Query Error: " . mysqli_error($koneksi) . "</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Error: Koneksi database gagal. Cek kembali path include koneksi.php.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
// TIDAK MEMANGGIL file footer.php sesuai permintaan.
?>

<script>
    $(document).ready(function() {
        $('#absensiTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 
                'csv', 
                'excel', 
                'pdf', 
                'print', 
                'colvis'
            ],
            order: [
                [3, 'desc']
            ]
        });
    });
</script>