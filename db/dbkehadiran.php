<?php
// Ganti path jika koneksi.php berada di lokasi lain
require_once '../koneksi.php'; 

// =======================================================
// TAMBAH DATA KEHADIRAN (Dipicu oleh Form di tambahkehadiran.php)
// =======================================================
if (isset($_POST['simpan'])) { 
    $idsiswa           = mysqli_real_escape_string($koneksi, $_POST['idsiswa']);
     $idadmin           = mysqli_real_escape_string($koneksi, $_POST['idadmin']);  // Jika idadmin dibutuhkan
    $tanggal           = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $statuskehadiran   = mysqli_real_escape_string($koneksi, $_POST['statuskehadiran']);

    $query = "INSERT INTO kehadiran (idsiswa, idadmin, tanggal, statuskehadiran)
              VALUES ('$idsiswa', '$idadmin', '$tanggal', '$statuskehadiran')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=kehadiran&pesan=berhasil_tambah");
        exit;
    } else {
        echo "Gagal menambah data kehadiran: " . mysqli_error($koneksi);
    }
}

// =======================================================
// EDIT DATA KEHADIRAN (Dipicu oleh Form di editkehadiran.php)
// =======================================================
if (isset($_POST['edit'])) { 
    $idkehadiran       = mysqli_real_escape_string($koneksi, $_POST['idkehadiran']);
    $idsiswa           = mysqli_real_escape_string($koneksi, $_POST['idsiswa']);
    $idadmin           = mysqli_real_escape_string($koneksi, $_POST['idadmin']);
    $tanggal           = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $statuskehadiran   = mysqli_real_escape_string($koneksi, $_POST['statuskehadiran']);

    $query = "UPDATE kehadiran 
              SET idsiswa='$idsiswa',
                  idadmin='$idadmin',
                  tanggal='$tanggal',
                  statuskehadiran='$statuskehadiran'
              WHERE idkehadiran='$idkehadiran'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=kehadiran&pesan=berhasil_edit");
        exit;
    } else {
        echo "Gagal mengedit data kehadiran: " . mysqli_error($koneksi);
    }
}

// =======================================================
// HAPUS DATA KEHADIRAN (Dipicu oleh link di kehadiran.php)
// =======================================================
if (isset($_GET['hapus'])) { 
    $idkehadiran = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query = "DELETE FROM kehadiran WHERE idkehadiran='$idkehadiran'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=kehadiran&pesan=berhasil_hapus");
        exit;
    } else {
        echo "Gagal menghapus data kehadiran: " . mysqli_error($koneksi);
    }
}
?>


