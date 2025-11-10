<?php
include "../koneksi.php";
session_start();

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

/* ==========================================
    TAMBAH POLA JADWAL
========================================== */
if ($proses == 'tambah') {
    $nama_pola = mysqli_real_escape_string($koneksi, $_POST['nama_pola']);
    $jam_masuk_ideal = mysqli_real_escape_string($koneksi, $_POST['jam_masuk_ideal']);
    $jam_pulang_ideal = mysqli_real_escape_string($koneksi, $_POST['jam_pulang_ideal']);

    $sql = "INSERT INTO polajadwal (nama_pola, jam_masuk_ideal, jam_pulang_ideal)
            VALUES ('$nama_pola', '$jam_masuk_ideal', '$jam_pulang_ideal')";
    mysqli_query($koneksi, $sql);

    header("Location: ../index.php?halaman=polajadwal");
    exit;
}

/* ==========================================
    EDIT POLA JADWAL
========================================== */
if ($proses == 'edit') {
    $idpola = $_POST['idpola'];
    $nama_pola = mysqli_real_escape_string($koneksi, $_POST['nama_pola']);
    $jam_masuk_ideal = mysqli_real_escape_string($koneksi, $_POST['jam_masuk_ideal']);
    $jam_pulang_ideal = mysqli_real_escape_string($koneksi, $_POST['jam_pulang_ideal']);

    $sql = "UPDATE polajadwal SET 
                nama_pola='$nama_pola',
                jam_masuk_ideal='$jam_masuk_ideal',
                jam_pulang_ideal='$jam_pulang_ideal'
            WHERE idpola='$idpola'";
    mysqli_query($koneksi, $sql);

    header("Location: ../index.php?halaman=polajadwal");
    exit;
}

/* ==========================================
    HAPUS POLA JADWAL
========================================== */
if ($proses == 'hapus') {
    $idpola = $_GET['idpola'];
    mysqli_query($koneksi, "DELETE FROM polajadwal WHERE idpola='$idpola'");
    header("Location: ../index.php?halaman=polajadwal");
    exit;
}
?>
