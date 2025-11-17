<?php
require_once __DIR__ . '/../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idkelas = $koneksi->real_escape_string($_POST['idkelas']);
    $nis = $koneksi->real_escape_string($_POST['NIS']);
    $namasiswa = $koneksi->real_escape_string($_POST['namasiswa']);
    $username = $koneksi->real_escape_string($_POST['username']);
    $password_plain = $_POST['password'];

    if (empty($idkelas) || empty($nis) || empty($namasiswa) || empty($username) || empty($password_plain)) {
        echo "<script>alert('Semua kolom wajib diisi!');</script>";
    } else {
        $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

        // Upload foto siswa
        $foto = 'user_default.jpg';
        if (!empty($_FILES['fotosiswa']['name'])) {
            $target_dir = __DIR__ . '/../../assets/fotosiswa/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_tmp = $_FILES['fotosiswa']['tmp_name'];
            $file_name = time() . '_' . basename($_FILES['fotosiswa']['name']);
            $file_path = $target_dir . $file_name;

            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $foto = $file_name;
                } else {
                    echo "<script>alert('Gagal mengupload foto.');</script>";
                }
            } else {
                echo "<script>alert('Format foto tidak valid (harus JPG/PNG/GIF).');</script>";
            }
        }

        // Simpan ke DB
        $sql = "INSERT INTO siswa (idkelas, NIS, namasiswa, password, fotosiswa, username)
                VALUES ('$idkelas', '$nis', '$namasiswa', '$password_hashed', '$foto', '$username')";

        if ($koneksi->query($sql)) {
            echo "<script>
                alert('Pendaftaran siswa berhasil! Silakan login.');
                window.location='index.php?halaman=loginsiswa';
            </script>";
        } else {
            $error_message = ($koneksi->errno == 1062)
                ? 'Username atau NIS sudah digunakan.'
                : 'Gagal mendaftar: " . $koneksi->error . "';
            echo "<script>alert('$error_message');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ecf0f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            width: 850px;
            max-width: 95%;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Bagian kiri (form) */
        .left {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left h2 {
            text-align: center;
            color: #2d3436;
            margin-bottom: 20px;
        }

        .left .logo {
            width: 60px;
            display: block;
            margin: 0 auto 10px auto;
        }

        form input[type="text"],
        form input[type="password"],
        form input[type="number"],
        form input[type="file"] {
            width: 100%;
            padding: 12px 14px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: all 0.3s;
        }

        form input:focus {
            border-color: #0984e3;
            outline: none;
            box-shadow: 0 0 4px rgba(9, 132, 227, 0.4);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #0984e3;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background-color: #0766b1;
            transform: translateY(-2px);
        }

        .left p {
            text-align: center;
            margin-top: 15px;
        }

        .left a {
            color: #0984e3;
            text-decoration: none;
            font-weight: 500;
        }

        .left a:hover {
            text-decoration: underline;
        }

        /* Bagian kanan (welcome) */
        .right {
            flex: 1;
            background: linear-gradient(135deg, #00cec9, #0984e3);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
        }

        .right img {
            width: 120px;
            margin-bottom: 20px;
        }

        .right h3 {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .right p {
            font-size: 15px;
            line-height: 1.5;
            max-width: 300px;
            margin-bottom: 25px;
        }

        .right a {
            color: white;
            border: 1px solid #fff;
            padding: 10px 28px;
            border-radius: 25px;
            text-decoration: none;
            transition: 0.3s;
        }

        .right a:hover {
            background-color: white;
            color: #0984e3;
        }

        /* Responsif */
        @media (max-width: 850px) {
            .container {
                flex-direction: column;
                width: 95%;
            }

            .right {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png"
                alt="Logo siswa" class="logo"
                style="width:60px;display:block;margin:0 auto 10px auto;">

            <h2>Registrasi Siswa Baru</h2>

            <form method="post" enctype="multipart/form-data">

                <!-- Dropdown Kelas (ukuran sama seperti input lainnya) -->
                <select name="idkelas" required
                    style="width:100%;padding:12px 14px;margin:8px 0;
                       border:1px solid #ccc;border-radius:8px;
                       font-size:14px;">
                    <option value="">-- Pilih Kelas --</option>
                    <?php
                    $kelas = $koneksi->query("SELECT idkelas, namakelas 
                                          FROM kelas ORDER BY namakelas ASC");
                    while ($row = $kelas->fetch_assoc()): ?>
                        <option value="<?= $row['idkelas']; ?>">
                            <?= $row['namakelas']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="text" name="NIS" placeholder="Nomor Induk Siswa" required>
                <input type="text" name="namasiswa" placeholder="Nama Lengkap" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <label style="font-size:13px;color:#555;">Upload Foto Profil:</label>
                <input type="file" name="fotosiswa" accept="image/*">

                <button type="submit">Daftar Sekarang</button>
            </form>

            <p>Sudah punya akun?
                <a href="index.php?halaman=loginsiswa">Login di sini</a>
            </p>
        </div>

        <div class="right">
            <img src="https://cdn-icons-png.flaticon.com/512/201/201818.png"
                alt="Icon buku" style="width:120px;margin-bottom:20px;">

            <h3>Selamat Datang!</h3>
            <p>Silakan daftar untuk mulai menggunakan sistem absensi siswa modern.</p>

            <a href="index.php?halaman=loginsiswa"
                style="color:white;border:1px solid #fff;padding:10px 28px;
                  border-radius:25px;text-decoration:none;transition:0.3s;">
                Sudah punya akun? Login
            </a>
        </div>
    </div>


    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            if (password.length < 6) {
                alert("Password minimal 6 karakter!");
                return false;
            }
            return true;
        }
    </script>

</body>

</html>