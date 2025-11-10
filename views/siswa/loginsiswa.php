<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Siswa Login - SMK NEGERI 1 KARANG BARU</title>

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --green: #68b684;
      --green-light: #bdf0c7;
      --green-pale: #e8ffe8;
      --gray-bg: #f9f9f9;
      --dark-text: #2f3e2f;
      --white: #fff;
    }

    body.siswa-login-page {
      background: linear-gradient(135deg, var(--green-pale) 0%, #ffffff 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }

    .siswa-login-box {
      width: 460px;
      background: var(--white);
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      border: 2px solid rgba(104, 182, 132, 0.25);
    }

    .login-header {
      background: linear-gradient(90deg, var(--green), var(--green-light));
      text-align: center;
      padding: 35px 25px;
      color: var(--white);
      position: relative;
    }

    .login-header::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      border-radius: 2px;
      background-color: #fff;
    }

    .school-name {
      display: block;
      font-size: 1rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      opacity: 0.9;
    }

    .student-icon {
      margin: 15px auto;
      color: #ffffff;
    }

    .student-icon i {
      font-size: 3.5rem;
      filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.6));
      animation: float 3s ease-in-out infinite;
    }

    .app-title {
      display: block;
      font-size: 2rem;
      font-weight: 700;
      letter-spacing: 1px;
      color: #ffffff;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-6px); }
    }

    .login-card-body {
      padding: 45px 40px;
      background-color: var(--gray-bg);
    }

    .login-box-msg {
      text-align: center;
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 30px;
    }

    .input-group-text {
      background-color: var(--green);
      color: white;
      border: none;
      font-size: 1.2rem;
      border-radius: 8px 0 0 8px;
    }

    .form-control {
      font-size: 1.2rem;
      padding: 1rem;
      border: 1px solid #ccc;
      border-radius: 0 8px 8px 0;
      background-color: #fff;
      color: var(--dark-text);
      transition: box-shadow 0.3s, border-color 0.3s;
    }

    .form-control:focus {
      outline: none;
      box-shadow: 0 0 10px rgba(104, 182, 132, 0.4);
      border-color: var(--green);
    }

    .btn-masuk {
      background: linear-gradient(90deg, var(--green), var(--green-light));
      font-size: 1.2rem;
      padding: 14px;
      border-radius: 10px;
      font-weight: 700;
      color: #ffffff;
      border: none;
      box-shadow: 0 4px 12px rgba(104, 182, 132, 0.4);
      transition: all 0.3s ease;
      margin-top: 5px;
    }

    .btn-masuk:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(104, 182, 132, 0.6);
    }

    .welcome-back-link {
      display: inline-block;
      margin-top: 25px;
      color: var(--green);
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s;
    }

    .welcome-back-link:hover {
      color: var(--dark-text);
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .siswa-login-box { width: 92%; }
      .login-card-body { padding: 35px 25px; }
    }
  </style>
</head>

<body class="siswa-login-page">
  <div class="siswa-login-box">
    <div class="login-header">
      <span class="school-name">SMK NEGERI 1 KARANG BARU</span>
      <div class="student-icon">
        <i class="fas fa-user-graduate"></i>
      </div>
      <span class="app-title">ABSENSI SISWA</span>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Masukkan NIS Anda untuk presensi</p>

        <form action="index.php?halaman=dblogin" method="post" id="formSiswaLogin">
          <input type="hidden" name="user_type" value="siswa">
          <div class="input-group mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-id-card"></i></div>
            </div>
            <input type="text" name="nis" class="form-control" placeholder="NIS Siswa" required>
          </div>
          <button type="submit" class="btn btn-masuk btn-block">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk & Presensi
          </button>
        </form>

        <p class="text-center">
          <a href="index.php?halaman=welcome" class="welcome-back-link">
            <i class="fas fa-home"></i> Kembali ke Halaman Utama
          </a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
