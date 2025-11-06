<?php
// pages/dblogin.php  (atau sesuai lokasi routing Anda)
session_start();
require_once 'koneksi.php'; // pastikan file ini mendefinisikan $koneksi (mysqli)

// jika bukan POST, redirect
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?halaman=welcome');
    exit;
}

$user_type = $_POST['user_type'] ?? '';

if ($user_type === 'admin') {
    // (contoh admin — sesuaikan jika perlu)
    $username = trim($_POST['username'] ?? '');
    $password_input = $_POST['password'] ?? '';

    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $admin = $res->fetch_assoc();
        // contoh: jika password di DB disimpan md5 (sebaiknya pakai password_hash)
        if (md5($password_input) === $admin['password'] || password_verify($password_input, $admin['password'])) {
            // set session admin
            $_SESSION['iduser'] = $admin['idadmin'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['level'] = 'admin';
            header("Location: index.php?halaman=dashboard");
            exit;
        } else {
            echo "<script>alert('Password admin salah'); window.location='index.php?halaman=loginadmin';</script>"; exit;
        }
    } else {
        echo "<script>alert('Username admin tidak ditemukan'); window.location='index.php?halaman=loginadmin';</script>"; exit;
    }

} elseif ($user_type === 'siswa') {
    // proses login siswa berdasarkan NIS
    $nis = trim($_POST['nis'] ?? '');

    if ($nis === '') {
        echo "<script>alert('Isi NIS terlebih dahulu'); window.location='index.php?halaman=loginsiswa';</script>"; exit;
    }

    $sql = "SELECT s.*, k.namakelas
            FROM siswa s
            LEFT JOIN kelas k ON s.idkelas = k.idkelas
            WHERE s.NIS = ? OR s.username = ? LIMIT 1";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('ss', $nis, $nis);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $siswa = $res->fetch_assoc();

        // Set session dengan data nyata dari DB
        $_SESSION['iduser']  = $siswa['idsiswa'];
        $_SESSION['nama']    = $siswa['namasiswa'];
        $_SESSION['nis']     = $siswa['NIS'];
        $_SESSION['idkelas'] = $siswa['idkelas'] ?? null;
        $_SESSION['kelas']   = $siswa['namakelas'] ?? '';
        $_SESSION['foto']    = $siswa['fotosiswa'] ?? '';
        $_SESSION['level']   = 'siswa';

        // Redirect ke dashboard siswa
        header("Location: index.php?halaman=dashboardsiswa");
        exit;
    } else {
        echo "<script>alert('NIS tidak ditemukan. Pastikan NIS benar.'); window.location='index.php?halaman=loginsiswa';</script>";
        exit;
    }

} else {
    echo "<script>alert('Tipe pengguna tidak dikenal'); window.location='index.php?halaman=welcome';</script>";
    exit;
}

$koneksi->close();
