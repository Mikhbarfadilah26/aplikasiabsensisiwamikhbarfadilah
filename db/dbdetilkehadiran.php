<?php
// Pastikan koneksi ditemukan
require_once __DIR__ . '/../koneksi.php';


// =======================================================
// TAMBAH DATA DETIL KEHADIRAN
// =======================================================
if (isset($_POST['simpan'])) {
    $idabsen     = (int)($_POST['idabsen'] ?? 0);
    $idkehadiran = (int)($_POST['idkehadiran'] ?? 0);
    $waktuabsen  = mysqli_real_escape_string($koneksi, $_POST['waktuabsen'] ?? date('Y-m-d H:i:s'));
    $device      = mysqli_real_escape_string($koneksi, $_POST['device'] ?? 'Tidak diketahui');

    // === Upload Foto (Opsional) ===
    $fotopath = null;
    if (!empty($_FILES['fotopath']['name'])) {
        $target_dir  = "../uploads/absen/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES['fotopath']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['fotopath']['tmp_name'], $target_file)) {
            $fotopath = "uploads/absen/" . $filename; // Simpan path relatif
        } else {
            echo "Gagal mengunggah file foto.";
            exit;
        }
    }

    // === Query Tambah ===
    $query = "INSERT INTO detilkehadiran (idabsen, idkehadiran, waktuabsen, fotopath, device)
              VALUES ('$idabsen', '$idkehadiran', '$waktuabsen', " . 
              ($fotopath ? "'$fotopath'" : "NULL") . ", '$device')";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=detilkehadiran&pesan=berhasil_tambah");
        exit;
    } else {
        echo "❌ Gagal menambah data detil kehadiran: " . mysqli_error($koneksi);
    }
}



// =======================================================
// EDIT DATA DETIL KEHADIRAN
// =======================================================
if (isset($_POST['edit'])) {
    $iddetilkehadiran = (int)($_POST['iddetilkehadiran'] ?? 0);
    $idabsen     = (int)($_POST['idabsen'] ?? 0);
    $idkehadiran = (int)($_POST['idkehadiran'] ?? 0);
    $waktuabsen  = mysqli_real_escape_string($koneksi, $_POST['waktuabsen'] ?? date('Y-m-d H:i:s'));
    $device      = mysqli_real_escape_string($koneksi, $_POST['device'] ?? 'Tidak diketahui');

    // === Upload Foto (Opsional, jika ingin ganti) ===
    $fotopath = null;
    if (!empty($_FILES['fotopath']['name'])) {
        $target_dir  = "../uploads/absen/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES['fotopath']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['fotopath']['tmp_name'], $target_file)) {
            $fotopath = "uploads/absen/" . $filename;
        } else {
            echo "Gagal mengunggah file foto baru.";
            exit;
        }
    }

    // === Query Update ===
    $query = "UPDATE detilkehadiran 
              SET idabsen='$idabsen',
                  idkehadiran='$idkehadiran',
                  waktuabsen='$waktuabsen',
                  device='$device'";

    if ($fotopath) {
        $query .= ", fotopath='$fotopath'";
    }

    $query .= " WHERE iddetilkehadiran='$iddetilkehadiran'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=detilkehadiran&pesan=berhasil_edit");
        exit;
    } else {
        echo "❌ Gagal mengedit data detil kehadiran: " . mysqli_error($koneksi);
    }
}



// =======================================================
// HAPUS DATA DETIL KEHADIRAN
// =======================================================
if (isset($_GET['hapus'])) {
    $iddetilkehadiran = (int)$_GET['hapus'];

    // Cek apakah ada foto yang perlu dihapus dari server
    $q_cek = mysqli_query($koneksi, "SELECT fotopath FROM detilkehadiran WHERE iddetilkehadiran='$iddetilkehadiran'");
    $data_foto = mysqli_fetch_assoc($q_cek);
    if (!empty($data_foto['fotopath']) && file_exists("../" . $data_foto['fotopath'])) {
        unlink("../" . $data_foto['fotopath']);
    }

    $query = "DELETE FROM detilkehadiran WHERE iddetilkehadiran='$iddetilkehadiran'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=detilkehadiran&pesan=berhasil_hapus");
        exit;
    } else {
        echo "❌ Gagal menghapus data detil kehadiran: " . mysqli_error($koneksi);
    }
}
?>
