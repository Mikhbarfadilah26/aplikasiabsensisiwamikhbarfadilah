<?php
// views/siswa/dashboardsiswa.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan path koneksi benar. Asumsi 'koneksi.php' ada di root, jadi path dari 'views/siswa' adalah '../koneksi.php'
// Jika 'koneksi.php' berada di folder yang sama, gunakan require_once 'koneksi.php';
require_once 'koneksi.php'; 

// ✅ Cek login siswa
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    // Menghapus underscore pada pesan
    header("Location: index.php?halaman=loginsiswa&pesan=belumlogin");
    exit;
}

// Data sesi siswa (Dihapus Underscore pada iduser, nama, kelas)
$idsiswa = (int)($_SESSION['iduser'] ?? 0);
$nama    = $_SESSION['nama'] ?? 'Siswa';
$nis     = $_SESSION['nis'] ?? '-';
$kelas   = $_SESSION['kelas'] ?? '-';
$foto    = $_SESSION['foto'] ?? 'dist/img/user2-160x160.jpg';

// Variabel waktu dan lingkungan (Dihapus Underscore)
$tanggal = date('Y-m-d');
$jammasuk = date('H:i:s');
$waktu = date('Y-m-d H:i:s');
$device = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];


// 📌 Fungsi untuk ambil idkategori (Dihapus Underscore)
function getidkategori($koneksi, $statusname) {
    $mapdefault = [
        'hadir' => 1,
        'izin'  => 2,
        'sakit' => 3,
        'alpha' => 4,
    ];
    $statustrim = trim(strtolower($statusname));
    if ($statustrim === '') return $mapdefault['hadir'];

    if ($koneksi instanceof mysqli) {
        $stmt = $koneksi->prepare("SELECT idkategori FROM kategori WHERE LOWER(namakategori)=? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $statustrim);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                return (int)$res->fetch_assoc()['idkategori'];
            }
        }
    }
    return $mapdefault[$statustrim] ?? $mapdefault['hadir'];
}

// ==========================================================
// 📌 PROSES ABSEN MASUK
// ==========================================================
// Menggunakan absenmasuk sebagai name tombol dan action form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['absenmasuk']))) {
    
    $statusabsen = 'hadir'; // Untuk absen masuk langsung, status default hadir
    $keterangan  = null; 

    // ✅ Cek sudah absen masuk hari ini
    $cek = $koneksi->prepare("SELECT idabsen FROM absen WHERE idsiswa=? AND tanggal=? AND jammasuk IS NOT NULL LIMIT 1");
    $cek->bind_param('is', $idsiswa, $tanggal);
    $cek->execute();
    $res = $cek->get_result();

    if ($res && $res->num_rows > 0) {
        echo "<script>Swal.fire('Oops!', 'Anda sudah Absen Masuk hari ini!', 'warning');</script>";
        // Hentikan script untuk mencegah eksekusi lebih lanjut
    } else {
        // Simpan ke tabel absen
        $idkategori = getidkategori($koneksi, $statusabsen);
        
        $insert = $koneksi->prepare("INSERT INTO absen (idsiswa, tanggal, jammasuk, idkategori, ipaddress, device) VALUES (?,?,?,?,?,?)");
        if (!$insert) {
            echo "<script>Swal.fire('Gagal!', 'Error Insert Absen: " . addslashes($koneksi->error) . "', 'error');</script>";
            exit;
        }

        // 'issssi' jika idkategori integer, atau 'isssss' jika idkategori string
        $insert->bind_param('sissis', $idsiswa, $tanggal, $jammasuk, $idkategori, $ip, $device); 

        if ($insert->execute()) {
            $idabsen = $koneksi->insert_id; 
            
            // ✅ Insert detilkehadiran
            $stmt2 = $koneksi->prepare("INSERT INTO detilkehadiran (idabsen, idsiswa, waktabsen, fotopath, device, keterangan) VALUES (?, ?, ?, NULL, ?, ?)");
            if ($stmt2) {
                $stmt2->bind_param('issss', $idabsen, $idsiswa, $waktu, $device, $keterangan);
                $stmt2->execute();
            }

            echo "<script>Swal.fire('Berhasil!', 'Absensi masuk berhasil dicatat pada {$jammasuk}!', 'success')
                  .then(()=>{window.location='index.php?halaman=dashboardsiswa'});</script>";
            exit;
        } else {
            echo "<script>Swal.fire('Gagal!', 'Presensi gagal: " . addslashes($koneksi->error) . "', 'error');</script>";
            exit;
        }
    }
}

// ==========================================================
// 📌 PROSES ABSEN PULANG
// ==========================================================
if (isset($_POST['absenpulang'])) { // absen_pulang diubah menjadi absenpulang
    
    $jampulang = date('H:i:s'); 
    
    // Cek apakah sudah absen masuk dan belum absen pulang
    $cek = $koneksi->prepare("SELECT idabsen FROM absen WHERE idsiswa = ? AND tanggal = ? AND jammasuk IS NOT NULL AND jamkeluar IS NULL LIMIT 1");
    $cek->bind_param('is', $idsiswa, $tanggal);
    $cek->execute();
    $res = $cek->get_result();
    
    if ($res && $res->num_rows > 0) {
         // Update jamkeluar (jam_pulang diubah menjadi jamkeluar)
        $update = $koneksi->prepare("
            UPDATE absen SET jamkeluar = ?, status = 'Selesai' 
            WHERE idsiswa = ? AND tanggal = ? AND jamkeluar IS NULL
        ");
        $update->bind_param("sis", $jampulang, $idsiswa, $tanggal); 
        
        if ($update->execute()) {
             echo "<script>Swal.fire('Berhasil!', 'Absensi pulang berhasil dicatat pada {$jampulang}!', 'success')
                  .then(()=>{window.location='index.php?halaman=dashboardsiswa'});</script>";
            exit;
        } else {
             echo "<script>Swal.fire('Gagal!', 'Absensi pulang gagal: " . addslashes($koneksi->error) . "', 'error');</script>";
            exit;
        }
    } else {
        echo "<script>Swal.fire('Oops!', 'Anda Belum Absen Masuk atau sudah Absen Pulang hari ini!', 'warning');</script>";
    }
}

// ==========================================================
// 📌 AMBIL DATA ABSENSI SISWA TERAKHIR
// ==========================================================
$riwayatAbsen = [];
$queryRiwayat = $koneksi->prepare("
    SELECT a.tanggal, a.jammasuk, a.jamkeluar, k.namakategori 
    FROM absen a
    JOIN kategori k ON a.idkategori = k.idkategori
    WHERE a.idsiswa = ?
    ORDER BY a.tanggal DESC LIMIT 10
");
$queryRiwayat->bind_param('s', $idsiswa);
$queryRiwayat->execute();
$resultRiwayat = $queryRiwayat->get_result();

if ($resultRiwayat) {
    while ($row = $resultRiwayat->fetch_assoc()) {
        $riwayatAbsen[] = $row;
    }
}

// Ambil status absen hari ini untuk tampilan tombol
$statusHariIni = [
    'jammasuk' => null,
    'jamkeluar' => null,
    'namakategori' => 'Belum Absen'
];
$queryStatus = $koneksi->prepare("
    SELECT jammasuk, jamkeluar, k.namakategori
    FROM absen a
    JOIN kategori k ON a.idkategori = k.idkategori
    WHERE a.idsiswa = ? AND a.tanggal = ? LIMIT 1
");
$queryStatus->bind_param('ss', $idsiswa, $tanggal);
$queryStatus->execute();
$resultStatus = $queryStatus->get_result();
if ($resultStatus && $resultStatus->num_rows > 0) {
    $statusHariIni = $resultStatus->fetch_assoc();
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Dashboard Siswa</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?= $foto ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?= $nama ?></h3>
                        <p class="text-muted text-center"><?= $kelas ?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>NIS</b> <a class="pull-right"><?= $nis ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status Absensi Hari Ini</h3>
                    </div>
                    <div class="box-body text-center">
                        <h1 id="live-clock" style="font-size: 4em; margin-bottom: 20px;"></h1>
                        <p class="text-bold"><?= date('l, d F Y') ?></p>
                        
                        <div class="alert alert-info">
                            Status: 
                            <span class="text-bold">
                                <?= $statusHariIni['namakategori'] ?>
                                <?php if ($statusHariIni['jammasuk']) echo " (Masuk: " . $statusHariIni['jammasuk'] . ")"; ?>
                                <?php if ($statusHariIni['jamkeluar']) echo " (Pulang: " . $statusHariIni['jamkeluar'] . ")"; ?>
                            </span>
                        </div>

                        <form method="post" class="form-inline">
                            <?php if (!$statusHariIni['jammasuk']): ?>
                                <button type="submit" name="absenmasuk" class="btn btn-success btn-lg"><i class="fa fa-sign-in"></i> Absen Masuk</button>
                            <?php elseif (!$statusHariIni['jamkeluar'] && $statusHariIni['namakategori'] === 'Hadir'): ?>
                                <button type="submit" name="absenpulang" class="btn btn-danger btn-lg"><i class="fa fa-sign-out"></i> Absen Pulang</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-default btn-lg" disabled>Absensi Selesai Hari Ini</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">10 Riwayat Absensi Terakhir</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($riwayatAbsen)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat absensi.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($riwayatAbsen as $row): ?>
                                    <tr>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                        <td><?= $row['jammasuk'] ?? '-' ?></td>
                                        <td><?= $row['jamkeluar'] ?? '-' ?></td>
                                        <td><span class="label label-<?= strtolower($row['namakategori']) == 'hadir' ? 'success' : 'warning' ?>"><?= $row['namakategori'] ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Panggil sekali untuk memastikan jam muncul langsung, lalu setiap detik
    updateClock();
    setInterval(updateClock, 1000);
</script>