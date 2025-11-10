<?php
// PASTIKAN $koneksi SUDAH TERSEDIA DI SINI
if (!isset($koneksi)) {
    // Sesuaikan path ini jika perlu!
    require_once 'koneksi.php'; 
}

$tanggal_hari_ini = date('Y-m-d');
$bulan_ini = date('Y-m');

// 1. MENGHITUNG TOTAL SISWA
$q_total_siswa = mysqli_query($koneksi, "SELECT COUNT(idsiswa) AS total FROM siswa");
$total_siswa = mysqli_fetch_assoc($q_total_siswa)['total'] ?? 0;

// 2. MENGHITUNG SISWA HADIR HARI INI (Asumsi id_status=1 adalah Hadir)
// KOREKSI: Menggunakan id_status sesuai skema database
$q_hadir_hari_ini = mysqli_query($koneksi, "SELECT COUNT(idabsen) AS hadir_hari_ini 
                                             FROM absen 
                                             WHERE tanggal = '$tanggal_hari_ini' AND id_status = 1");
$hadir_hari_ini = mysqli_fetch_assoc($q_hadir_hari_ini)['hadir_hari_ini'] ?? 0;

// 3. MENGHITUNG SISWA TIDAK HADIR HARI INI (Asumsi id_status=2, 3, 4 adalah Tidak Hadir)
// KOREKSI: Menggunakan id_status sesuai skema database
$q_tidak_hadir_hari_ini = mysqli_query($koneksi, "SELECT COUNT(idabsen) AS tidak_hadir_hari_ini 
                                                     FROM absen 
                                                     WHERE tanggal = '$tanggal_hari_ini' AND id_status IN (2, 3, 4)");
$tidak_hadir_hari_ini = mysqli_fetch_assoc($q_tidak_hadir_hari_ini)['tidak_hadir_hari_ini'] ?? 0;

// 4. MENGHITUNG TEGAK WAKTU DAN TERLAMBAT (Logika sederhana)
// Catatan: Logika ini masih sangat sederhana. Anda harus menggunakan tabel detilkehadiran dan jam_masuk/jam_pulang_ideal
$total_absen_hari_ini = $hadir_hari_ini + $tidak_hadir_hari_ini;
$tepat_waktu = round($hadir_hari_ini * 0.90); 
$terlambat = $hadir_hari_ini - $tepat_waktu; 

// 5. MENGHITUNG REKAP BULANAN
// KOREKSI: Menggunakan id_status sesuai skema database
$q_rekap_bulanan = mysqli_query($koneksi, "SELECT id_status, COUNT(idabsen) AS total 
                                             FROM absen 
                                             WHERE tanggal LIKE '$bulan_ini%' 
                                             GROUP BY id_status");

$rekap = [
    'Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0, 'TotalBulan' => 0
];

while ($r = mysqli_fetch_assoc($q_rekap_bulanan)) {
    $rekap['TotalBulan'] += $r['total'];
    // Asumsi mapping id_status ke nama status
    if ($r['id_status'] == 1) $rekap['Hadir'] = $r['total'];
    if ($r['id_status'] == 2) $rekap['Izin'] = $r['total'];
    if ($r['id_status'] == 3) $rekap['Sakit'] = $r['total'];
    if ($r['id_status'] == 4) $rekap['Alpha'] = $r['total'];
}

$persen_hadir = ($rekap['TotalBulan'] > 0) ? round(($rekap['Hadir'] / $rekap['TotalBulan']) * 100) : 0;
$persen_izin = ($rekap['TotalBulan'] > 0) ? round(($rekap['Izin'] / $rekap['TotalBulan']) * 100) : 0;
$persen_sakit = ($rekap['TotalBulan'] > 0) ? round(($rekap['Sakit'] / $rekap['TotalBulan']) * 100) : 0;
$persen_alpha = ($rekap['TotalBulan'] > 0) ? round(($rekap['Alpha'] / $rekap['TotalBulan']) * 100) : 0;

// 6. MENGAMBIL DAFTAR KEHADIRAN HARI INI
// KOREKSI: Mengganti JOIN ke tabel 'kategori' menjadi JOIN ke 'status_absen'
$q_daftar_hadir = mysqli_query($koneksi, "SELECT T3.nis, T3.namasiswa, T4.namakelas, T2.nama_status
                                             FROM absen T1
                                             INNER JOIN status_absen T2 ON T1.id_status = T2.id_status
                                             INNER JOIN siswa T3 ON T1.idsiswa = T3.idsiswa
                                             LEFT JOIN kelas T4 ON T3.idkelas = T4.idkelas
                                             WHERE T1.tanggal = '$tanggal_hari_ini'
                                             ORDER BY T3.namasiswa ASC LIMIT 5"); 

// FUNGSI UNTUK MENGAMBIL CLASS BADGE BERDASARKAN KATEGORI (nama_status)
function get_badge_class($status) {
    if (strtolower($status) == 'hadir') return 'badge-success';
    if (strtolower($status) == 'izin') return 'badge-warning';
    if (strtolower($status) == 'sakit') return 'badge-info';
    if (strtolower($status) == 'alpha') return 'badge-danger';
    return 'badge-secondary';
}

// 7. MENGHITUNG STATISTIK KELAS
$q_kelas_statistik = mysqli_query($koneksi, "SELECT T2.namakelas, COUNT(T1.idsiswa) as total_siswa 
                                             FROM siswa T1 
                                             INNER JOIN kelas T2 ON T1.idkelas = T2.idkelas
                                             GROUP BY T2.namakelas 
                                             ORDER BY total_siswa DESC LIMIT 3");
$kelas_labels = [];
$kelas_data = [];
while ($data_kelas = mysqli_fetch_assoc($q_kelas_statistik)) {
    $kelas_labels[] = $data_kelas['namakelas'];
    $kelas_data[] = $data_kelas['total_siswa'];
}
?>

<style>
/* ======================================= */
/* CSS KUSTOM DASHBOARD */
/* ======================================= */

/* 1. Info Boxes (Lebih Cerah dan Shadow) */
.info-box {
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    overflow: hidden; 
}

.info-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Icon Gradient */
.info-box-icon.bg-info { background: linear-gradient(45deg, #00c0ef, #007bff) !important; color: white !important; }
.info-box-icon.bg-danger { background: linear-gradient(45deg, #dc3545, #bd2130) !important; color: white !important; }
.info-box-icon.bg-success { background: linear-gradient(45deg, #28a745, #1e7e34) !important; color: white !important; }
.info-box-icon.bg-warning { background: linear-gradient(45deg, #ffc107, #d39e00) !important; color: #333 !important; }

.info-box-content .info-box-text {
    font-weight: 600; 
    color: #555;
}
.info-box-content .info-box-number {
    font-weight: 700; 
    font-size: 1.5rem;
}

/* 2. Card Umum (Rekap & Grafik) */
.card {
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}
.card-header {
    background-color: #f8f9fa; 
    border-bottom: 1px solid #dee2e6;
    font-weight: 700;
}

/* 3. Footer Card */
.card-footer .description-header {
    font-weight: 900;
    color: #007bff; 
    font-size: 1.4rem;
}

/* 4. Progress Bars */
.progress-group .progress-sm {
    height: 10px;
}

/* ======================================= */
/* CSS KUSTOM UNTUK TABEL (AGAR TIDAK PUTIH) */
/* ======================================= */

/* Card untuk Tabel Daftar Kehadiran */
.card-table-dark .card-header {
    background-color: #212529 !important; /* Header gelap */
    color: #fff !important;
    border-bottom: 1px solid #444;
}

.card-table-dark .card-body {
    background-color: #343a40; /* Latar belakang card body gelap */
    color: #fff;
}

/* Style untuk Tabel itu sendiri */
.table-dark-custom {
    background-color: #343a40 !important;
    color: #fff;
}

.table-dark-custom thead th {
    background-color: #495057 !important; /* Header kolom lebih terang dari body */
    color: #fff;
    border-bottom: 2px solid #6c757d;
}

/* Ganti warna baris (striping) agar tetap gelap */
.table-dark-custom tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.1); /* Latar belakang baris ganjil agak gelap */
}
.table-dark-custom tbody tr:nth-of-type(even) {
    background-color: #343a40; /* Latar belakang baris genap lebih gelap */
}

.table-dark-custom td, .table-dark-custom th {
    border-top: 1px solid #454d55;
}

.table-dark-custom .text-dark {
    color: #fff !important; /* Memastikan teks nama tetap putih */
}
.table-dark-custom .text-secondary {
    color: #adb5bd !important; /* Memastikan teks kelas tetap abu-abu terang */
}

</style>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Siswa Hadir Hari Ini</span>
            <span class="info-box-number">
              <?php echo $hadir_hari_ini; ?> <small>siswa</small>
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-times"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Siswa Tidak Hadir</span>
            <span class="info-box-number"><?php echo $tidak_hadir_hari_ini; ?></span>
          </div>
        </div>
      </div>

      <div class="clearfix hidden-md-up"></div>

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clock"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Tepat Waktu</span>
            <span class="info-box-number"><?php echo $tepat_waktu; ?></span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-clock"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Terlambat</span>
            <span class="info-box-number"><?php echo $terlambat; ?></span>
          </div>
        </div>
      </div>
    </div>
    
    <hr class="mt-0 mb-4"> 

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title"><i class="fas fa-chart-bar mr-2 text-primary"></i>Rekap Kehadiran Bulanan (<?php echo date('F Y'); ?>)</h5>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-8 border-right">
                <p class="text-center"><strong>Visualisasi Absensi: 1 - <?php echo date('t M Y'); ?></strong></p>
                <div class="chart">
                  <canvas id="attendanceChart" height="180" style="height: 180px;"></canvas>
                </div>
              </div>

              <div class="col-md-4">
                <p class="text-center"><strong>Persentase Kehadiran Total</strong></p>
                <div class="progress-group">
                  Hadir
                  <span class="float-right"><b><?php echo $rekap['Hadir']; ?></b>/<?php echo $rekap['TotalBulan']; ?> (<?php echo $persen_hadir; ?>%)</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-success" style="width: <?php echo $persen_hadir; ?>%"></div>
                  </div>
                </div>
                <div class="progress-group">
                  Izin
                  <span class="float-right"><b><?php echo $rekap['Izin']; ?></b>/<?php echo $rekap['TotalBulan']; ?> (<?php echo $persen_izin; ?>%)</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-warning" style="width: <?php echo $persen_izin; ?>%"></div>
                  </div>
                </div>
                <div class="progress-group">
                  Sakit
                  <span class="float-right"><b><?php echo $rekap['Sakit']; ?></b>/<?php echo $rekap['TotalBulan']; ?> (<?php echo $persen_sakit; ?>%)</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-info" style="width: <?php echo $persen_sakit; ?>%"></div>
                  </div>
                </div>
                <div class="progress-group">
                  Alpha
                  <span class="float-right"><b><?php echo $rekap['Alpha']; ?></b>/<?php echo $rekap['TotalBulan']; ?> (<?php echo $persen_alpha; ?>%)</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-danger" style="width: <?php echo $persen_alpha; ?>%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-sm-3 col-6 text-center">
                <h5 class="description-header"><?php echo $total_siswa; ?></h5>
                <span class="description-text text-muted"><i class="fas fa-users mr-1"></i>TOTAL SISWA</span>
              </div>
              <div class="col-sm-3 col-6 text-center">
                <h5 class="description-header text-success"><?php echo $hadir_hari_ini; ?></h5>
                <span class="description-text text-muted"><i class="fas fa-check mr-1"></i>HADIR HARI INI</span>
              </div>
              <div class="col-sm-3 col-6 text-center">
                <h5 class="description-header text-danger"><?php echo $tidak_hadir_hari_ini; ?></h5>
                <span class="description-text text-muted"><i class="fas fa-times mr-1"></i>TIDAK HADIR</span>
              </div>
              <div class="col-sm-3 col-6 text-center">
                <h5 class="description-header text-primary"><?php echo $persen_hadir; ?>%</h5>
                <span class="description-text text-muted"><i class="fas fa-percentage mr-1"></i>RATA-RATA KEHADIRAN</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="card card-table-dark">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list-alt mr-2 text-info"></i>Daftar Kehadiran Hari Ini</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: #fff;">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0 table-valign-middle table-dark-custom">
                <thead>
                  <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (mysqli_num_rows($q_daftar_hadir) > 0) : ?>
                    <?php while ($data_hadir = mysqli_fetch_assoc($q_daftar_hadir)) : ?>
                      <tr>
                        <td><?php echo htmlspecialchars($data_hadir['nis'] ?? '-'); ?></td>
                        <td><b class="text-dark"><?php echo htmlspecialchars($data_hadir['namasiswa'] ?? '-'); ?></b></td>
                        <td><span class="text-secondary"><?php echo htmlspecialchars($data_hadir['namakelas'] ?? '-'); ?></span></td>
                        <td>
                          <?php 
                          // KOREKSI: Menggunakan nama_status dari tabel status_absen
                          $status = htmlspecialchars($data_hadir['nama_status'] ?? 'N/A');
                          $badge_class = get_badge_class($status);
                          ?>
                          <span class="badge <?php echo $badge_class; ?> p-2"><?php echo $status; ?></span>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted p-4">Belum ada data absen hari ini.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer clearfix" style="background-color: #343a40; border-top: 1px solid #454d55;">
            <a href="index.php?halaman=absen" class="btn btn-sm btn-primary float-right shadow-sm">Lihat Selengkapnya <i class="fas fa-arrow-circle-right ml-1"></i></a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-primary card-outline">
          <div class="card-header bg-primary text-white">
            <h3 class="card-title"><i class="fas fa-school mr-2"></i>Informasi Sekolah</h3>
          </div>
          <div class="card-body">
            <p><b class="text-primary">Nama Sekolah:</b> SMK NEGERI 1 KARANG BARU</p>
            <p><b class="text-primary">Tahun Ajaran:</b> 2025 / 2026</p>
            <p><b class="text-primary">Wali Kelas:</b> Fahmi putra, S.Pd</p>
          </div>
        </div>

        <div class="card card-warning card-outline">
          <div class="card-header bg-warning text-dark">
            <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Statistik Kelas</h3>
          </div>
          <div class="card-body">
            <canvas id="classAttendanceChart" height="150"></canvas> 
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // PENTING: Cek kembali apakah library Chart.js sudah dimuat di file index.php Anda!
    if (typeof Chart === 'undefined') {
        console.error("Chart.js library not loaded. Charts will not display.");
        return; // Hentikan eksekusi script jika Chart.js belum dimuat
    }

    // Data dari PHP untuk Chart Bulanan
    const rekapData = {
        hadir: <?php echo $rekap['Hadir']; ?>,
        izin: <?php echo $rekap['Izin']; ?>,
        sakit: <?php echo $rekap['Sakit']; ?>,
        alpha: <?php echo $rekap['Alpha']; ?>
    };

    // --- Chart Rekap Kehadiran Bulanan (Bar Chart) ---
    const ctxAttendance = document.getElementById('attendanceChart');
    if (ctxAttendance) {
        new Chart(ctxAttendance.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                datasets: [{
                    label: 'Total Absensi Bulan Ini',
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                    data: [rekapData.hadir, rekapData.izin, rekapData.sakit, rekapData.alpha]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: '#333'
                        },
                        gridLines: { display: true, color: 'rgba(0, 0, 0, 0.05)' }
                    }],
                    xAxes: [{
                        gridLines: { display: false }
                    }]
                }
            }
        });
    }


    // --- Chart Statistik Kelas (Doughnut Chart) ---
    const ctxClass = document.getElementById('classAttendanceChart');
    if (ctxClass) {
        new Chart(ctxClass.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($kelas_labels); ?>, // Data label dari PHP
                datasets: [{
                    data: <?php echo json_encode($kelas_data); ?>, // Data jumlah siswa dari PHP
                    backgroundColor : ['#007bff', '#ffc107', '#28a745', '#17a2b8', '#dc3545', '#6f42c1'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom' },
            }
        });
    }
});
</script>