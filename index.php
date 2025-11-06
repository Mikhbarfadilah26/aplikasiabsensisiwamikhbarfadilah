<?php
session_start();

// 1. Koneksi DB
require_once 'koneksi.php';

// 2. Path untuk Views dan Pages
define('VIEWS_PATH', __DIR__ . '/views/');
define('PAGES_PATH', __DIR__ . '/pages/');
// 3. Routing Parameter
$halaman = $_GET['halaman'] ?? 'welcome';
$halaman = preg_replace('/[^a-z0-9_]/i', '', $halaman);

// 4. Halaman Guest (belum login)
$guest_pages = ['welcome', 'loginadmin', 'loginsiswa', 'dblogin', 'logout', 'tentang', 'panduan'];
$is_guest = !isset($_SESSION['level']);

// 5. Login & Logout Routing
if ($halaman === 'dblogin') {
    require_once 'db/dblogin.php';
    exit;
} elseif ($halaman === 'logout') {
    require_once 'logout.php';
    exit;
}

// 5a. Guest Pages
if (in_array($halaman, $guest_pages)) {
    if ($halaman === 'loginadmin') {
        include(VIEWS_PATH . "admin/loginadmin.php");
    } elseif ($halaman === 'loginsiswa') {
        include(VIEWS_PATH . "siswa/loginsiswa.php");
    } elseif ($halaman === 'tentang') {
        include(VIEWS_PATH . "tentang/tentang.php");
    } elseif ($halaman === 'panduan') {
        include(VIEWS_PATH . "panduan/panduan.php");
    } else {
        include(VIEWS_PATH . "welcome/welcome.php");
    }
    exit;
}

// 6. Jika belum login dan mencoba akses halaman selain guest
if ($is_guest) {
    echo "<script>alert('Silakan login terlebih dahulu!');window.location='index.php?halaman=welcome';</script>";
    exit;
}
?>

<?php
/////////////////////////////////////////////////////////////
// 7. TEMPLATE UNTUK ADMIN
/////////////////////////////////////////////////////////////
if ($_SESSION['level'] === 'admin') {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php include PAGES_PATH . 'header.php'; ?>
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
    </head>

    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">

            <nav class="main-header navbar navbar-expand navbar-dark">
                <?php include PAGES_PATH . 'navbar.php'; ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="index.php?halaman=logout" onclick="confirmLogout(event)" class="nav-link text-danger font-weight-bold">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>

            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <?php include PAGES_PATH . 'sidebar.php'; ?>
            </aside>

            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid">
                        <?php include(routeView($halaman)); ?>
                    </div>
                </section>
            </div>

            <footer class="main-footer">
                <?php include PAGES_PATH . 'footer.php'; ?>
            </footer>
        </div>
        <script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="dist/js/adminlte.js"></script>
    </body>

    </html>
<?php exit;
} ?>

<?php
/////////////////////////////////////////////////////////////
// 8. TEMPLATE UNTUK SISWA
/////////////////////////////////////////////////////////////
if ($_SESSION['level'] === 'siswa') {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php include PAGES_PATH . 'header.php'; ?>
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
    </head>

    <body class="hold-transition dark-mode layout-top-nav">
        <div class="wrapper">

            <nav class="main-header navbar navbar-expand-md navbar-dark navbar-primary">
                <div class="container">
                    <a href="index.php?halaman=dashboardsiswa" class="navbar-brand">
                        <span class="brand-text font-weight-light">Portal Siswa</span>
                    </a>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a href="#" class="btn btn-warning btn-sm font-weight-bold mx-2" onclick="window.location.href='index.php?halaman=absensiswa'">
                                <i class="fas fa-fingerprint"></i> Presensi Harian
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?halaman=logout" onclick="confirmLogout(event)" class="nav-link text-danger font-weight-bold">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="content-wrapper">
                <section class="content pt-4">
                    <div class="container">
                        <?php include(routeView($halaman)); ?>
                    </div>
                </section>
            </div>

            <footer class="main-footer text-center">
                <strong>&copy; 2025 Aplikasi Absensi | Siswa</strong>
            </footer>

        </div>
        <script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="dist/js/adminlte.js"></script>
    </body>

    </html>
<?php exit;
} ?>
<?php
// ================== ROUTER FUNCTION =====================
function routeView($page)
{
    switch ($page) {

        // DASHBOARD
        case "dashboard":
            return VIEWS_PATH . "admin/dashboardadmin.php";
        case "dashboardsiswa":
            return VIEWS_PATH . "siswa/dashboardsiswa.php";

            // ✅ PENAMBAHAN ROUTING UNTUK TENTANG DAN PANDUAN (jika diakses setelah login)
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

            // detilkehadiran
        case "detilkehadiran":
            return VIEWS_PATH . "detilkehadiran/detilkehadiran.php";
        case "tambahdetilkehadiran":
            return VIEWS_PATH . "detilkehadiran/tambahdetilkehadiran.php";
        case "editdetilkehadiran":
            return VIEWS_PATH . "detilkehadiran/editdetilkehadiran.php";
        case "tampildetilkehadiran":
            return VIEWS_PATH . "detilkehadiran/tampildetilkehadiran.php";

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

            // KATEGORI
        case "kategori":
            return VIEWS_PATH . "kategori/kategori.php";
        case "tambahkategori":
            return VIEWS_PATH . "kategori/tambahkategori.php";
        case "editkategori":
            return VIEWS_PATH . "kategori/editkategori.php";

            // LAPORAN
        case "laporanabsensi":
            return VIEWS_PATH . "laporan/laporanabsensi.php";
            
            

            // DEFAULT
        default:
            return PAGES_PATH . "notfound.php";
    }
}
?>