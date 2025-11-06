<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Absensi Digital SMK NEGERI 1 KARANG BARU</title>

    <!-- CSS & Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
            --accent-color: #ffc107;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar-custom {
            background-color: var(--primary-color) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Hero Section */
        .welcome-hero {
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.85), rgba(42, 82, 152, 0.75)),
                        url('/APLIKASIABSENSISISWAIKHBAR/foto/welcome/sekolah.jpg') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            height: 60vh;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* 🔥 Teks Animasi Timbul */
        .welcome-hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #ffffff, #ffe680, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 
                0 3px 5px rgba(0,0,0,0.4),
                0 5px 15px rgba(0,0,0,0.3);
            animation: popIn 1.8s ease-out, floating 3s ease-in-out infinite;
        }

        @keyframes popIn {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.9);
                text-shadow: none;
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes floating {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }

        .welcome-hero .lead {
            font-size: 1.5rem;
            margin-top: 10px;
        }

        /* Running Text */
        .welcome-marquee {
            background-color: var(--accent-color);
            color: var(--primary-color);
            padding: 10px 0;
            font-weight: 700;
            font-size: 1.2rem;
            border-top: 3px solid rgba(255, 255, 255, 0.8);
            border-bottom: 3px solid rgba(255, 255, 255, 0.8);
            margin-bottom: 50px;
        }

        /* Login Section */
        .login-section h2 {
            color: var(--primary-color);
            font-weight: 600;
            border-bottom: 3px solid var(--accent-color);
            padding-bottom: 10px;
            display: inline-block;
        }

        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .btn-siswa { background-color: #28a745; }
        .btn-admin { background-color: var(--primary-color); }

        .btn-siswa:hover { background-color: #218838; }
        .btn-admin:hover { background-color: var(--secondary-color); }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="index.php">
            <i class="fas fa-school mr-2"></i>
            <marquee scrollamount="4" style="width:250px;">SMK NEGERI 1 KARANG BARU</marquee>
        </a>
        <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link" href="index.php?halaman=tentang">Tentang</a>
            <a class="nav-item nav-link" href="index.php?halaman=panduan">Panduan</a>
            <a class="nav-item btn btn-warning text-dark ml-3" href="index.php?halaman=loginadmin">
                <i class="fas fa-sign-in-alt mr-1"></i> Login Admin
            </a>
            <a class="nav-item btn btn-success text-white ml-2" href="index.php?halaman=loginsiswa">
                <i class="fas fa-user-graduate mr-1"></i> Login Siswa
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<header class="welcome-hero">
    <div>
        <h1>Aplikasi Absensi Digital</h1>
        <p class="lead">Cepat, Akurat, dan Terintegrasi untuk Seluruh Civitas Akademika.</p>
        <a href="#login-section" class="btn btn-lg mt-4" style="background-color: var(--accent-color); color: var(--primary-color); font-weight:bold;">
            Mulai Presensi Hari Ini <i class="fas fa-arrow-down ml-2"></i>
        </a>
    </div>
</header>

<!-- Running Marquee -->
<section class="welcome-marquee">
    <div class="container">
        <marquee scrollamount="8">
            SELAMAT DATANG | SILAKAN PILIH JENIS LOGIN ANDA DI BAWAH INI
        </marquee>
    </div>
</section>

<!-- Login Section -->
<section id="login-section" class="container login-section">
    <div class="text-center mb-5">
        <h2>MASUK KE SISTEM ABSENSI</h2>
    </div>

    <div class="row justify-content-center">
        <!-- Siswa -->
        <div class="col-md-5 mb-4">
            <div class="card login-card text-center">
                <div class="card-body">
                    <i class="fas fa-user-graduate text-success"></i>
                    <h5 class="font-weight-bold mt-3">Masuk Sebagai Siswa</h5>
                    <p class="text-muted">Gunakan Nomor Induk Siswa (NIS) Anda untuk presensi harian.</p>
                    <a href="index.php?halaman=loginsiswa" class="btn btn-siswa btn-lg btn-block mt-3">
                        <i class="fas fa-id-card mr-2"></i> ABSENSI SISWA
                    </a>
                </div>
            </div>
        </div>

        <!-- Admin -->
        <div class="col-md-5 mb-4">
            <div class="card login-card text-center">
                <div class="card-body">
                    <i class="fas fa-user-shield text-info"></i>
                    <h5 class="font-weight-bold mt-3">Masuk Sebagai Admin/Guru</h5>
                    <p class="text-muted">Akses sistem manajemen untuk mengelola data dan laporan.</p>
                    <a href="index.php?halaman=loginadmin" class="btn btn-admin btn-lg btn-block mt-3">
                        <i class="fas fa-lock mr-2"></i> KELOLA DATA ABSENSI
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
