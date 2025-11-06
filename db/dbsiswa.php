<?php
// File: db/dbsiswa.php

include "../koneksi.php"; 
session_start();

$proses = $_GET['proses'] ?? '';

// PERBAIKAN: Menggunakan direktori target: '../foto/siswa/' (Relatif dari folder 'db/')
$TARGET_DIR = "../foto/siswa/"; 

// Pastikan folder penyimpanan ada
if (!is_dir($TARGET_DIR)) {
    // Jika folder belum ada, buat folder 'foto/siswa/'
    if (!mkdir($TARGET_DIR, 0777, true)) {
        // Handle error jika mkdir gagal
        // Contoh: die("Gagal membuat folder penyimpanan foto."); 
    }
}

/* ==========================================
   TAMBAH SISWA (proses=tambahsiswa)
========================================== */
if ($proses == 'tambahsiswa') {
    $idkelas = (int)$_POST['idkelas'];
    $NIS = mysqli_real_escape_string($koneksi, $_POST['NIS']);
    $namasiswa = mysqli_real_escape_string($koneksi, $_POST['namasiswa']);
    $password_plain = $_POST['password'];

    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
    $fotosiswa = '';

    // Logika Upload Foto Siswa
    if (!empty($_FILES['fotosiswa']['name'])) {
        $foto_tmp = $_FILES['fotosiswa']['tmp_name'];
        // Pastikan nama file unik untuk menghindari konflik
        $foto_name = time() . '_' . basename($_FILES['fotosiswa']['name']);
        $target_file = $TARGET_DIR . $foto_name; 

        if (move_uploaded_file($foto_tmp, $target_file)) {
            $fotosiswa = $foto_name;
        } else {
            // Jika upload gagal
            // echo "Upload Gagal!"; exit;
        }
    }

    // Query INSERT
    $query = "INSERT INTO siswa (idkelas, NIS, namasiswa, password, fotosiswa) 
              VALUES ('$idkelas', '$NIS', '$namasiswa', '$password_hashed', '$fotosiswa')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../index.php?halaman=siswa&status=sukses_tambah");
    } else {
        // Untuk debug error: echo "Error: " . mysqli_error($koneksi); exit;
        header("Location: ../index.php?halaman=tambahsiswa&status=gagal_tambah");
    }
    exit;
}


/* ==========================================
   EDIT SISWA (proses=editsiswa)
========================================== */
if ($proses == 'editsiswa') {
    $idsiswa = (int)$_POST['idsiswa'];
    $idkelas = (int)$_POST['idkelas'];
    $NIS = mysqli_real_escape_string($koneksi, $_POST['NIS']);
    $namasiswa = mysqli_real_escape_string($koneksi, $_POST['namasiswa']);
    $password_new = $_POST['password']; 

    // Ambil data lama
    $query_lama = mysqli_query($koneksi, "SELECT fotosiswa, password FROM siswa WHERE idsiswa='$idsiswa'");
    $data_lama = mysqli_fetch_assoc($query_lama);
    $fotosiswa_lama = $data_lama['fotosiswa'] ?? '';
    $password_lama = $data_lama['password'] ?? ''; 

    $fotosiswa = $fotosiswa_lama;
    $password_update = $password_lama;

    // Cek password baru
    if (!empty($password_new)) {
        $password_update = password_hash($password_new, PASSWORD_DEFAULT);
    }

    // Logika Upload Foto Baru
    if (!empty($_FILES['fotosiswa']['name'])) {
        $foto_tmp = $_FILES['fotosiswa']['tmp_name'];
        $foto_name = time() . '_' . basename($_FILES['fotosiswa']['name']);
        $target_file = $TARGET_DIR . $foto_name; 

        if (move_uploaded_file($foto_tmp, $target_file)) {
            // Hapus foto lama jika ada
            if (!empty($fotosiswa_lama) && file_exists($TARGET_DIR . $fotosiswa_lama)) {
                unlink($TARGET_DIR . $fotosiswa_lama);
            }
            $fotosiswa = $foto_name;
        }
    }

    // Query UPDATE
    $query_update = "UPDATE siswa SET 
                     idkelas='$idkelas', 
                     NIS='$NIS', 
                     namasiswa='$namasiswa', 
                     password='$password_update', 
                     fotosiswa='$fotosiswa' 
                     WHERE idsiswa='$idsiswa'";
                     
    if (mysqli_query($koneksi, $query_update)) {
        header("Location: ../index.php?halaman=siswa&status=sukses_edit");
    } else {
        header("Location: ../index.php?halaman=editsiswa&id=$idsiswa&status=gagal_edit");
    }
    exit;
}


/* ==========================================
   HAPUS SISWA (proses=hapussiswa)
========================================== */
if ($proses == 'hapussiswa') {
    if (!isset($_GET['id'])) {
        header("Location: ../index.php?halaman=siswa");
        exit;
    }
    
    $idsiswa = (int)$_GET['id'];

    // Ambil nama file foto lama
    $query_foto = mysqli_query($koneksi, "SELECT fotosiswa FROM siswa WHERE idsiswa='$idsiswa'");
    $data = mysqli_fetch_assoc($query_foto);

    // Hapus file foto jika ada
    if ($data && !empty($data['fotosiswa']) && file_exists($TARGET_DIR . $data['fotosiswa'])) {
        unlink($TARGET_DIR . $data['fotosiswa']);
    }

    // Hapus dari database
    mysqli_query($koneksi, "DELETE FROM siswa WHERE idsiswa='$idsiswa'");

    header("Location: ../index.php?halaman=siswa&status=sukses_hapus");
    exit;
}
?>