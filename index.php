<?php
session_start();

// ========================================
// 1️⃣ KONEKSI DATABASE
// ========================================
require_once 'koneksi.php';

// ========================================
// 2️⃣ PATH UNTUK VIEWS DAN PAGES
// ========================================
define('VIEWS_PATH', __DIR__ . '/views/');
define('PAGES_PATH', __DIR__ . '/pages/');

// ========================================
// 3️⃣ PARAMETER ROUTING
// ========================================
$halaman = $_GET['halaman'] ?? 'welcome';
$halaman = preg_replace('/[^a-z0-9_]/i', '', $halaman);

// ========================================
// 4️⃣ HALAMAN UNTUK GUEST (BELUM LOGIN)
// ========================================
$guest_pages = [
    'welcome',
    'loginadmin',
    'loginsiswa',
    'dblogin',
    'logout',
    'tentang',
    'panduan',
    'registersiswa',
    'registeradmin'
];

$is_guest = !isset($_SESSION['level']);

// ========================================
// 5️⃣ LOGIN & LOGOUT ROUTING
// ========================================
if ($halaman === 'dblogin') {
    require_once 'db/dblogin.php';
    exit;
} elseif ($halaman === 'logout') {
    require_once 'logout.php';
    exit;
}

// ========================================
// 6️⃣ GUEST PAGES (BELUM LOGIN)
// ========================================
if (in_array($halaman, $guest_pages)) {
    switch ($halaman) {
        case 'loginadmin':
            include(VIEWS_PATH . "admin/loginadmin.php");
            break;

        case 'loginsiswa':
            include(VIEWS_PATH . "siswa/loginsiswa.php");
            break;

        case 'registersiswa': // 🔥 Tambahan routing register siswa
            include(VIEWS_PATH . "siswa/registersiswa.php");
            break;

        case 'registeradmin': // 🔥 Tambahan routing register admin
            include(VIEWS_PATH . "admin/registeradmin.php");
            break;

        case 'tentang':
            include(VIEWS_PATH . "tentang/tentang.php");
            break;

        case 'panduan':
            include(VIEWS_PATH . "panduan/panduan.php");
            break;

        default:
            include(VIEWS_PATH . "welcome/welcome.php");
            break;
    }
    exit;
}

// ========================================
// 7️⃣ JIKA BELUM LOGIN TAPI AKSES NON-GUEST
// ========================================
if ($is_guest) {
    echo "<script>
            alert('Silakan login terlebih dahulu!');
            window.location='index.php?halaman=welcome';
          </script>";
    exit;
}

// ========================================
// 8️⃣ (SISA KODE ROUTING & TEMPLATE DI BAWAH)
// ========================================

// ... lanjutkan bagian 8, 9, 10 (template admin & siswa)
?>


<?php
/////////////////////////////////////////////////////////////
// 8. TEMPLATE UNTUK ADMIN (TANPA KONFIRMASI LOGOUT)
/////////////////////////////////////////////////////////////
if ($_SESSION['level'] === 'admin') {
?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <?php include PAGES_PATH . 'header.php'; ?>
    </head>

    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-dark">
                <?php include PAGES_PATH . 'navbar.php'; ?>
            </nav>

            <!-- Sidebar -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <?php include PAGES_PATH . 'sidebar.php'; ?>
            </aside>

            <!-- Content -->
            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid">
                        <?php include(routeView($halaman)); ?>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer class="main-footer">
                <?php include PAGES_PATH . 'footer.php'; ?>
            </footer>
        </div>

        <!-- Script -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="dist/js/adminlte.js"></script>
    </body>

    </html>
<?php exit;
} ?>


<?php
/////////////////////////////////////////////////////////////
// 9. TEMPLATE UNTUK SISWA (DENGAN KONFIRMASI LOGOUT)
/////////////////////////////////////////////////////////////
if ($_SESSION['level'] === 'siswa') {
?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <?php include PAGES_PATH . 'header.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmLogout(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: "Apakah Anda yakin ingin logout?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "index.php?halaman=logout";
                    }
                });
            }
        </script>
    </head>

    <body class="hold-transition dark-mode layout-top-nav">
        <div class="wrapper">
            <?php
            // Pastikan session sudah aktif
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Ambil data user dari session
            $level = $_SESSION['level'] ?? 'Tamu';
            $nama  = $_SESSION['nama'] ?? 'Pengguna';
            $display_level = strtoupper($level);

            // Gambar user default (bisa diganti dinamis dari database)
            $gambar_user = $_SESSION['foto'] ?? 'dist/img/user2-160x160.jpg';
            ?>

            <!-- ================== NAVBAR ADMIN ================== -->
            <style>
                /* ======================================= */
                /* CSS KUSTOM UNTUK NAVBAR (VERSI RAMPING) */
                /* ======================================= */
                .main-header {
                    padding-top: 0.25rem !important;
                    padding-bottom: 0.25rem !important;
                    background: linear-gradient(90deg, #1f2d3d, #343a40);
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
                }

                .navbar-dark .nav-link {
                    color: #f8f9fa !important;
                    padding-top: 0.5rem !important;
                    padding-bottom: 0.5rem !important;
                }

                .navbar-dark .nav-link:hover {
                    color: #00c0ef !important;
                }

                .navbar-nav .nav-link .fas,
                .navbar-nav .nav-link .far {
                    font-weight: 900 !important;
                    text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
                    font-size: 0.9rem;
                }

                .navbar-badge {
                    font-weight: bold;
                    font-size: 0.6rem;
                    padding: 0.2em 0.4em;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                }

                /* Marquee */
                .marquee-container {
                    flex-grow: 1;
                    text-align: center;
                    overflow: hidden;
                    white-space: nowrap;
                    line-height: 40px;
                }

                .marquee-text {
                    display: inline-block;
                    padding-left: 100%;
                    color: #ffc107;
                    font-weight: 700;
                    font-size: 0.95rem;
                    animation: marquee 15s linear infinite;
                }

                @keyframes marquee {
                    0% {
                        transform: translate(0, 0);
                    }

                    100% {
                        transform: translate(-100%, 0);
                    }
                }

                @media (max-width: 576px) {
                    .marquee-container {
                        display: none;
                    }
                }

                /* User profile in navbar */
                .user-image {
                    width: 25px !important;
                    height: 25px !important;
                    margin-right: 0.3rem !important;
                    margin-top: -2px;
                }

                .user-header {
                    background-color: #343a40 !important;
                    color: #fff !important;
                }
            </style>

            <!-- SweetAlert2 -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function confirmLogout(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Logout',
                        text: "Apakah Anda yakin ingin logout?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "index.php?halaman=logout";
                        }
                    });
                }
            </script>

            <nav class="main-header navbar navbar-expand navbar-dark">
                <!-- LEFT MENU -->

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Home</a>
                </li>
                </ul>

                <!-- MARQUEE TEXT -->
                <div class="marquee-container d-none d-md-block">
                    <span class="marquee-text">
                        APLIKASI ABSENSI SISWA | — Selamat Datang <?php echo htmlspecialchars($nama); ?> —
                    </span>
                </div>

                <!-- RIGHT MENU -->
                <ul class="navbar-nav ml-auto">
                    <!-- Notification Icon (Contoh Saja) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">5</span>
                        </a>
                    </li>

                    <!-- USER MENU -->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo $gambar_user; ?>" class="user-image img-circle elevation-2" alt="User Image">
                            <span class="d-none d-md-inline"><?php echo htmlspecialchars($nama); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <!-- Header -->
                            <li class="user-header">
                                <img src="<?php echo $gambar_user; ?>" class="img-circle elevation-2" alt="User Image">
                                <p>
                                    <b><?php echo $display_level; ?></b><br>
                                    <small><?php echo htmlspecialchars($nama); ?></small>
                                </p>
                            </li>
                            <!-- Footer -->
                            <li class="user-footer text-center">
                                <button onclick="confirmLogout(event)" class="btn btn-danger btn-flat">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                                </button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>



            <!-- ✅ STYLE TEKS BERGERAK + GRADIENT -->
            <style>
                .moving-text-container {
                    position: relative;
                    overflow: hidden;
                    width: 100%;
                    max-width: 750px;
                    height: 32px;
                    line-height: 32px;
                    background: rgba(255, 255, 255, 0.08);
                    border-radius: 25px;
                    box-shadow: 0 0 10px rgba(255, 255, 255, 0.25);
                }

                .moving-text {
                    position: absolute;
                    white-space: nowrap;
                    font-weight: 700;
                    font-size: 15px;
                    letter-spacing: 0.5px;
                    animation: moveText 15s linear infinite;
                }

                /* ✨ Efek Warna Gradasi yang Bergerak */
                .shimmer-text {
                    background: linear-gradient(90deg, #00bcd4, #4caf50, #ffeb3b, #ff5722, #00bcd4);
                    background-size: 400%;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    animation: moveText 15s linear infinite, shimmer 6s ease-in-out infinite;
                }

                /* Animasi jalan dari kanan ke kiri */
                @keyframes moveText {
                    0% {
                        left: 100%;
                    }

                    100% {
                        left: -100%;
                    }
                }

                /* Animasi shimmer warna */
                @keyframes shimmer {
                    0% {
                        background-position: 0% 50%;
                    }

                    50% {
                        background-position: 100% 50%;
                    }

                    100% {
                        background-position: 0% 50%;
                    }
                }

                /* Efek pause saat hover */
                .moving-text-container:hover .moving-text {
                    animation-play-state: paused;
                }
            </style>

            <!-- ✅ SCRIPT OPSIONAL UNTUK PENGAMAN -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const movingText = document.querySelector('.moving-text');
                    if (movingText) {
                        movingText.addEventListener('mouseenter', () => {
                            movingText.style.animationPlayState = 'paused';
                        });
                        movingText.addEventListener('mouseleave', () => {
                            movingText.style.animationPlayState = 'running';
                        });
                    }
                });
            </script>


            <!-- Content -->
            <div class="content-wrapper">
                <section class="content pt-4">
                    <div class="container">
                        <?php include(routeView($halaman)); ?>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer class="main-footer text-center">
                <strong>&copy; 2025 Aplikasi Absensi | Siswa</strong>
            </footer>
        </div>

        <!-- Script -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="dist/js/adminlte.js"></script>
    </body>

    </html>
<?php exit;
} ?>


<?php
/////////////////////////////////////////////////////////////
// 10. ROUTING FUNCTION
/////////////////////////////////////////////////////////////
function routeView($page)
{
    switch ($page) {
        // DASHBOARD
        case "dashboard":
            return VIEWS_PATH . "admin/dashboardadmin.php";
        case "dashboardsiswa":
            return VIEWS_PATH . "siswa/dashboardsiswa.php";

            // TAMBAHAN (TENTANG & PANDUAN)
        case "tentang":
            return VIEWS_PATH . "tentang/tentang.php";
        case "panduan":
            return VIEWS_PATH . "panduan/panduan.php";

            // ADMIN
        case "admin":
            return VIEWS_PATH . "admin/admin.php";
        case "tambahadmin":
            return VIEWS_PATH . "admin/tambahadmin.php";
        case "editadmin":
            return VIEWS_PATH . "admin/editadmin.php";
        case "tampiladmin":
            return VIEWS_PATH . "admin/tampiladmin.php";

            // SISWA
        case "siswa":
            return VIEWS_PATH . "siswa/siswa.php";
        case "tambahsiswa":
            return VIEWS_PATH . "siswa/tambahsiswa.php";
        case "editsiswa":
            return VIEWS_PATH . "siswa/editsiswa.php";

            // KELAS
        case "kelas":
            return VIEWS_PATH . "kelas/kelas.php";
        case "tambahkelas":
            return VIEWS_PATH . "kelas/tambahkelas.php";
        case "editkelas":
            return VIEWS_PATH . "kelas/editkelas.php";

            // ABSEN
        case "absen":
            return VIEWS_PATH . "absen/absen.php";
        case "tambahabsen":
            return VIEWS_PATH . "absen/tambahabsen.php";
        case "editabsen":
            return VIEWS_PATH . "absen/editabsen.php";

            // KEHADIRAN
        case "kehadiran":
            return VIEWS_PATH . "kehadiran/kehadiran.php";
        case "tambahkehadiran":
            return VIEWS_PATH . "kehadiran/tambahkehadiran.php";
        case "editkehadiran":
            return VIEWS_PATH . "kehadiran/editkehadiran.php";

            // POLA JADWAL
        case "polajadwal":
            return VIEWS_PATH . "polajadwal/polajadwal.php";
        case "tambahpolajadwal":
            return VIEWS_PATH . "polajadwal/tambahpolajadwal.php";
        case "editpolajadwal":
            return VIEWS_PATH . "polajadwal/editpolajadwal.php";
            // detilkehadiran
        case "detilkehadiran":
            return VIEWS_PATH . "detilkehadiran/detilkehadiran.php";
        case "tambahdetilkehadiran":
            return VIEWS_PATH . "detilkehadiran/tambahdetilkehadiran.php";
        case "editdetilkehadiran":
            return VIEWS_PATH . "detilkehadiran/editdetilkehadiran.php";
            // STATUS ABSEN
        case "status_absen":
            return VIEWS_PATH . "status_absen/status_absen.php";
        case "tambahstatus_absen":
            return VIEWS_PATH . "status_absen/tambahstatus_absen.php";
        case "editstatus_absen":
            return VIEWS_PATH . "status_absen/editstatus_absen.php";

            // LAPORAN
        case "laporanabsensi":
            return VIEWS_PATH . "laporan/laporanabsensi.php";
        case "export_pdf_absensi.php":
            return VIEWS_PATH . "laporan/export_pdf_absensi.php.";
        case "laporan/export_excel_absensi.php":
            return VIEWS_PATH . "laporan/export_excel_absensi.php.";

            // DEFAULT
        default:
            return PAGES_PATH . "notfound.php";
    }
}
?>