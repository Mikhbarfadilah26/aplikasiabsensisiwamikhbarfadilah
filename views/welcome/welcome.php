<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Absensi Digital SMK NEGERI 1 KARANG BARU</title>

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
                0 3px 5px rgba(0, 0, 0, 0.4),
                0 5px 15px rgba(0, 0, 0, 0.3);
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
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0);
            }
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
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .login-card .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        /* Gaya tombol Registrasi Siswa */
        .btn-register-siswa {
            background-color: #007bff;
        }
        .btn-register-siswa:hover {
            background-color: #0056b3;
        }

        /* Gaya tombol Registrasi Admin */
        .btn-register-admin {
            background-color: #dc3545;
        }
        .btn-register-admin:hover {
            background-color: #bd2130;
        }

        /* Hapus gaya login yang tidak terpakai agar CSS tetap bersih */
        .btn-siswa, .btn-admin { display: none; }
    </style>
</head>

<body>

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

    <header class="welcome-hero">
        <div>
            <h1>Aplikasi Absensi Digital</h1>
            <p class="lead">Cepat, Akurat, dan Terintegrasi untuk Seluruh Civitas Akademika.</p>
            <a href="#register-section" class="btn btn-lg mt-4" style="background-color: var(--accent-color); color: var(--primary-color); font-weight:bold;">
                Pilih Opsi Registrasi <i class="fas fa-arrow-down ml-2"></i>
            </a>
        </div>
    </header>

    <section class="welcome-marquee">
        <div class="container">
            <marquee scrollamount="8">
                SELAMAT DATANG | SILAKAN PILIH OPSI REGISTRASI DI BAWAH INI
            </marquee>
        </div>
    </section>

    <section id="register-section" class="container login-section">
        <div class="text-center mb-5">
            <h2>REGISTRASI AKUN BARU</h2>
            <p class="lead text-muted">Silakan pilih jenis pendaftaran Anda: Siswa atau Admin/Guru.</p>
        </div>

        <div class="row justify-content-center">
            
            <div class="col-md-6 mb-4">
                <div class="card login-card text-center">
                    <div class="card-body">
                        <div>
                            <i class="fas fa-user-plus text-primary fa-4x mb-3"></i> <h3 class="font-weight-bold mt-3">Registrasi Akun Siswa</h3>
                            <p class="text-muted">Pendaftaran untuk seluruh siswa SMK N 1 Karang Baru. Gunakan NIS yang valid.</p>
                        </div>
                        <a href="index.php?halaman=registersiswa" class="btn btn-register-siswa text-white btn-lg btn-block mt-4">
                            <i class="fas fa-pencil-alt mr-2"></i>  DAFTAR SISWA BARU
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card login-card text-center">
                    <div class="card-body">
                        <div>
                            <i class="fas fa-user-tie text-danger fa-4x mb-3"></i> <h3 class="font-weight-bold mt-3">Registrasi Akun Admin/Guru</h3>
                            <p class="text-muted">Hanya untuk Pendaftaran Staf dan Guru. Diperlukan Verifikasi Administrator.</p>
                        </div>
                        <a href="index.php?halaman=registeradmin" class="btn btn-register-admin text-white btn-lg btn-block mt-4">
                            <i class="fas fa-user-cog mr-2"></i> DAFTAR ADMIN/GURU
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