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
    --blue: #1d5dfa;
    --blue-light: #6fb7ff;
    --blue-pale: #e9f1ff;
    --gray-bg: #f4f6fa;
    --dark-text: #1a2a3a;
    --white: #ffffff;
  }

  body.siswa-login-page {
    background: linear-gradient(135deg, var(--blue-pale) 0%, #ffffff 100%);
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
    box-shadow: 0 8px 25px rgba(0, 70, 160, 0.18);
    overflow: hidden;
    border: 2px solid rgba(29, 93, 250, 0.25);
  }

  .login-header {
    background: linear-gradient(90deg, var(--blue), var(--blue-light));
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

  .student-icon i {
    font-size: 3.6rem;
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.7));
    animation: float 3s ease-in-out infinite;
  }

  .app-title {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 1px;
    color: #ffffff;
    text-shadow: 1px 2px 4px rgba(0,0,0,0.2);
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
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 30px;
  }

  .input-group-text {
    background-color: var(--blue);
    color: white;
    border: none;
    font-size: 1.2rem;
    border-radius: 8px 0 0 8px;
  }

  .form-control {
    font-size: 1.2rem;
    padding: 1rem;
    border: 1px solid #cbd6f3;
    border-radius: 0 8px 8px 0;
    background-color: #fff;
    color: var(--dark-text);
    transition: box-shadow 0.3s, border-color 0.3s;
  }

  .form-control:focus {
    outline: none;
    box-shadow: 0 0 10px rgba(29, 93, 250, 0.4);
    border-color: var(--blue);
  }

  .btn-masuk {
    background: linear-gradient(90deg, var(--blue), var(--blue-light));
    font-size: 1.2rem;
    padding: 14px;
    border-radius: 10px;
    font-weight: 700;
    color: #ffffff;
    border: none;
    box-shadow: 0 4px 15px rgba(29, 93, 250, 0.45);
    transition: all 0.3s ease;
    margin-top: 8px;
  }

  .btn-masuk:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(29, 93, 250, 0.6);
  }

  .welcome-back-link {
    display: inline-block;
    margin-top: 25px;
    color: var(--blue);
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
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
