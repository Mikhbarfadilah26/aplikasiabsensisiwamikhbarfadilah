<?php
// =======================================================
// FILE: db/dbkehadiran.php
// FUNGSI: Tambah, Edit, Hapus data kehadiran siswa
// =======================================================

require_once '../koneksi.php';
session_start(); // Wajib untuk ambil data admin login

// =======================================================
// CEK LOGIN ADMIN
// =======================================================
if (!isset($_SESSION['idadmin']) || $_SESSION['idadmin'] <= 0) {
    echo "Session admin belum aktif atau tidak valid.";
    exit;
}

$idadmin = (int)$_SESSION['idadmin'];

// =======================================================
// TAMBAH DATA KEHADIRAN
// =======================================================
if (isset($_POST['simpan'])) {
    $idsiswa   = isset($_POST['idsiswa']) ? (int)$_POST['idsiswa'] : 0;
    $tanggal   = !empty($_POST['tanggal']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal']) : date('Y-m-d');
    $id_status = isset($_POST['id_status']) ? (int)$_POST['id_status'] : 0;

    // Validasi data
    if ($idsiswa <= 0 || $id_status <= 0) {
        echo "Data siswa atau status tidak valid.";
        exit;
    }

    $query = "INSERT INTO kehadiran (idsiswa, idadmin, tanggal, id_status)
              VALUES ('$idsiswa', '$idadmin', '$tanggal', '$id_status')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=kehadiran&pesan=berhasil_tambah");
        exit;
    } else {
        echo "❌ Gagal menambah data kehadiran: " . mysqli_error($koneksi);
        exit;
    }
}

// =======================================================
// EDIT DATA KEHADIRAN
// =======================================================
if (isset($_POST['edit'])) {
    $idkehadiran = isset($_POST['idkehadiran']) ? (int)$_POST['idkehadiran'] : 0;
    $idsiswa     = isset($_POST['idsiswa']) ? (int)$_POST['idsiswa'] : 0;
    $tanggal     = !empty($_POST['tanggal']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal']) : date('Y-m-d');
    $id_status   = isset($_POST['id_status']) ? (int)$_POST['id_status'] : 0;

    if ($idkehadiran <= 0 || $idsiswa <= 0 || $id_status <= 0) {
        echo "Data kehadiran tidak valid.";
        exit;
    }

    $query = "UPDATE kehadiran 
              SET idsiswa='$idsiswa',
                  idadmin='$idadmin',
                  tanggal='$tanggal',
                  id_status='$id_status'
              WHERE idkehadiran='$idkehadiran'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=kehadiran&pesan=berhasil_edit");
        exit;
    } else {
        echo "❌ Gagal mengedit data kehadiran: " . mysqli_error($koneksi);
        exit;
    }
}

// =======================================================
// HAPUS DATA KEHADIRAN
// =======================================================
if (isset($_GET['hapus'])) {
    $idkehadiran = isset($_GET['hapus']) ? (int)$_GET['hapus'] : 0;

    if ($idkehadiran <= 0) {
        echo "ID kehadiran tidak valid.";
        exit;
    }

    $query = "DELETE FROM kehadiran WHERE idkehadiran='$idkehadiran'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=kehadiran&pesan=berhasil_hapus");
        exit;
    } else {
        echo "❌ Gagal menghapus data kehadiran: " . mysqli_error($koneksi);
        exit;
    }
}
