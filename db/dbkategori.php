<?php
include "../koneksi.php";
session_start();

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

/* ==========================================
    TAMBAH KATEGORI (Hanya menggunakan namakategori)
========================================== */
if ($proses == 'tambah') {
    // Pastikan Anda mendapatkan namakategori dari form. 
    // Jika form tambah kategori menggunakan POST langsung ke dbkategori.php, pastikan ada input utk namakategori.
    $namakategori = mysqli_real_escape_string($koneksi, $_POST['namakategori']);
    
    // HAPUS Cek apakah ID kategori sudah ada
    /*
    $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE idkategori='$idkategori'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                  alert('ID Kategori sudah ada, silakan gunakan ID lain!');
                  window.location='../index.php?halaman=tambahkategori';
              </script>";
        exit;
    }
    */

    // Simpan ke database (Hanya INSERT namakategori)
    $sql = "INSERT INTO kategori (namakategori) VALUES ('$namakategori')";
    mysqli_query($koneksi, $sql);

    // Anda perlu memastikan form tambah kategori mengirim data ke URL ini (index.php?halaman=kategori&proses=tambah)
    // Jika form di file kategori.php yang sebelumnya sudah diubah, ini tidak akan terpakai.
    header("Location: ../index.php?halaman=kategori");
    exit;
}

/* ==========================================
    EDIT KATEGORI (Membutuhkan idkategori dari URL/tersembunyi)
========================================== */
if ($proses == 'edit') {
    // Asumsi: idkategori dikirim melalui POST tersembunyi/URL dari form edit.
    // Jika tidak, Anda harus mengambilnya dari GET URL atau POST.
    $idkategori = mysqli_real_escape_string($koneksi, $_POST['idkategori'] ?? $_GET['idkategori']);
    $namakategori = mysqli_real_escape_string($koneksi, $_POST['namakategori']);

    $sql = "UPDATE kategori SET namakategori='$namakategori' WHERE idkategori='$idkategori'";
    mysqli_query($koneksi, $sql);

    header("Location: ../index.php?halaman=kategori");
    exit;
}

/* ==========================================
    HAPUS KATEGORI (Membutuhkan idkategori dari URL)
========================================== */
if ($proses == 'hapus') {
    $idkategori = mysqli_real_escape_string($koneksi, $_GET['idkategori']);

    mysqli_query($koneksi, "DELETE FROM kategori WHERE idkategori='$idkategori'");
    header("Location: ../index.php?halaman=kategori");
    exit;
}
?>