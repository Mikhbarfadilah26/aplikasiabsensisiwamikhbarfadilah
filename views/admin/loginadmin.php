<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login Admin | SMK NEON ABSENSI</title>

    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
        /* Variabel Warna Neon */
        :root {
            --neon-green: #39ff14; /* Hijau Neon */
            --dark-blue: #0e0e1a; /* Background Gelap */
            --dark-purple: #1a0e2a; /* Gradasi Gelap */
            --accent-glow: #a7fff6; /* Aksen Cahaya */
        }

        /* --- BODY & CONTAINER UTAMA --- */
        body {
            /* Gradasi background gelap */
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--dark-purple) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Consolas', 'Courier New', monospace; /* Font Gaya Tech */
        }

        .login-box-custom {
            width: 400px; /* Ukuran box diperbesar */
            background: rgba(14, 14, 26, 0.7); /* Background card transparan */
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 30px rgba(57, 255, 20, 0.4); /* Glow utama */
            border: 2px solid var(--neon-green);
        }

        /* --- LOGO / JUDUL --- */
        .login-logo a {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--neon-green);
            text-shadow:
                0 0 5px var(--neon-green),
                0 0 15px var(--neon-green),
                0 0 30px rgba(57, 255, 20, 0.8); /* Efek Timbul Neon */
            margin-bottom: 30px;
            display: block;
            text-align: center;
            letter-spacing: 2px;
        }

        /* Logo Unik (Menggunakan Ikon Fa-Fingerprint) */
        .login-logo i {
            font-size: 1.8rem;
            margin-right: 10px;
            color: var(--accent-glow);
            text-shadow: 0 0 10px var(--accent-glow);
        }

        /* --- TEKS INFORMASI --- */
        .login-box-msg {
            color: #ccc;
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.1rem;
            border-bottom: 1px dashed #333;
            padding-bottom: 15px;
        }

        /* --- INPUT FIELD --- */
        .form-control {
            background-color: rgba(0, 0, 0, 0.5); /* Input semi-transparan */
            border: 1px solid var(--neon-green);
            color: var(--accent-glow);
            box-shadow: 0 0 8px rgba(57, 255, 20, 0.3);
            transition: all 0.3s;
        }

        .form-control::placeholder {
            color: #6a6a6a;
        }

        .form-control:focus {
            background-color: rgba(0, 0, 0, 0.8);
            border-color: var(--accent-glow);
            box-shadow: 0 0 15px var(--neon-green);
            color: white;
        }

        .input-group-text {
            background-color: var(--neon-green);
            border: 1px solid var(--neon-green);
            color: var(--dark-blue);
            box-shadow: 0 0 5px var(--neon-green);
        }

        /* Tombol mata (agar terlihat menyatu) */
        .btn-toggle-visibility {
            background: transparent;
            border: 1px solid var(--neon-green);
            border-left: none;
            color: var(--accent-glow);
            padding: 6px 10px;
            border-radius: 0 6px 6px 0;
            box-shadow: 0 0 8px rgba(57, 255, 20, 0.15);
            cursor: pointer;
        }
        .btn-toggle-visibility:focus {
            outline: none;
            box-shadow: 0 0 12px var(--neon-green);
        }
        .btn-toggle-visibility .fas {
            pointer-events: none; /* biar klik melewati icon ke tombol */
        }

        /* --- TOMBOL SIGN IN --- */
        .btn-neon {
            background: linear-gradient(90deg, #10e010 0%, #39ff14 100%); /* Gradasi Hijau */
            color: var(--dark-blue);
            font-weight: bold;
            border: none;
            padding: 10px 0;
            border-radius: 8px;
            box-shadow: 0 0 15px var(--neon-green);
            transition: all 0.3s;
        }
        .btn-neon:hover {
            box-shadow: 0 0 30px var(--neon-green), 0 0 50px rgba(57, 255, 20, 0.6);
            transform: translateY(-2px);
            background: linear-gradient(90deg, #39ff14 0%, #10e010 100%);
            color: var(--dark-blue);
        }

        /* --- KEMBALI KE HALAMAN UTAMA --- */
        .back-link {
            color: var(--accent-glow);
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 5px;
            border-radius: 5px;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: var(--neon-green);
            text-shadow: 0 0 5px var(--neon-green);
        }

        /* sedikit responsif */
        @media (max-width: 420px) {
            .login-box-custom { width: 92%; padding: 28px; }
        }
    </style>
</head>
<body>
<div class="login-box-custom">
    <div class="login-logo">
        <i class="fas fa-fingerprint"></i> <a href="index.php"><b>Admin</b> Login</a>
    </div>

    <div class="card" style="background: none; border: none;">
        <div class="card-body" style="padding: 0;">
            <p class="login-box-msg">Masuk untuk mengelola data absensi</p>

            <form action="index.php?halaman=dblogin" method="post" autocomplete="off">
                <input type="hidden" name="user_type" value="admin">

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder=" (Username)" required autocomplete="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3" style="align-items: stretch;">
                    <!-- password field -->
                    <input id="password-field" type="password" name="password" class="form-control" placeholder=" (Password)" required autocomplete="current-password" aria-label="Password">
                    <div class="input-group-append" style="display:flex;">
                        <!-- ikon gembok -->
                        <div class="input-group-text" style="border-right: none;">
                            <span class="fas fa-lock"></span>
                        </div>
                        <!-- tombol toggle visibility -->
                        <button type="button" id="togglePassword" class="btn-toggle-visibility" aria-label="Tampilkan atau sembunyikan password" title="Tampilkan / Sembunyikan password">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block btn-neon">
                            <i class="fas fa-sign-in-alt mr-2"></i> MASUK
                        </button>
                    </div>
                </div>
            </form>

            <a href="index.php?halaman=welcome" class="back-link">
                &lt;&lt; Back to Mainframe (Halaman Utama)
            </a>
        </div>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
    // Toggle visibility password
    (function() {
        const pwField = document.getElementById('password-field');
        const toggleBtn = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleBtn.addEventListener('click', function() {
            const isPassword = pwField.getAttribute('type') === 'password';
            pwField.setAttribute('type', isPassword ? 'text' : 'password');

            // ganti icon
            if (isPassword) {
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }

            // tetap fokus ke field supaya UX nyaman
            pwField.focus();
        });

        // Optional: support toggle with keyboard (Spasi / Enter ketika tombol fokus)
        toggleBtn.addEventListener('keydown', function(e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                toggleBtn.click();
            }
        });
    })();
</script>
</body>
</html>
