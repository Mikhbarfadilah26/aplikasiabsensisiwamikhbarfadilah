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

<!-- ================== NAVBAR CUSTOM START ================== -->
<style>
/* ======================================= */
/* CSS KUSTOM UNTUK NAVBAR (VERSI RAMPING) */
/* ======================================= */
.main-header {
    padding-top: 0.25rem !important;
    padding-bottom: 0.25rem !important;
    background: linear-gradient(90deg, #1f2d3d, #343a40);
    box-shadow: 0 4px 10px rgba(0,0,0,0.4);
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
    text-shadow: 0 0 5px rgba(255,255,255,0.5);
    font-size: 0.9rem;
}

.navbar-badge {
    font-weight: bold;
    font-size: 0.6rem;
    padding: 0.2em 0.4em;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
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
    0% { transform: translate(0,0); }
    100% { transform: translate(-100%,0); }
}
@media (max-width: 576px) {
    .marquee-container { display:none; }
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

<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- LEFT MENU -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index.php?halaman=dashboard" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- MARQUEE TEXT -->
    <div class="marquee-container d-none d-md-block">
        <span class="marquee-text">
            APLIKASI ABSENSI | DASHBOARD ADMIN — Selamat Datang <?php echo htmlspecialchars($nama); ?> —
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
                        <b><?php echo $display_level; ?></b>
                        <small><?php echo htmlspecialchars($nama); ?></small>
                    </p>
                </li>
                <!-- Footer -->
                <li class="user-footer">
                    <div class="text-center">
                        <a href="logout.php" class="btn btn-default btn-flat">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- ================== NAVBAR CUSTOM END ================== -->
