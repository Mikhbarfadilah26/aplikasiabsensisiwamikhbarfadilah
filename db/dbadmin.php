<?php
include "../koneksi.php";
session_start();

// Perhatian: Pastikan file '../koneksi.php' berisi koneksi database $koneksi
// Perhatian: Ini adalah contoh. Untuk aplikasi production, pertimbangkan validasi input dan autentikasi/otorisasi yang lebih ketat.

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

/* ==========================================
    TAMBAH ADMIN
========================================== */
if ($proses == 'tambah') {
    // Ambil dan bersihkan data
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_plain = $_POST['password']; // Ambil password mentah
    $nohp = mysqli_real_escape_string($koneksi, $_POST['nohp']);
    $namaadmin = mysqli_real_escape_string($koneksi, $_POST['namaadmin']);
    $fotoadmin = '';

    // Hash password sebelum disimpan
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    // Upload foto jika ada
    if (!empty($_FILES['fotoadmin']['name'])) {
        $foto_tmp = $_FILES['fotoadmin']['tmp_name'];
        $foto_name = time() . '_' . basename($_FILES['fotoadmin']['name']);
        $target_dir = "../foto/admin/"; // Folder baru untuk foto admin
        $target_file = $target_dir . $foto_name;

        // Pastikan folder ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Pindahkan file ke folder
        if (move_uploaded_file($foto_tmp, $target_file)) {
            $fotoadmin = $foto_name;
        }
    }

    // Simpan ke database
    $sql = "INSERT INTO admin (username, password, nohp, namaadmin, fotoadmin) 
            VALUES ('$username', '$password_hashed', '$nohp', '$namaadmin', '$fotoadmin')";
    mysqli_query($koneksi, $sql);
    
    header("Location: ../index.php?halaman=admin"); // Ganti halaman tujuan
    exit;
}
// -----------------------------------------------------------------------------

/* ==========================================
    EDIT ADMIN
========================================== */
if ($proses == 'edit') {
    $idadmin = $_POST['idadmin'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nohp = mysqli_real_escape_string($koneksi, $_POST['nohp']);
    $namaadmin = mysqli_real_escape_string($koneksi, $_POST['namaadmin']);
    $new_password = $_POST['password']; // Password baru (bisa kosong)

    // Ambil data lama (terutama foto lama dan password lama)
    $query_lama = mysqli_query($koneksi, "SELECT password, fotoadmin FROM admin WHERE idadmin='$idadmin'");
    $data_lama = mysqli_fetch_assoc($query_lama);
    $fotoadmin_lama = $data_lama['fotoadmin'];
    $password_lama = $data_lama['password'];
    
    $fotoadmin = $fotoadmin_lama; // default foto tetap yang lama
    $password_update = $password_lama; // default password tetap yang lama

    // Jika ada password baru, hash dan siapkan untuk update
    if (!empty($new_password)) {
        $password_update = password_hash($new_password, PASSWORD_DEFAULT);
    }
    
    // Siapkan bagian SET SQL untuk password
    $password_sql = ", password='$password_update'";
    // Perhatikan: Jika Anda tidak ingin mengupdate password kecuali ada input,
    // Anda bisa memindahkan penentuan $password_sql ke dalam blok if (!empty($new_password)).
    // Dalam contoh ini, jika $new_password kosong, $password_update akan berisi hash password lama.
    // Jika menggunakan kolom VARCHAR(255) atau TEXT untuk password, ini aman.

    // Jika ada upload foto baru
    if (!empty($_FILES['fotoadmin']['name'])) {
        $foto_tmp = $_FILES['fotoadmin']['tmp_name'];
        $foto_name = time() . '_' . basename($_FILES['fotoadmin']['name']);
        $target_dir = "../foto/admin/"; 
        $target_file = $target_dir . $foto_name;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($foto_tmp, $target_file)) {
            // Hapus foto lama jika ada
            if (!empty($fotoadmin_lama) && file_exists($target_dir . $fotoadmin_lama)) {
                unlink($target_dir . $fotoadmin_lama);
            }
            $fotoadmin = $foto_name;
        }
    }

    // Update data
    $sql = "UPDATE admin SET 
                username='$username', 
                nohp='$nohp', 
                namaadmin='$namaadmin', 
                password='$password_update', 
                fotoadmin='$fotoadmin' 
            WHERE idadmin='$idadmin'";
            
    mysqli_query($koneksi, $sql);
    header("Location: ../index.php?halaman=admin"); // Ganti halaman tujuan
    exit;
}
// -----------------------------------------------------------------------------

/* ==========================================
    HAPUS ADMIN
========================================== */
if ($proses == 'hapus') {
    $idadmin = $_GET['idadmin'];

    // Ambil nama file foto
    $query_foto = mysqli_query($koneksi, "SELECT fotoadmin FROM admin WHERE idadmin='$idadmin'");
    $data = mysqli_fetch_assoc($query_foto);

    // Hapus file foto jika ada
    if ($data && !empty($data['fotoadmin']) && file_exists("../foto/admin/" . $data['fotoadmin'])) {
        unlink("../foto/admin/" . $data['fotoadmin']);
    }

    // Hapus dari database
    mysqli_query($koneksi, "DELETE FROM admin WHERE idadmin='$idadmin'");
    header("Location: ../index.php?halaman=admin"); // Ganti halaman tujuan
    exit;
}
?>