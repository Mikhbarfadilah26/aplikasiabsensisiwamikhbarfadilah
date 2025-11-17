<style>
    /* ======================================= */
    /* CSS KUSTOM UNTUK SIDEBAR */
    /* ======================================= */

    /* 1. Brand Link (Logo dan Judul Aplikasi) */
    .main-sidebar .brand-link {
        /* Background yang kontras dengan warna sidebar dark */
        background: #007bff;
        /* Biru Primer */
        color: white !important;
        border-bottom: 3px solid #0056b3;
        /* Garis bawah biru gelap */
        padding: 1rem 0.5rem;
        /* Padding lebih besar */
    }

    /* 2. Logo Aplikasi (Memberikan efek glow dan warna cerah) */
    .main-sidebar .brand-image {
        /* Efek 3D ringan */
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        border: 2px solid #fff;
        /* Tambahkan filter untuk warna jika logo AdminLTE.png adalah hitam putih */
        /* filter: drop-shadow(0 0 5px rgba(0, 192, 239, 0.8)); */
    }

    /* 3. Teks Brand (APLIKASI) */
    .main-sidebar .brand-text {
        font-weight: 900 !important;
        /* Membuat teks sangat tebal */
        font-size: 1.2rem;
        letter-spacing: 1px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    /* 4. Teks Navigasi dan User Info (Dibuat Bold) */
    .sidebar .info .d-block,
    .nav-sidebar .nav-item .nav-link p,
    .nav-sidebar .nav-header {
        font-weight: 600 !important;
        /* Membuat semua teks menu tebal */
        transition: color 0.3s ease;
    }

    /* 5. Ikon Navigasi (Dibuat Lebih Menonjol) */
    .nav-sidebar .nav-icon {
        font-size: 1.1rem;
        /* Ukuran ikon sedikit lebih besar */
        /* Membuat ikon terlihat lebih solid */
        text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
    }

    /* 6. Active Link Styling (Lebih Jelas) */
    .nav-sidebar>.nav-item>.nav-link.active {
        background-color: #00c0ef !important;
        /* Warna biru cerah */
        color: #1f2d3d !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        font-weight: 700 !important;
    }

    /* 7. Hover Effect pada Navigasi */
    .nav-sidebar .nav-link:not(.active):hover {
        background-color: #34495e;
        /* Warna sedikit lebih terang dari sidebar dark */
        color: #fff !important;
    }

    /* 8. Nav Header (Kategori) yang Lebih Menonjol */
    .nav-header {
        color: #ffc107 !important;
        /* Warna kuning emas untuk kategori */
        border-top: 1px solid #3e4b54;
        padding-top: 10px;
        margin-top: 10px;
    }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php?halaman=dashboard" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
        <span class="brand-text font-weight-light">APLIKASI</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">M. ikhbar fadilah</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="index.php?halaman=dashboard" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">Data</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Data Nama Kelas<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=kelas" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kelas</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Data Siswa perkelas<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=siswa" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>



                <li class="nav-header">Data Absensi</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Data Absen<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=absen" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Absen</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Data Kehadiran<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=kehadiran" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sesi Kehadiran</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>Detil Kehadiran Siswa<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=detilkehadiran" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Detil Kehadiran</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>status Absen<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=status_absen" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>status Absen</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Kategori<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=polajadwal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">Pengguna Sistem</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Data Admin<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=admin" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Index Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">Laporan Absensi</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?halaman=laporanabsensi" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Laporan Absensi</p>
                            </a>

                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>