<?php
include "../koneksi.php";
session_start();

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

/* ==========================================
   TAMBAH KELAS
========================================== */
if ($proses == 'tambah') {
    $namakelas = mysqli_real_escape_string($koneksi, $_POST['namakelas']);
    $fotokelas = '';

    // Upload foto jika ada
    if (!empty($_FILES['fotokelas']['name'])) {
        $foto_tmp = $_FILES['fotokelas']['tmp_name'];
        $foto_name = time() . '_' . basename($_FILES['fotokelas']['name']);
        $target_dir = "../foto/kelas/";
        $target_file = $target_dir . $foto_name;

        // Pastikan folder ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Pindahkan file ke folder
        if (move_uploaded_file($foto_tmp, $target_file)) {
            $fotokelas = $foto_name;
        }
    }

    // Simpan ke database
    mysqli_query($koneksi, "INSERT INTO kelas (namakelas, fotokelas) VALUES ('$namakelas', '$fotokelas')");
    header("Location: ../index.php?halaman=kelas");
    exit;
}

/* ==========================================
   EDIT KELAS
========================================== */
if ($proses == 'edit') {
    $idkelas = $_POST['idkelas'];
    $namakelas = mysqli_real_escape_string($koneksi, $_POST['namakelas']);

    // Ambil data lama
    $query_lama = mysqli_query($koneksi, "SELECT fotokelas FROM kelas WHERE idkelas='$idkelas'");
    $data_lama = mysqli_fetch_assoc($query_lama);
    $fotokelas_lama = $data_lama['fotokelas'];

    $fotokelas = $fotokelas_lama; // default

    // Jika ada upload foto baru
    if (!empty($_FILES['fotokelas']['name'])) {
        $foto_tmp = $_FILES['fotokelas']['tmp_name'];
        $foto_name = time() . '_' . basename($_FILES['fotokelas']['name']);
        $target_dir = "../foto/kelas/";
        $target_file = $target_dir . $foto_name;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($foto_tmp, $target_file)) {
            // Hapus foto lama jika ada
            if (!empty($fotokelas_lama) && file_exists($target_dir . $fotokelas_lama)) {
                unlink($target_dir . $fotokelas_lama);
            }
            $fotokelas = $foto_name;
        }
    }

    // Update data
    mysqli_query($koneksi, "UPDATE kelas SET namakelas='$namakelas', fotokelas='$fotokelas' WHERE idkelas='$idkelas'");
    header("Location: ../index.php?halaman=kelas");
    exit;
}

/* ==========================================
   HAPUS KELAS
========================================== */
if ($proses == 'hapus') {
    $idkelas = $_GET['idkelas'];

    // Ambil foto lama
    $query_foto = mysqli_query($koneksi, "SELECT fotokelas FROM kelas WHERE idkelas='$idkelas'");
    $data = mysqli_fetch_assoc($query_foto);

    // Hapus file foto jika ada
    if (!empty($data['fotokelas']) && file_exists("../foto/kelas/" . $data['fotokelas'])) {
        unlink("../foto/kelas/" . $data['fotokelas']);
    }

    // Hapus dari database
    mysqli_query($koneksi, "DELETE FROM kelas WHERE idkelas='$idkelas'");
    header("Location: ../index.php?halaman=kelas");
    exit;
}
?>
