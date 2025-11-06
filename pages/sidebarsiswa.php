<?php 
// ASUMSI: VARIABEL SESI LOGIN SISWA SUDAH TERSEDIA
// Gunakan variabel ini untuk menampilkan nama dan NISN yang sebenarnya
$nama_siswa_login = isset($_SESSION['nama_siswa']) ? $_SESSION['nama_siswa'] : 'Pengguna Siswa';
$nisn_siswa_login = isset($_SESSION['nisn_siswa']) ? $_SESSION['nisn_siswa'] : 'N/A';
$profile_pic = 'dist/img/user2-160x160.jpg'; // Ganti dengan path foto siswa yang benar
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php?halaman=dashboardsiswa" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="Logo Siswa" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">ABSENSI SISWA</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $profile_pic ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="index.php?halaman=editprofilsiswa" class="d-block font-weight-bold"><?= $nama_siswa_login ?></a>
                <small class="text-muted">NISN: <?= $nisn_siswa_login ?></small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="index.php?halaman=dashboardsiswa" class="nav-link 
                        <?php if(isset($_GET['halaman']) && $_GET['halaman'] == 'dashboardsiswa') echo 'active'; ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="index.php?halaman=absensiswa" class="nav-link 
                        <?php if(isset($_GET['halaman']) && $_GET['halaman'] == 'absensiswa') echo 'active'; ?>">
                        <i class="nav-icon fas fa-fingerprint"></i>
                        <p>
                            Presensi Harian
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?halaman=laporanabsensiswa" class="nav-link 
                        <?php if(isset($_GET['halaman']) && $_GET['halaman'] == 'laporanabsensiswa') echo 'active'; ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Riwayat Absensi
                        </p>
                    </a>
                </li>

                <li class="nav-header">AKUN & PENGATURAN</li>

                <li class="nav-item">
                    <a href="index.php?halaman=editprofilsiswa" class="nav-link 
                        <?php if(isset($_GET['halaman']) && $_GET['halaman'] == 'editprofilsiswa') echo 'active'; ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Pengaturan Profil
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        </div>
    </aside>