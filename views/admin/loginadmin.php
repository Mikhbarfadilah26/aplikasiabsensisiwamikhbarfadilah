<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin | SMK NEON ABSENSI</title>

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #e0f7ff 0%, #f5faff 100%);
      font-family: "Poppins", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .login-container {
      background: #ffffff;
      border-radius: 20px;
      width: 400px;
      padding: 40px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
    }

    .login-container:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 35px rgba(0, 0, 0, 0.2);
    }

    .login-logo {
      text-align: center;
      margin-bottom: 25px;
    }

    .login-logo img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      box-shadow: 0 0 15px rgba(0, 132, 255, 0.4);
    }

    .login-logo h2 {
      margin-top: 15px;
      font-weight: 700;
      color: #0066cc;
      font-size: 1.8rem;
    }

    .login-box-msg {
      text-align: center;
      color: #555;
      font-size: 1rem;
      margin-bottom: 25px;
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid #c9e0ff;
      background-color: #f8fbff;
      color: #333;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #0066cc;
      box-shadow: 0 0 6px rgba(0, 102, 204, 0.3);
      background-color: #fff;
      color: #000;
    }

    .input-group-text {
      background-color: #0066cc;
      border: 1px solid #0066cc;
      color: white;
      border-radius: 0 6px 6px 0;
    }

    .btn-toggle-visibility {
      background: transparent;
      border: 1px solid #0066cc;
      border-left: none;
      color: #0066cc;
      padding: 6px 10px;
      border-radius: 0 6px 6px 0;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .btn-toggle-visibility:hover {
      background: #0066cc;
      color: white;
    }

    .btn-neon {
      background: linear-gradient(90deg, #0066cc 0%, #3399ff 100%);
      color: white;
      border: none;
      font-weight: 600;
      padding: 10px 0;
      border-radius: 8px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
    }

    .btn-neon:hover {
      background: linear-gradient(90deg, #3399ff 0%, #0066cc 100%);
      box-shadow: 0 6px 20px rgba(0, 102, 204, 0.5);
      transform: translateY(-2px);
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      color: #0066cc;
      transition: color 0.3s;
    }

    .back-link:hover {
      color: #3399ff;
      text-decoration: underline;
    }

    @media (max-width: 420px) {
      .login-container {
        width: 92%;
        padding: 30px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-logo">
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin Logo">
      <h2>Admin Login</h2>
    </div>

    <p class="login-box-msg">Masuk untuk mengelola data absensi</p>

    <form action="index.php?halaman=dblogin" method="post" autocomplete="off">
      <input type="hidden" name="user_type" value="admin">

      <div class="input-group mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="username">
        <div class="input-group-append">
          <div class="input-group-text"><span class="fas fa-user"></span></div>
        </div>
      </div>

      <div class="input-group mb-3" style="align-items: stretch;">
        <input id="password-field" type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
        <div class="input-group-append" style="display:flex;">
          <div class="input-group-text" style="border-right: none;">
            <span class="fas fa-lock"></span>
          </div>
          <button type="button" id="togglePassword" class="btn-toggle-visibility" aria-label="Tampilkan atau sembunyikan password">
            <i id="toggleIcon" class="fas fa-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-block btn-neon">
        <i class="fas fa-sign-in-alt mr-2"></i> MASUK
      </button>
    </form>

    <a href="index.php?halaman=welcome" class="back-link">
      &lt;&lt; Kembali ke Halaman Utama
    </a>
  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>

  <script>
    const pwField = document.getElementById('password-field');
    const toggleBtn = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleBtn.addEventListener('click', () => {
      const isPassword = pwField.type === 'password';
      pwField.type = isPassword ? 'text' : 'password';
      toggleIcon.classList.toggle('fa-eye');
      toggleIcon.classList.toggle('fa-eye-slash');
      pwField.focus();
    });
  </script>
</body>
</html>
