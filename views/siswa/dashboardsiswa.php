<?php
// =======================================================
// Dashboard Absensi Siswa (Final - tanpa 'alpha', logika hadir diperbaiki)
// =======================================================
date_default_timezone_set('Asia/Jakarta');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'koneksi.php'; // koneksi mysqli dalam $koneksi

// ==============================
// Pastikan siswa sudah login
// ==============================
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    header("Location: index.php?halaman=loginsiswa&pesan=belumlogin");
    exit;
}

// Data siswa dari session
$idsiswa     = (int)($_SESSION['iduser'] ?? 0);
$nama        = $_SESSION['nama'] ?? 'Siswa';
$nis         = $_SESSION['nis'] ?? '-';
$kelas       = $_SESSION['kelas'] ?? '-';

$tanggal     = date('Y-m-d');
$jamSekarang = date('H:i:s');
$hariIni     = strtolower(date('l'));

// ==============================
// Ambil foto dari DB (field: fotosiswa)
// ==============================
$fotoPath = 'assets/img/avatar-default.png'; // fallback relative path

$stmtFoto = $koneksi->prepare("SELECT fotosiswa FROM siswa WHERE idsiswa=? LIMIT 1");
if ($stmtFoto) {
    $stmtFoto->bind_param('i', $idsiswa);
    $stmtFoto->execute();
    $resFoto = $stmtFoto->get_result();
    if ($resFoto && $resFoto->num_rows > 0) {
        $r = $resFoto->fetch_assoc();
        if (!empty($r['fotosiswa'])) {
            // sesuaikan path upload kalian
            $fotoPath = 'uploads/foto/' . $r['fotosiswa'];
        }
    }
}

// ==============================
// Fungsi bantu
// ==============================
function getidstatus($koneksi, $statusname)
{
    // Hapus alpha dari map
    $map = ['hadir' => 1, 'izin' => 2, 'sakit' => 3];
    $status = strtolower(trim((string)$statusname));
    if ($status === '') return 1;

    $stmt = $koneksi->prepare("SELECT id_status FROM status_absen WHERE LOWER(nama_status)=? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) return (int)$res->fetch_assoc()['id_status'];
    }

    return $map[$status] ?? 1;
}

// ===============================
// VALIDASI WAKTU ABSEN MASUK (peringatan saja, tidak blokir)
// ===============================
function cekTerlambatMasuk($jam)
{
    $awal   = strtotime('07:35:00');
    $akhir  = strtotime('08:00:00');
    $jamNow = strtotime($jam);

    if ($jamNow < $awal)  return "Belum waktunya absen masuk (mulai 07:35).";
    if ($jamNow > $akhir) return "Sudah lewat waktu absen masuk (maksimal 08:00). Anda akan dicatat Terlambat.";
    return null;
}

// ===============================
// VALIDASI WAKTU ABSEN PULANG (peringatan saja, tidak blokir)
// ===============================
function cekTerlambatPulang($jam, $hari)
{
    $jamNow = strtotime($jam);
    $hari   = strtolower((string)$hari);

    // Khusus Jumat
    if ($hari === 'friday' || $hari === 'jumat' || $hari === "jum' at" || $hari === "jum'at") {
        $awal  = strtotime('11:30:00');
        $akhir = strtotime('13:00:00');
        if ($jamNow < $awal)  return "Belum waktunya absen pulang (mulai 11:30 - Jumat).";
        if ($jamNow > $akhir) return "Waktu absen pulang sudah berakhir (maksimal 13:00 - Jumat).";
        return null;
    }

    // Hari biasa
    $awal  = strtotime('14:00:00');
    $akhir = strtotime('17:00:00');
    if ($jamNow < $awal)  return "Belum waktunya absen pulang (mulai 14:00).";
    if ($jamNow > $akhir) return "Waktu absen pulang sudah berakhir (maksimal 17:00).";
    return null;
}

// --- FUNGSI UNTUK MENDAPATKAN NAMA DEVICE ---
function getDevice()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Device';
    if (preg_match('/(android|iphone|ipad|ipod)/i', $userAgent)) return 'Mobile Device';
    if (preg_match('/(windows|mac|linux)/i', $userAgent) && preg_match('/(chrome|firefox|safari|edge)/i', $userAgent)) return 'Web Browser';
    return 'Other Device';
}

// ==============================
// Upload folder
// ==============================
$uploadDir      = __DIR__ . '/../../uploads/bukti/';
$uploadRelative = 'uploads/bukti/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// =======================================================
// ABSEN MASUK  -> INSERT absen + kehadiran + detilkehadiran
// =======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['absenmasuk'])) {

    $statusabsen = $_POST['statuskehadiran'] ?? 'hadir';
    $id_status   = getidstatus($koneksi, $statusabsen);
    $keterangan  = trim($_POST['keterangan'] ?? '');
    $file_bukti  = null;
    $device      = getDevice();

    // 1. Cek Duplikasi Absen Masuk (hari ini)
    $cek = $koneksi->prepare("SELECT idabsen FROM absen WHERE idsiswa=? AND tanggal=? LIMIT 1");
    $cek->bind_param('is', $idsiswa, $tanggal);
    $cek->execute();
    $res = $cek->get_result();
    if ($res && $res->num_rows > 0) {
        echo "<script>
            Swal.fire('Oops','Anda sudah absen masuk hari ini!','warning')
            .then(()=>{ window.location=\"index.php?halaman=dashboardsiswa\"; });
        </script>";
        return;
    }

    // 2. Validasi Izin/Sakit (wajib keterangan + bukti)
    if ($statusabsen === 'izin' || $statusabsen === 'sakit') {
        if (isset($_FILES['file_bukti']) && $_FILES['file_bukti']['error'] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES['file_bukti']['tmp_name'];
            $base = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', basename($_FILES['file_bukti']['name']));
            $targetFull = $uploadDir . $base;
            if (move_uploaded_file($tmp, $targetFull)) {
                $file_bukti = $base;
            }
        }
        if (empty($keterangan) || empty($file_bukti)) {
            echo "<script>
                Swal.fire('Lengkapi Data','Izin/Sakit wajib isi keterangan & upload bukti!','warning')
                .then(()=>{ window.location=\"index.php?halaman=dashboardsiswa\"; });
            </script>";
            return;
        }
    }

    // 3. Peringatan Waktu (tidak menghalangi simpan)
    $pesanJam = cekTerlambatMasuk($jamSekarang);
    if ($pesanJam) {
        echo "<script>Swal.fire('Peringatan Waktu', " . json_encode($pesanJam) . ", 'warning');</script>";
    }

    try {
        // =============================
        // A. INSERT ke tabel `absen`
        // =============================
        $sqlAbsen = "INSERT INTO absen 
                (idsiswa, tanggal, jammasuk, id_status, id_pola, jamkeluar, keterangan, file_bukti)
             VALUES (?,?,?,?,?,?,?,?)";

        $insertAbsen = $koneksi->prepare($sqlAbsen);
        if (!$insertAbsen) {
            throw new Exception("Prepare INSERT absen gagal: " . $koneksi->error);
        }

        $id_pola        = 1;
        $jamkeluar_null = null;   // saat absen masuk, jamkeluar masih NULL

        $insertAbsen->bind_param(
            'issiisss',
            $idsiswa,
            $tanggal,
            $jamSekarang,
            $id_status,
            $id_pola,
            $jamkeluar_null,
            $keterangan,
            $file_bukti
        );

        if (!$insertAbsen->execute()) {
            throw new Exception("DEBUG INSERT ABSEN GAGAL: " . $insertAbsen->error);
        }

        $idabsen = $koneksi->insert_id;
        if (!$idabsen) {
            throw new Exception("Gagal mendapatkan ID Absen. Error: " . $koneksi->error);
        }

        // =============================
        // B. INSERT ke tabel `kehadiran`
        //    Catatan: kita tetap menyimpan id_status yang dipilih
        //    tetapi tampilan 'Hadir' hanya akan dianggap lengkap setelah absen pulang.
        // =============================
        $idadmin_null = null;
        $insertKehadiran = $koneksi->prepare(
            "INSERT INTO kehadiran (idsiswa, idadmin, id_status, tanggal)
             VALUES (?,?,?,?)"
        );
        if (!$insertKehadiran) {
            throw new Exception("Prepare INSERT kehadiran gagal: " . $koneksi->error);
        }

        $insertKehadiran->bind_param('iiis', $idsiswa, $idadmin_null, $id_status, $tanggal);

        if (!$insertKehadiran->execute()) {
            throw new Exception("Eksekusi INSERT kehadiran gagal: " . $insertKehadiran->error);
        }

        $idkehadiran = $koneksi->insert_id;
        if (!$idkehadiran) {
            throw new Exception("Gagal mendapatkan ID Kehadiran.");
        }

        // =============================
        // C. INSERT ke `detilkehadiran` (MASUK)
        // =============================
        $fotopath_null = null;
        $waktuMasuk    = $tanggal . ' ' . $jamSekarang; // DATETIME

        $insertDetil = $koneksi->prepare(
            "INSERT INTO detilkehadiran (idabsen,idkehadiran,waktuabsen,fotopath,device)
             VALUES (?,?,?,?,?)"
        );
        if (!$insertDetil) {
            throw new Exception("Prepare INSERT detilkehadiran gagal: " . $koneksi->error);
        }

        $insertDetil->bind_param('iisss', $idabsen, $idkehadiran, $waktuMasuk, $fotopath_null, $device);

        if (!$insertDetil->execute()) {
            throw new Exception("Eksekusi INSERT detilkehadiran gagal: " . $insertDetil->error);
        }

        // Sukses
        echo "<script>
            Swal.fire('Berhasil','Absensi masuk berhasil dicatat! (Tunggu absen pulang untuk dianggap HADIR)','success')
            .then(()=>{ window.location=\"index.php?halaman=dashboardsiswa\"; });
        </script>";
        return;
    } catch (Exception $e) {
        error_log("Absen Masuk Gagal: " . $e->getMessage());
        echo "<script>
            Swal.fire('Error','Gagal menyimpan absen! " . htmlspecialchars($e->getMessage()) . "','error')
            .then(()=>{ window.location=\"index.php?halaman=dashboardsiswa\"; });
        </script>";
        return;
    }
}

// =======================================================
// ABSEN PULANG -> UPDATE absen + INSERT detilkehadiran
// =======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['absenpulang'])) {
    $jampulang = date('H:i:s');
    $device    = getDevice();

    // 1. Ambil data absen & kehadiran hari ini yang belum pulang
    $cek = $koneksi->prepare(
        "SELECT a.idabsen, k.idkehadiran, a.jammasuk, a.keterangan, a.file_bukti
         FROM absen a
         JOIN kehadiran k
           ON a.idsiswa = k.idsiswa
          AND a.tanggal = k.tanggal
         WHERE a.idsiswa = ?
           AND a.tanggal = ?
           AND (a.jamkeluar IS NULL OR a.jamkeluar = '00:00:00')
         LIMIT 1"
    );
    $cek->bind_param('is', $idsiswa, $tanggal);
    $cek->execute();
    $res = $cek->get_result();

    if (!$res || $res->num_rows === 0) {
        echo "<script>
            Swal.fire('Oops','Belum absen masuk hari ini atau sudah absen pulang!','warning')
            .then(()=>{ window.location=\"index.php?halaman=dashboardsiswa\"; });
        </script>";
        return;
    }

    $data        = $res->fetch_assoc();
    $idabsen     = (int)$data['idabsen'];
    // Ambil idkehadiran lagi (JOIN mengembalikan)
    $idkehadiran = null;
    $tmp = $koneksi->prepare(
        "SELECT k.idkehadiran FROM kehadiran k WHERE k.idsiswa=? AND k.tanggal=? LIMIT 1"
    );
    $tmp->bind_param('is', $idsiswa, $tanggal);
    $tmp->execute();
    $resTmp = $tmp->get_result();
    if ($resTmp && $resTmp->num_rows>0) {
        $idkehadiran = (int)$resTmp->fetch_assoc()['idkehadiran'];
    }

    // 2. Peringatan Waktu (Non-Blocking)
    $pesanJam = cekTerlambatPulang($jampulang, $hariIni);
    if ($pesanJam) {
        echo "<script>Swal.fire('Peringatan Waktu', " . json_encode($pesanJam) . ", 'warning');</script>";
    }

    try {
        // A. UPDATE jamkeluar di `absen`
        $updateAbsen = $koneksi->prepare("UPDATE absen SET jamkeluar=? WHERE idabsen=?");
        if (!$updateAbsen) {
            throw new Exception("Prepare UPDATE absen gagal: " . $koneksi->error);
        }

        $updateAbsen->bind_param('si', $jampulang, $idabsen);
        if (!$updateAbsen->execute() || $updateAbsen->affected_rows === 0) {
            throw new Exception("Gagal mengupdate jam pulang di Absen.");
        }

        // B. INSERT ke `detilkehadiran` (PULANG)
        $fotopath_null = null;
        $waktuPulang   = $tanggal . ' ' . $jampulang;

        $insertDetilPulang = $koneksi->prepare(
            "INSERT INTO detilkehadiran (idabsen,idkehadiran,waktuabsen,fotopath,device)
             VALUES (?,?,?,?,?)"
        );
        if (!$insertDetilPulang) {
            throw new Exception("Prepare INSERT detilkehadiran (pulang) gagal: " . $koneksi->error);
        }

        $insertDetilPulang->bind_param('iisss', $idabsen, $idkehadiran, $waktuPulang, $fotopath_null, $device);

        if (!$insertDetilPulang->execute()) {
            throw new Exception("Eksekusi INSERT detilkehadiran (pulang) gagal: " . $insertDetilPulang->error);
        }

        // Setelah pulang, kita anggap kehadiran lengkap (HADIR). 
        // Jika ingin mengubah id_status di kehadiran menjadi 'hadir' pastikan id_status hadir ada di tabel status_absen.
        // Berikut contoh update untuk memastikan status kehadiran = hadir (opsional, uncomment bila perlu):
        // $id_hadir = getidstatus($koneksi, 'hadir');
        // $upd = $koneksi->prepare(\"UPDATE kehadiran SET id_status=? WHERE idkehadiran=?\");
        // $upd->bind_param('ii', $id_hadir, $idkehadiran);
        // $upd->execute();

        echo "<script>
            Swal.fire('Berhasil','Absen pulang dicatat! Kehadiran dianggap lengkap (HADIR).','success')
            .then(()=>{ window.location=\"index.php?halaman=dashboardsiswa\"; });
        </script>";
        return;
    } catch (Exception $e) {
        error_log("Absen Pulang Gagal: " . $e->getMessage());
        echo "<script>
            Swal.fire('Error','Gagal menyimpan absen pulang! " . htmlspecialchars($e->getMessage()) . "','error')
            .then(()=>{ window.location=\"index.php?halaman=dashboardsiswa\"; });
        </script>";
        return;
    }
}

/* =======================================================================
   MULAI BAGIAN HTML + CSS + JS
   ======================================================================= */

// ==============================
// Ambil data tampilan: riwayat 8 terakhir
// ==============================
$riwayatAbsen = [];
$q = $koneksi->prepare(
    "SELECT a.tanggal, a.jammasuk, a.jamkeluar, s.nama_status
     FROM absen a
     JOIN status_absen s ON a.id_status=s.id_status
     WHERE a.idsiswa=?
     ORDER BY a.tanggal DESC
     LIMIT 8"
);
$q->bind_param('i', $idsiswa);
$q->execute();
$r = $q->get_result();
while ($row = $r->fetch_assoc()) $riwayatAbsen[] = $row;

// Status hari ini
$statusHariIni = ['jammasuk' => null, 'jamkeluar' => null, 'nama_status' => 'Belum Absen'];
$q2 = $koneksi->prepare(
    "SELECT a.jammasuk, a.jamkeluar, s.nama_status
     FROM absen a
     JOIN status_absen s ON a.id_status=s.id_status
     WHERE a.idsiswa=? AND a.tanggal=? LIMIT 1"
);
$q2->bind_param('is', $idsiswa, $tanggal);
$q2->execute();
$res2 = $q2->get_result();
if ($res2 && $res2->num_rows > 0) $statusHariIni = $res2->fetch_assoc();

// ==============================
// Data untuk chart (7 hari terakhir + ringkasan) - TANPA ALPHA
// ==============================
$statuses  = ['Hadir', 'Izin', 'Sakit']; // alpha dihapus
$startDate = date('Y-m-d', strtotime('-6 days'));
$endDate   = $tanggal;

$sql = "SELECT a.tanggal, LOWER(s.nama_status) AS status, COUNT(*) as cnt
        FROM absen a
        JOIN status_absen s ON a.id_status=s.id_status
        WHERE a.idsiswa=? AND a.tanggal BETWEEN ? AND ?
        GROUP BY a.tanggal, status";
$stmt = $koneksi->prepare($sql);
$chartDataRaw = [];
if ($stmt) {
    $stmt->bind_param('iss', $idsiswa, $startDate, $endDate);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($ro = $res->fetch_assoc()) {
        $chartDataRaw[$ro['tanggal']][strtolower($ro['status'])] = (int)$ro['cnt'];
    }
}

$labels     = [];
$barSeries  = [];
foreach ($statuses as $s) $barSeries[strtolower($s)] = [];
$pieCounts  = array_fill_keys(array_map('strtolower', $statuses), 0);

for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d M', strtotime($d));
    foreach ($statuses as $s) {
        $key = strtolower($s);
        $val = $chartDataRaw[$d][$key] ?? 0;
        $barSeries[$key][] = $val;
        $pieCounts[$key]  += $val;
    }
}

$js_labels      = json_encode($labels);
$js_bar_series  = json_encode($barSeries);
$js_pie_counts  = json_encode(array_values($pieCounts));
$js_pie_labels  = json_encode(array_map('ucfirst', array_keys($pieCounts)));
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Absensi Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --blue-dark: #002D62;
            --blue-1: #A7C7E7;
            --blue-2: #E1F0FF;
            --glass: rgba(255, 255, 255, 0.65);
        }

        * {
            box-sizing: border-box
        }

        body {
            background: linear-gradient(135deg, var(--blue-2), var(--blue-1));
            font-family: 'Poppins', sans-serif;
            color: var(--blue-dark);
            margin: 0;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 18px;
            margin-bottom: 20px;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12)
        }

        .header-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--blue-dark);
            margin: 0 0 8px 0;
            border-left: 5px solid #007bff;
            padding-left: 10px
        }

        .info-pill {
            display: inline-block;
            background: rgba(255, 255, 255, 0.9);
            color: var(--blue-dark);
            padding: 8px 12px;
            margin: 4px;
            border-radius: 999px;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #cceeff
        }

        .top-row {
            display: flex;
            gap: 18px;
            align-items: center;
            flex-wrap: wrap
        }

        .profile {
            display: flex;
            gap: 14px;
            align-items: center
        }

        .profile img {
            width: 86px;
            height: 86px;
            border-radius: 999px;
            object-fit: cover;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            border: 3px solid rgba(255, 255, 255, 0.6)
        }

        .profile-info {
            line-height: 1
        }

        .profile-info .name {
            font-weight: 800;
            font-size: 18px;
            color: var(--blue-dark)
        }

        .profile-info .meta {
            font-weight: 600;
            color: #214b69;
            margin-top: 6px
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            align-items: start
        }

        @media (max-width:920px) {
            .grid {
                grid-template-columns: 1fr
            }
        }

        select,
        textarea,
        input[type="file"] {
            width: 100%;
            border: 1px solid #a7c7e7;
            border-radius: 12px;
            padding: 10px;
            margin-top: 6px;
            background: #ffffff;
            color: var(--blue-dark);
            font-family: inherit
        }

        .btn {
            border: none;
            border-radius: 12px;
            font-weight: 700;
            padding: 12px 18px;
            cursor: pointer;
            transition: .2s
        }

        .btn-primary {
            background: linear-gradient(90deg, #007bff, #00c8ff);
            color: #fff;
            box-shadow: 0 6px 18px rgba(0, 123, 255, 0.2)
        }

        .btn-primary:hover {
            transform: translateY(-2px)
        }

        .btn-pulang {
            background: linear-gradient(90deg, #28a745, #20c997);
            color: #fff;
            box-shadow: 0 6px 18px rgba(40, 167, 69, 0.15)
        }

        .table-soft {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: 10px
        }

        .table-soft th {
            background: var(--blue-dark);
            color: #fff;
            padding: 12px;
            text-align: left;
            border: none
        }

        .table-soft td {
            padding: 10px;
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            color: #333
        }

        .table-soft tr:hover td {
            background: #e6f7ff
        }

        .status-hadir {
            color: #2b7be9;
            font-weight: 700
        }

        .status-izin {
            color: #e6a600;
            font-weight: 700
        }

        .status-sakit {
            color: #ff4c4c;
            font-weight: 700
        }

        .status-detail-text {
            color: var(--blue-dark);
            font-weight: 600
        }

        .center {
            text-align: center
        }

        .charts {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-top: 10px
        }

        .chart-card {
            flex: 1;
            min-width: 260px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05)
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="glass-card">
            <div class="top-row">
                <div class="profile">
                    <img src="<?= htmlspecialchars($fotoPath) ?>" alt="Foto Siswa">
                    <div class="profile-info">
                        <div class="name"><?= htmlspecialchars($nama) ?></div>
                        <div class="meta">NIS: <?= htmlspecialchars($nis) ?> • Kelas: <?= htmlspecialchars($kelas) ?></div>
                        <div style="margin-top:8px;">
                            <span class="info-pill">🗓️ <?= date('d F Y') ?></span>
                            <span class="info-pill" id="clock-pill">⏰ <?= date('H:i:s') ?></span>
                        </div>
                    </div>
                </div>
                <div style="flex:1"></div>
                <div class="center">
                    <div style="text-align:right">
                        <div class="header-title" style="margin-bottom:8px">✨ Dashboard Absensi Siswa</div>
                        <div style="max-width:360px">
                            <span class="info-pill">
                                Status Hari Ini:
                                <?php
                                // Tampilkan logika baru:
                                // - Jika belum absen: Belum Absen
                                // - Jika sudah absen masuk tetapi belum pulang: Menunggu Pulang (Belum Hadir)
                                // - Jika sudah absen pulang: tampilkan Nama Status (Hadir/Izin/Sakit)
                                if (empty($statusHariIni['jammasuk'])): ?>
                                    <strong>Belum Absen</strong>
                                <?php else:
                                    if (empty($statusHariIni['jamkeluar'])): ?>
                                        <strong>Menunggu Pulang (Belum Hadir)</strong>
                                    <?php else: ?>
                                        <strong><?= htmlspecialchars(ucfirst($statusHariIni['nama_status'])) ?></strong>
                                    <?php endif;
                                endif;
                                ?>
                            </span>

                            <?php if (!empty($statusHariIni['jammasuk'])): ?>
                                <div style="margin-top:6px" class="status-detail-text">
                                    Masuk: <?= htmlspecialchars($statusHariIni['jammasuk']) ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($statusHariIni['jamkeluar'])): ?>
                                <div style="margin-top:6px" class="status-detail-text">
                                    Pulang: <?= htmlspecialchars($statusHariIni['jamkeluar']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid">
            <!-- FORM ABSENSI MASUK -->
            <div class="glass-card">
                <h4 style="color:var(--blue-dark);margin-top:0">📝 Form Absensi Masuk</h4>
                <?php if (empty($statusHariIni['jammasuk'])): ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div style="margin-bottom:10px;">
                            <label style="font-weight:600;">Status Kehadiran</label>
                            <select name="statuskehadiran" id="statuskehadiran">
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                            </select>
                        </div>
                        <div id="keteranganBox" style="display:none;margin-top:10px;">
                            <label style="font-weight:600;">Keterangan</label>
                            <textarea name="keterangan" placeholder="Isi alasan izin / sakit (Wajib)" rows="3"></textarea>
                        </div>
                        <div id="fileBox" style="display:none;margin-top:10px;">
                            <label style="font-weight:600;">Upload Bukti (Surat / Foto)</label>
                            <input type="file" name="file_bukti" accept="image/*,application/pdf">
                        </div>
                        <div style="margin-top:18px;display:flex;gap:10px;align-items:center">
                            <button class="btn btn-primary" name="absenmasuk" type="submit">Absen Masuk Sekarang</button>
                            <div style="font-size:0.9em;color:#214b69;">
                                Jam Sekarang: <strong id="jam-now"><?= htmlspecialchars($jamSekarang) ?></strong>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div style="padding:14px;background:rgba(225,240,255,0.9);border-radius:12px;border:1px solid #a7c7e7;text-align:center;">
                        <strong style="font-size:1.05em;color:var(--blue-dark);">
                            ✅ Kamu sudah absen masuk hari ini
                        </strong>
                        <div style="margin-top:8px;">
                            <?php
                            // Tampilkan status round:
                            if (empty($statusHariIni['jamkeluar'])) {
                                echo '<div class="status-detail-text">Status: Menunggu Pulang (Belum Hadir)</div>';
                            } else {
                                echo '<div class="status-detail-text">Status: ' . htmlspecialchars(ucfirst($statusHariIni['nama_status'])) . '</div>';
                            }
                            ?>
                            <?php if ($statusHariIni['jammasuk']): ?>
                                <div class="status-detail-text">Masuk: <?= htmlspecialchars($statusHariIni['jammasuk']) ?></div>
                            <?php endif; ?>
                            <?php if ($statusHariIni['jamkeluar']): ?>
                                <div class="status-detail-text">Pulang: <?= htmlspecialchars($statusHariIni['jamkeluar']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RIWAYAT + ABSEN PULANG + CHART -->
            <div class="glass-card">
                <h4 style="color:var(--blue-dark);margin-top:0">📋 Riwayat 8 Absensi Terakhir</h4>

                <table class="table-soft">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($riwayatAbsen)): ?>
                            <tr>
                                <td colspan="4" style="text-align:center">
                                    Belum ada data riwayat absensi yang tercatat.
                                </td>
                            </tr>
                            <?php else: foreach ($riwayatAbsen as $r):
                                // jika pada riwayat jamkeluar kosong, tampilkan 'Menunggu Pulang'
                                $statusLabel = $r['nama_status'];
                                if (empty($r['jamkeluar'])) $statusLabel = 'Menunggu Pulang';
                                $cls = 'status-' . strtolower(preg_replace('/[^a-z]/i','', $statusLabel)); ?>
                                <tr>
                                    <td style="text-align:left"><?= date('d/M/Y', strtotime($r['tanggal'])) ?></td>
                                    <td><?= $r['jammasuk'] ?: '-' ?></td>
                                    <td><?= $r['jamkeluar'] ?: '-' ?></td>
                                    <td class="<?= $cls ?>"><?= htmlspecialchars(ucfirst($statusLabel)) ?></td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>

                <?php if (!empty($statusHariIni['jammasuk']) && empty($statusHariIni['jamkeluar'])): ?>
                    <div style="text-align:center;margin-top:18px;padding-top:12px;border-top:1px solid #a7c7e7;">
                        <form method="POST">
                            <button class="btn btn-pulang" name="absenpulang" type="submit">Absen Pulang</button>
                        </form>
                    </div>
                <?php endif; ?>

                <div style="margin-top:18px">
                    <div class="charts">
                        <div class="chart-card">
                            <strong>Ringkasan (7 hari)</strong>
                            <canvas id="pieChart" style="max-height:220px;margin-top:8px;"></canvas>
                        </div>
                        <div class="chart-card" style="flex:1.6">
                            <strong>Kehadiran 7 Hari Terakhir</strong>
                            <canvas id="barChart" style="max-height:220px;margin-top:8px;"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Live clock
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('clock-pill');
            if (clockEl) clockEl.textContent = '⏰ ' + h + ':' + m + ':' + s;
            const jamNow = h + ':' + m + ':' + s;
            const el = document.getElementById('jam-now');
            if (el) el.textContent = jamNow;
        }
        setInterval(updateClock, 1000);

        // Show/hide form izin/sakit
        (function() {
            const select = document.getElementById('statuskehadiran');
            const ketBox = document.getElementById('keteranganBox');
            const fileBox = document.getElementById('fileBox');
            if (!select) return;
            select.addEventListener('change', () => {
                const v = select.value.toLowerCase();
                const show = (v === 'izin' || v === 'sakit');
                ketBox.style.display = show ? 'block' : 'none';
                fileBox.style.display = show ? 'block' : 'none';
            });
            const init = select.value.toLowerCase();
            const showInit = (init === 'izin' || init === 'sakit');
            ketBox.style.display = showInit ? 'block' : 'none';
            fileBox.style.display = showInit ? 'block' : 'none';
        })();

        // Chart data dari PHP (tanpa alpha)
        const labels = <?= $js_labels ?>;
        const barSeries = <?= $js_bar_series ?>;
        const pieLabels = <?= $js_pie_labels ?>;
        const pieCounts = <?= $js_pie_counts ?>;

        // Pie chart
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieCounts,
                    backgroundColor: ['#2b7be9', '#e6a600', '#ff4c4c'],
                    borderColor: '#fff'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                maintainAspectRatio: false
            }
        });

        // Bar chart
        const ctxBar = document.getElementById('barChart').getContext('2d');
        const colorMap = {
            'Hadir': '#2b7be9',
            'Izin': '#e6a600',
            'Sakit': '#ff4c4c'
        };

        const datasets = [
            {
                label: 'Hadir',
                data: barSeries.hadir || [],
                stack: 'Stack 0',
                backgroundColor: colorMap.Hadir
            },
            {
                label: 'Izin',
                data: barSeries.izin || [],
                stack: 'Stack 0',
                backgroundColor: colorMap.Izin
            },
            {
                label: 'Sakit',
                data: barSeries.sakit || [],
                stack: 'Stack 0',
                backgroundColor: colorMap.Sakit
            }
        ];

        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });
    </script>

</body>

</html>
