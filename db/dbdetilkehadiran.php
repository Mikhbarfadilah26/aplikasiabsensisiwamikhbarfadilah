<?php
// Ganti path jika koneksi.php berada di lokasi lain
require_once '../koneksi.php'; 

// =======================================================
// TAMBAH DATA DETIL KEHADIRAN (Dipicu oleh Form di tambahdetilkehadiran.php)
// =======================================================
if (isset($_POST['simpan'])) { 
    // Ambil data yang dibutuhkan dari form
    $idabsen        = mysqli_real_escape_string($koneksi, $_POST['idabsen']);
    $idkehadiran    = mysqli_real_escape_string($koneksi, $_POST['idkehadiran']);
    $waktuabsen     = mysqli_real_escape_string($koneksi, $_POST['waktuabsen']);
    $fotopath       = mysqli_real_escape_string($koneksi, $_POST['fotopath']);
    $device         = mysqli_real_escape_string($koneksi, $_POST['device']);

    $query = "INSERT INTO detilkehadiran (idabsen, idkehadiran, waktuabsen, fotopath, device)
              VALUES ('$idabsen', '$idkehadiran', '$waktuabsen', '$fotopath', '$device')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=detilkehadiran&pesan=berhasil_tambah");
        exit;
    } else {
        echo "Gagal menambah data detil kehadiran: " . mysqli_error($koneksi);
    }
}

// =======================================================
// EDIT DATA DETIL KEHADIRAN (Dipicu oleh Form di editdetilkehadiran.php)
// =======================================================
if (isset($_POST['edit'])) { 
    $iddetilkehadiran = mysqli_real_escape_string($koneksi, $_POST['iddetilkehadiran']);
    $idabsen          = mysqli_real_escape_string($koneksi, $_POST['idabsen']);
    $idkehadiran      = mysqli_real_escape_string($koneksi, $_POST['idkehadiran']);
    $waktuabsen       = mysqli_real_escape_string($koneksi, $_POST['waktuabsen']);
    $fotopath         = mysqli_real_escape_string($koneksi, $_POST['fotopath']);
    $device           = mysqli_real_escape_string($koneksi, $_POST['device']);

    $query = "UPDATE detilkehadiran 
              SET idabsen='$idabsen',
                  idkehadiran='$idkehadiran',
                  waktuabsen='$waktuabsen',
                  fotopath='$fotopath',
                  device='$device'
              WHERE iddetilkehadiran='$iddetilkehadiran'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=detilkehadiran&pesan=berhasil_edit");
        exit;
    } else {
        echo "Gagal mengedit data detil kehadiran: " . mysqli_error($koneksi);
    }
}

// =======================================================
// HAPUS DATA DETIL KEHADIRAN (Dipicu oleh link di detilkehadiran.php)
// =======================================================
if (isset($_GET['hapus'])) { 
    $iddetilkehadiran = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query = "DELETE FROM detilkehadiran WHERE iddetilkehadiran='$iddetilkehadiran'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=detilkehadiran&pesan=berhasil_hapus");
        exit;
    } else {
        echo "Gagal menghapus data detil kehadiran: " . mysqli_error($koneksi);
    }
}
?>