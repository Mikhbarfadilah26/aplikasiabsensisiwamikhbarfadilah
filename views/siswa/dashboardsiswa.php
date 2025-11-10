<?php
// =======================================================
// 📂 views/siswa/dashboardsiswa.php
// Tema: Elegant Sky Blue - Glassmorphism + Interaktif
// =======================================================
date_default_timezone_set('Asia/Jakarta');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'koneksi.php';

// Pastikan siswa login
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    header("Location: index.php?halaman=loginsiswa&pesan=belumlogin");
    exit;
}

// Data siswa
$idsiswa = (int)($_SESSION['iduser'] ?? 0);
$nama    = $_SESSION['nama'] ?? 'Siswa';
$nis     = $_SESSION['nis'] ?? '-';
$kelas   = $_SESSION['kelas'] ?? '-';
$tanggal = date('Y-m-d');
$jamSekarang = date('H:i:s');
$hariIni = strtolower(date('l'));

// ==============================
// Fungsi bantu
function getidstatus($koneksi, $statusname) {
    $map = ['hadir'=>1,'izin'=>2,'sakit'=>3,'alpha'=>4];
    $status = strtolower(trim($statusname));
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

function cekTerlambatMasuk($jam) {
    $awal = strtotime('07:35:00');
    $akhir = strtotime('08:00:00');
    $jamNow = strtotime($jam);
    if ($jamNow < $awal) return "Belum waktunya absen masuk (mulai 07:35).";
    if ($jamNow > $akhir) return "Anda terlambat, jangan diulangi lagi.";
    return null;
}
function cekTerlambatPulang($jam, $hari) {
    $jamNow = strtotime($jam);
    if ($hari === 'friday' || $hari === 'jumat') {
        $awal = strtotime('11:30:00');
        if ($jamNow < $awal) return "Belum waktunya absen pulang (mulai 11:30).";
    } else {
        $awal = strtotime('14:00:00');
        if ($jamNow < $awal) return "Belum waktunya absen pulang (mulai 14:00).";
    }
    return null;
}

// ==============================
// Upload folder
$uploadDir = __DIR__ . '/../../uploads/bukti/';
$uploadRelative = 'uploads/bukti/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// ==============================
// Absen Masuk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['absenmasuk'])) {
    $statusabsen = $_POST['statuskehadiran'] ?? 'hadir';
    $id_status = getidstatus($koneksi, $statusabsen);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $file_bukti = null;

    $pesanJam = cekTerlambatMasuk($jamSekarang);
    if ($pesanJam) {
        echo "<script>Swal.fire('Peringatan', " . json_encode($pesanJam) . ", 'warning');</script>";
        if (stripos($pesanJam, 'Belum waktunya') !== false) exit;
    }

    $cek = $koneksi->prepare("SELECT idabsen FROM absen WHERE idsiswa=? AND tanggal=? LIMIT 1");
    $cek->bind_param('is', $idsiswa, $tanggal);
    $cek->execute();
    $res = $cek->get_result();
    if ($res && $res->num_rows > 0) {
        echo "<script>Swal.fire('Oops','Anda sudah absen hari ini!','warning');</script>";
    } else {
        if (($statusabsen === 'izin' || $statusabsen === 'sakit')) {
            if (isset($_FILES['file_bukti']) && $_FILES['file_bukti']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['file_bukti']['tmp_name'];
                $base = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', basename($_FILES['file_bukti']['name']));
                $targetFull = $uploadDir . $base;
                if (move_uploaded_file($tmp, $targetFull)) $file_bukti = $base;
            }
            if (empty($keterangan) || empty($file_bukti)) {
                echo "<script>Swal.fire('Lengkapi Data','Izin/Sakit wajib isi keterangan & upload bukti!','warning');</script>";
                exit;
            }
        }
        $insert = $koneksi->prepare("INSERT INTO absen (idsiswa,tanggal,jammasuk,id_status,keterangan,file_bukti,id_pola)
                                     VALUES (?,?,?,?,?,?,1)");
        $insert->bind_param('ississ', $idsiswa, $tanggal, $jamSekarang, $id_status, $keterangan, $file_bukti);
        if ($insert->execute()) {
            echo "<script>Swal.fire('Berhasil','Absensi berhasil dicatat!','success')
            .then(()=>{window.location='index.php?halaman=dashboardsiswa'});</script>";
            exit;
        } else {
            echo "<script>Swal.fire('Error','Gagal menyimpan absen!','error');</script>";
        }
    }
}

// ==============================
// Absen Pulang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['absenpulang'])) {
    $jampulang = date('H:i:s');
    $cek = $koneksi->prepare("SELECT idabsen FROM absen WHERE idsiswa=? AND tanggal=? AND jamkeluar IS NULL LIMIT 1");
    $cek->bind_param('is', $idsiswa, $tanggal);
    $cek->execute();
    $res = $cek->get_result();
    if ($res && $res->num_rows > 0) {
        $pesanJam = cekTerlambatPulang($jampulang, $hariIni);
        if ($pesanJam && stripos($pesanJam, 'Belum waktunya') !== false) {
            echo "<script>Swal.fire('Peringatan', " . json_encode($pesanJam) . ", 'warning');</script>";
            exit;
        }
        $idabsen = $res->fetch_assoc()['idabsen'];
        $update = $koneksi->prepare("UPDATE absen SET jamkeluar=? WHERE idabsen=?");
        $update->bind_param('si', $jampulang, $idabsen);
        $update->execute();
        echo "<script>Swal.fire('Berhasil','Absen pulang dicatat!','success')
        .then(()=>{window.location='index.php?halaman=dashboardsiswa'});</script>";
        exit;
    } else {
        echo "<script>Swal.fire('Oops','Belum absen masuk atau sudah absen pulang!','warning');</script>";
    }
}

// ==============================
// Ambil data tampilan
$riwayatAbsen = [];
$q = $koneksi->prepare("SELECT a.tanggal, a.jammasuk, a.jamkeluar, s.nama_status
                        FROM absen a JOIN status_absen s ON a.id_status=s.id_status
                        WHERE a.idsiswa=? ORDER BY a.tanggal DESC LIMIT 8");
$q->bind_param('i', $idsiswa);
$q->execute();
$r = $q->get_result();
while ($row = $r->fetch_assoc()) $riwayatAbsen[] = $row;

$statusHariIni = ['jammasuk'=>null, 'jamkeluar'=>null, 'nama_status'=>'Belum Absen'];
$q2 = $koneksi->prepare("SELECT a.jammasuk, a.jamkeluar, s.nama_status
                         FROM absen a JOIN status_absen s ON a.id_status=s.id_status
                         WHERE a.idsiswa=? AND a.tanggal=? LIMIT 1");
$q2->bind_param('is', $idsiswa, $tanggal);
$q2->execute();
$res2 = $q2->get_result();
if ($res2 && $res2->num_rows > 0) $statusHariIni = $res2->fetch_assoc();
?>

<!-- ======================================================= -->
<!-- ELEGANT SKY BLUE THEME -->
<!-- ======================================================= -->
<style>
body {
  background: linear-gradient(135deg, #b8e1ff, #e3f6ff);
  font-family: 'Poppins', sans-serif;
  color: #083b5f;
}
.content-wrapper {
  padding: 40px 25px;
}
.glass-card {
  background: rgba(255, 255, 255, 0.35);
  backdrop-filter: blur(12px);
  border-radius: 18px;
  border: 1px solid rgba(255, 255, 255, 0.35);
  box-shadow: 0 8px 30px rgba(0,0,0,0.1);
  padding: 25px;
  margin-bottom: 20px;
  transition: transform 0.2s;
}
.glass-card:hover {
  transform: translateY(-3px);
}
.header-title {
  font-size: 24px;
  font-weight: 700;
  color: #094a92;
  margin-bottom: 15px;
}
.info-pill {
  display: inline-block;
  background: rgba(255,255,255,0.6);
  color: #094a92;
  padding: 8px 14px;
  margin: 4px;
  border-radius: 20px;
  font-weight: 600;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
select, textarea, input[type="file"] {
  width: 100%;
  border: 1px solid #cfe0f5;
  border-radius: 10px;
  padding: 10px;
  margin-top: 6px;
  background: #fff;
}
.btn {
  border: none;
  border-radius: 10px;
  font-weight: 700;
  padding: 10px 16px;
  cursor: pointer;
  transition: 0.2s;
}
.btn-primary {
  background: linear-gradient(90deg,#2e80ff,#64b0ff);
  color: #fff;
}
.btn-primary:hover {
  background: linear-gradient(90deg,#1b6ee0,#5fa8ff);
}
.btn-pulang {
  background: linear-gradient(90deg,#0fd9a5,#3cf2b5);
  color: #fff;
}
.btn-pulang:hover {
  background: linear-gradient(90deg,#0fcf95,#2ee3a8);
}
.table-soft {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
.table-soft th {
  background: rgba(255,255,255,0.6);
  color: #094a92;
  padding: 10px;
  border-bottom: 2px solid #dbefff;
}
.table-soft td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid rgba(255,255,255,0.3);
}
.status-hadir { color: #089c6d; font-weight: 700; }
.status-izin { color: #d77a0c; font-weight: 700; }
.status-sakit { color: #d14343; font-weight: 700; }
.status-alpha { color: #777; font-weight: 700; }
@media (max-width: 800px){
  .form-row { flex-direction: column; }
}
</style>

<div class="content-wrapper">
  <div class="glass-card">
    <div class="header-title">📘 Dashboard Absensi Siswa</div>
    <div>
      <span class="info-pill">👤 <?= htmlspecialchars($nama) ?></span>
      <span class="info-pill">NIS: <?= htmlspecialchars($nis) ?></span>
      <span class="info-pill">Kelas: <?= htmlspecialchars($kelas) ?></span>
      <span class="info-pill">Tanggal: <?= date('d-m-Y') ?></span>
      <span class="info-pill" id="clock-pill">Jam: <?= date('H:i:s') ?></span>
    </div>
  </div>

  <div class="form-row" style="display:flex;gap:20px;flex-wrap:wrap;">
    <!-- Kiri: Form -->
    <div class="glass-card" style="flex:1;min-width:280px;">
      <h4 style="color:#094a92;margin-top:0;">📝 Form Absensi</h4>
      <?php if (empty($statusHariIni['jammasuk'])): ?>
        <form method="POST" enctype="multipart/form-data">
          <label>Status Kehadiran</label>
          <select name="statuskehadiran" id="statuskehadiran">
            <option value="hadir">Hadir</option>
            <option value="izin">Izin</option>
            <option value="sakit">Sakit</option>
            <option value="alpha">Alpa</option>
          </select>
          <div id="keteranganBox" style="display:none;margin-top:10px;">
            <label>Keterangan</label>
            <textarea name="keterangan" placeholder="Isi alasan izin / sakit"></textarea>
          </div>
          <div id="fileBox" style="display:none;margin-top:10px;">
            <label>Upload Bukti</label>
            <input type="file" name="file_bukti" accept="image/*">
          </div>
          <div style="margin-top:15px;">
            <button class="btn btn-primary" name="absenmasuk">Absen Masuk</button>
          </div>
        </form>
      <?php else: ?>
        <div style="padding:10px;background:#fff;border-radius:10px;">
          <strong>Status Hari Ini:</strong>
          <?= htmlspecialchars($statusHariIni['nama_status']) ?>
          <?php if ($statusHariIni['jammasuk']) echo " (Masuk: {$statusHariIni['jammasuk']})"; ?>
          <?php if ($statusHariIni['jamkeluar']) echo " (Pulang: {$statusHariIni['jamkeluar']})"; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Kanan: Riwayat -->
    <div class="glass-card" style="flex:1;min-width:300px;">
      <h4 style="color:#094a92;margin-top:0;">📊 Riwayat Absensi</h4>
      <table class="table-soft">
        <thead><tr><th>Tanggal</th><th>Masuk</th><th>Keluar</th><th>Status</th></tr></thead>
        <tbody>
        <?php if (empty($riwayatAbsen)): ?>
          <tr><td colspan="4">Belum ada data.</td></tr>
        <?php else: foreach ($riwayatAbsen as $r): 
          $cls = 'status-'.strtolower($r['nama_status']); ?>
          <tr>
            <td><?= date('d/m/Y', strtotime($r['tanggal'])) ?></td>
            <td><?= $r['jammasuk'] ?: '-' ?></td>
            <td><?= $r['jamkeluar'] ?: '-' ?></td>
            <td class="<?= $cls ?>"><?= htmlspecialchars(ucfirst($r['nama_status'])) ?></td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>

      <?php if (!empty($statusHariIni['jammasuk']) && empty($statusHariIni['jamkeluar'])): ?>
        <div style="text-align:center;margin-top:12px;">
          <form method="POST">
            <button class="btn btn-pulang" name="absenpulang">Absen Pulang</button>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
  // Live clock
  function updateClock(){
    const now = new Date();
    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');
    document.getElementById('clock-pill').textContent = 'Jam: '+h+':'+m+':'+s;
  }
  setInterval(updateClock,1000);

  // Show/hide izin/sakit fields
  const select = document.getElementById('statuskehadiran');
  const ketBox = document.getElementById('keteranganBox');
  const fileBox = document.getElementById('fileBox');
  if (select) {
    select.addEventListener('change', ()=>{
      const v = select.value.toLowerCase();
      const show = (v==='izin'||v==='sakit');
      ketBox.style.display = show?'block':'none';
      fileBox.style.display = show?'block':'none';
    });
  }
})();
</script>
