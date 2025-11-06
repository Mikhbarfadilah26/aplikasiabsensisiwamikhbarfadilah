<!-- Simplified login page without logos -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Login - SMK NEGERI 1 KARANG BARU</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #32a852;
            --secondary-orange: #6ecf68;
            --gradient-start: #d4ffcc;
            --gradient-end: #a8ff80;
            --text-gradient-start: #ffffff;
            --text-gradient-end: #ffd699;
        }
        body.siswa-login-page {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .siswa-login-box {
            width: 480px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(90deg, var(--secondary-orange), var(--primary-orange));
            text-align: center;
            padding: 35px;
            color: white;
        }
        .school-name {
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .app-title {
            font-size: 2.4rem;
            font-weight: 800;
            display: inline-block;
            background: linear-gradient(to right, var(--text-gradient-start), var(--text-gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.25);
        }
        .login-card-body {
            padding: 45px 40px;
        }
        .input-group-text {
            background-color: var(--secondary-orange);
            border: none;
            color: white;
            font-size: 1.3rem;
        }
        .form-control {
            font-size: 1.3rem;
            padding: 1rem;
            border-radius: 0 10px 10px 0;
            border: 1px solid #ccc;
        }
        .btn-masuk {
            background: linear-gradient(90deg, var(--secondary-orange), var(--primary-orange));
            font-size: 1.4rem;
            padding: 18px;
            border-radius: 12px;
            font-weight: 700;
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(255, 127, 0, 0.4);
            transition: transform 0.3s ease;
        }
        .btn-masuk:hover {
            transform: translateY(-3px);
        }
        .welcome-back-link {
            font-size: 1rem;
            color: var(--primary-orange);
            font-weight: 600;
        }
    </style>
</head>
<body class="siswa-login-page">
<div class="siswa-login-box">
    <div class="login-header">
        <span class="school-name">SMK NEGERI 1 KARANG BARU</span>
        <div class="student-icon" style="margin: 10px auto;">
            <i class="fas fa-user-graduate" style="font-size: 3.5rem; filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.6)); animation: floating 3s ease-in-out infinite;"></i>
        </div>
        <span class="app-title">ABSENSI SISWA</span>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg" style="text-align:center; font-size:1.3rem; font-weight:700;">Masukkan NIS Anda untuk presensi</p>
            <form action="index.php?halaman=dblogin" method="post" id="formSiswaLogin">
                <input type="hidden" name="user_type" value="siswa">
                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                    </div>
                    <input type="text" name="nis" class="form-control" placeholder="NIS Siswa" required>
                </div>
                <button type="submit" class="btn btn-masuk btn-block"><i class="fas fa-sign-in-alt mr-2"></i>Masuk & Presensi</button>
            </form>
            <p class="mt-4 text-center">
                <a href="index.php?halaman=welcome" class="welcome-back-link"><i class="fas fa-home"></i> Kembali ke Halaman Utama</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
