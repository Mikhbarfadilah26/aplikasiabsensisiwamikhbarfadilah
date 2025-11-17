<?php
require_once __DIR__ . '/../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaadmin = $koneksi->real_escape_string($_POST['namaadmin']);
    $nohp = $koneksi->real_escape_string($_POST['nohp']);
    $username = $koneksi->real_escape_string($_POST['username']);
    $password_plain = $_POST['password'];
    $fotoadmin = 'user_default.jpg';

    // Upload foto admin/guru
    if (isset($_FILES['fotoadmin']) && $_FILES['fotoadmin']['error'] === 0) {
        $targetDir = __DIR__ . '/../../assets/img/admin/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $ext = strtolower(pathinfo($_FILES['fotoadmin']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $newName = 'admin_' . time() . '.' . $ext;
            $targetFile = $targetDir . $newName;
            if (move_uploaded_file($_FILES['fotoadmin']['tmp_name'], $targetFile)) {
                $fotoadmin = $newName;
            }
        }
    }

    if (empty($namaadmin) || empty($username) || empty($password_plain)) {
        echo "<script>alert('Semua kolom wajib diisi!');</script>";
    } else {
        $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);
        $sql = "INSERT INTO admin (namaadmin, nohp, username, password, fotoadmin)
                VALUES ('$namaadmin', '$nohp', '$username', '$password_hashed', '$fotoadmin')";
        if ($koneksi->query($sql)) {
            echo "<script>
                alert('Pendaftaran Admin/Guru BERHASIL! Silakan login.');
                window.location='index.php?halaman=loginadmin';
            </script>";
        } else {
            $error_message = ($koneksi->errno == 1062)
                ? 'Username sudah digunakan.'
                : 'Gagal mendaftar: ' . $koneksi->error;
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
<title>Registrasi Admin/Guru</title>

<!-- Font Awesome (ikon AdminLTE) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
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
    margin-bottom: 25px;
}
.left .icon {
    font-size: 70px;
    color: #00b894;
    display: block;
    text-align: center;
    margin-bottom: 20px;
}
form input[type="text"],
form input[type="password"],
form input[type="file"] {
    width: 100%;
    padding: 12px 14px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    transition: all 0.3s;
}
form input:focus {
    border-color: #00b894;
    outline: none;
    box-shadow: 0 0 4px rgba(0,184,148,0.4);
}
button {
    width: 100%;
    padding: 12px;
    background-color: #00b894;
    color: #fff;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    margin-top: 10px;
    cursor: pointer;
    transition: all 0.3s;
}
button:hover {
    background-color: #019874;
    transform: translateY(-2px);
}
.left p {
    text-align: center;
    margin-top: 15px;
}
.left a {
    color: #00b894;
    text-decoration: none;
    font-weight: 500;
}
.left a:hover {
    text-decoration: underline;
}
.right {
    flex: 1;
    background: linear-gradient(135deg, #00b894, #0984e3);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 40px;
}
.right .icon {
    font-size: 90px;
    margin-bottom: 20px;
}
.right h3 {
    font-size: 26px;
    margin-bottom: 10px;
}
.right p {
    font-size: 15px;
    line-height: 1.6;
    max-width: 320px;
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
    color: #00b894;
}
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
        <i class="fas fa-user-tie icon"></i>
        <h2>Registrasi Admin / Guru</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="text" name="namaadmin" placeholder="Nama Lengkap" required>
            <input type="text" name="nohp" placeholder="Nomor HP (Opsional)">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <label style="font-size:13px;color:#555;">Upload Foto Profil:</label>
            <input type="file" name="fotoadmin" accept="image/*">
            <button type="submit">Daftar Sekarang</button>
        </form>
        <p>Sudah punya akun? <a href="index.php?halaman=loginadmin">Login di sini</a></p>
    </div>

    <div class="right">
        <i class="fas fa-user-shield icon"></i>
        <h3>Selamat Datang di Portal Admin</h3>
        <p><b>Sistem Absensi Digital</b><br>
        Daftarkan dirimu sebagai Admin atau Guru dan bantu menciptakan sistem absensi yang efisien, cepat, dan modern.</p>
        <a href="index.php?halaman=loginadmin">Sudah punya akun? Login</a>
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
