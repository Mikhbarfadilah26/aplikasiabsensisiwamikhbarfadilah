<?php
include '../koneksi.php';
session_start();

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

/* ==========================================
    TAMBAH STATUS ABSEN
========================================== */
if ($proses == 'tambah') {
    $nama_status = mysqli_real_escape_string($koneksi, $_POST['nama_status']);
    $sql = "INSERT INTO status_absen (nama_status) VALUES ('$nama_status')";
    mysqli_query($koneksi, $sql);

    header("Location: ../index.php?halaman=status_absen");
    exit;
}

/* ==========================================
    EDIT STATUS ABSEN
========================================== */
if ($proses == 'edit') {
    $id_status = $_POST['id_status'];
    $nama_status = mysqli_real_escape_string($koneksi, $_POST['nama_status']);
    $sql = "UPDATE status_absen SET nama_status='$nama_status' WHERE id_status='$id_status'";
    mysqli_query($koneksi, $sql);

    header("Location: ../index.php?halaman=status_absen");
    exit;
}

/* ==========================================
    HAPUS STATUS ABSEN
========================================== */
if ($proses == 'hapus') {
    $id_status = $_GET['id_status'];
    mysqli_query($koneksi, "DELETE FROM status_absen WHERE id_status='$id_status'");
    header("Location: ../index.php?halaman=status_absen");
    exit;
}
?>
