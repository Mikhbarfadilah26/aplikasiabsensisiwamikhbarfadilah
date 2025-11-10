<?php
// views/laporan/laporanabsensi.php
include 'koneksi.php';

// Ambil data filter
$tgl_mulai   = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$idkelas     = $_GET['idkelas'] ?? '';
$status      = $_GET['status'] ?? '';

$halaman = $_GET['halaman'] ?? 'laporanabsensi';
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
                        $kelas_q = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY namakelas ASC");
                        while ($k = mysqli_fetch_assoc($kelas_q)) {
                            $selected = ($idkelas == $k['idkelas']) ? 'selected' : '';
                            echo "<option value='{$k['idkelas']}' $selected>{$k['namakelas']}</option>";
                        }
                        ?>
                    </select>

                    <label class="mr-2">Status</label>
                    <select name="status" class="form-control mr-3">
                        <option value="">Semua</option>
                        <?php
                        $status_q = mysqli_query($koneksi, "SELECT * FROM status_absen ORDER BY id_status ASC");
                        while ($s = mysqli_fetch_assoc($status_q)) {
                            $selected = ($status == $s['id_status']) ? 'selected' : '';
                            echo "<option value='{$s['id_status']}' $selected>{$s['nama_status']}</option>";
                        }
                        ?>
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="index.php?halaman=laporanabsensi" class="btn btn-secondary ml-2">Reset</a>

                    <!-- Tombol Export PDF -->
                    <a href="views/laporan/export_pdf_absensi.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>&idkelas=<?= $idkelas ?>&status=<?= $status ?>"
                       target="_blank" class="btn btn-danger ml-2">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>

                    <!-- Tombol Export Excel -->
                    <a href="views/laporan/export_excel_absensi.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>&idkelas=<?= $idkelas ?>&status=<?= $status ?>"
                       target="_blank" class="btn btn-success ml-2">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>

                    <!-- ✅ Tombol Export CSV (hijau tua terang) -->
                    <a href="views/laporan/export_csv_absensi.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>&idkelas=<?= $idkelas ?>&status=<?= $status ?>"
                       target="_blank" class="btn btn-success ml-2" style="background-color:#198754; border-color:#146c43;">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>

                    <!-- ✅ Tombol Print -->
                    <a href="views/laporan/print_absensi.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>&idkelas=<?= $idkelas ?>&status=<?= $status ?>"
                       target="_blank" class="btn btn-info ml-2">
                        <i class="fas fa-print"></i> Print
                    </a>

                    <!-- ✅ Tombol Copy -->
                    <button type="button" id="copyTable" class="btn btn-warning ml-2">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </form>

                <div class="table-responsive mt-3">
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

                            $query = "
                            SELECT 
                                s.namasiswa, 
                                k.namakelas, 
                                a.tanggal, 
                                ab.jammasuk, 
                                ab.jamkeluar, 
                                sa.nama_status AS status_kehadiran,
                                ad.namaadmin 
                            FROM kehadiran a
                            JOIN siswa s ON a.idsiswa = s.idsiswa
                            JOIN kelas k ON s.idkelas = k.idkelas
                            LEFT JOIN admin ad ON a.idadmin = ad.idadmin
                            LEFT JOIN absen ab ON a.idkehadiran = ab.idabsen
                            JOIN status_absen sa ON a.id_status = sa.id_status
                            WHERE 1=1
                            ";

                            if ($tgl_mulai && $tgl_selesai) {
                                $query .= " AND a.tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
                            }
                            if ($idkelas) {
                                $query .= " AND k.idkelas = '$idkelas'";
                            }
                            if ($status) {
                                $query .= " AND a.id_status = '$status'";
                            }

                            $query .= " ORDER BY a.tanggal DESC";
                            $result = mysqli_query($koneksi, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $jamMasuk = $row['jammasuk'] ?? '-';
                                    $jamKeluar = $row['jamkeluar'] ?? '-';
                                    $namaAdmin = $row['namaadmin'] ?? '-';
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['namasiswa']) ?></td>
                                        <td><?= htmlspecialchars($row['namakelas']) ?></td>
                                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                        <td><?= htmlspecialchars($jamMasuk) ?></td>
                                        <td><?= htmlspecialchars($jamKeluar) ?></td>
                                        <td><?= htmlspecialchars($row['status_kehadiran']) ?></td>
                                        <td><?= htmlspecialchars($namaAdmin) ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>Data tidak ditemukan</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#absensiTable').DataTable({
        order: [[3, 'desc']]
    });

    // ✅ Fungsi Tombol Copy
    $('#copyTable').on('click', function() {
        let tableText = '';
        $('#absensiTable tr').each(function() {
            $(this).find('th, td').each(function() {
                tableText += $(this).text().trim() + '\t';
            });
            tableText += '\n';
        });

        navigator.clipboard.writeText(tableText).then(function() {
            alert('✅ Data tabel berhasil disalin ke clipboard!');
        }, function(err) {
            alert('❌ Gagal menyalin data: ' + err);
        });
    });
});
</script>
