<?php
// db/dbabsen.php
require_once '../koneksi.php'; 

// =======================================================
// TAMBAH DATA ABSEN (DENGAN JAM MASUK)
// =======================================================
if (isset($_POST['simpan'])) { 
    $idsiswa    = (int)$_POST['idsiswa'];
    
    // Ambil data, gunakan string kosong jika kosong untuk menghindari Deprecated Warning saat escape
    $tanggal_raw = $_POST['tanggal'] ?? '';
    $idkategori_raw = $_POST['idkategori'] ?? '';
    $jammasuk_raw = $_POST['jammasuk'] ?? date('H:i:s'); // Default waktu saat ini jika input kosong
    $jamkeluar_raw = $_POST['jamkeluar'] ?? null; // Biarkan NULL jika input kosong

    // Escape string (hanya untuk data yang berupa string)
    $tanggal    = mysqli_real_escape_string($koneksi, $tanggal_raw);
    $idkategori = (int)$idkategori_raw; 
    $jammasuk   = mysqli_real_escape_string($koneksi, $jammasuk_raw); 
    
    // Escape dan format jamkeluar: 'NULL' jika NULL, atau 'nilai_jam' jika ada
    $jamkeluar_escaped = $jamkeluar_raw ? mysqli_real_escape_string($koneksi, $jamkeluar_raw) : NULL;

    // Query INSERT menggunakan kolom jammasuk & jamkeluar
    $query = "INSERT INTO absen (idsiswa, tanggal, idkategori, jammasuk, jamkeluar)
              VALUES ('$idsiswa', '$tanggal', '$idkategori', '$jammasuk', " . ($jamkeluar_escaped ? "'$jamkeluar_escaped'" : "NULL") . ")";
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=absen&pesan=berhasil_tambah");
        exit;
    } else {
        // Tampilkan error SQL jika gagal
        echo "Gagal menambah data: " . mysqli_error($koneksi); 
    }
}

// =======================================================
// EDIT DATA ABSEN (DENGAN JAM MASUK)
// =======================================================
if (isset($_POST['edit'])) { 
    $idabsen    = (int)$_POST['idabsen'];
    
    // Ambil data POST
    $idsiswa_raw = $_POST['idsiswa'] ?? '';
    $tanggal_raw = $_POST['tanggal'] ?? '';
    $idkategori_raw = $_POST['idkategori'] ?? '';
    $jammasuk_raw = $_POST['jammasuk'] ?? null;
    $jamkeluar_raw = $_POST['jamkeluar'] ?? null; 

    // Escape string
    $idsiswa    = (int)$idsiswa_raw;
    $tanggal    = mysqli_real_escape_string($koneksi, $tanggal_raw);
    $idkategori = (int)$idkategori_raw;
    
    // Escape dan format jammasuk/jamkeluar: 'NULL' jika NULL, atau 'nilai_jam' jika ada
    $jammasuk_escaped = $jammasuk_raw ? mysqli_real_escape_string($koneksi, $jammasuk_raw) : NULL;
    $jamkeluar_escaped = $jamkeluar_raw ? mysqli_real_escape_string($koneksi, $jamkeluar_raw) : NULL;

    // Update Query
    $query = "UPDATE absen 
              SET idsiswa='$idsiswa',
                  tanggal='$tanggal',
                  idkategori='$idkategori',
                  jammasuk=" . ($jammasuk_escaped ? "'$jammasuk_escaped'" : "NULL") . ",
                  jamkeluar=" . ($jamkeluar_escaped ? "'$jamkeluar_escaped'" : "NULL") . "
              WHERE idabsen='$idabsen'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: ../index.php?halaman=absen&pesan=berhasil_edit");
        exit;
    } else {
        echo "Gagal mengedit data: " . mysqli_error($koneksi);
    }
}

// =======================================================
// HAPUS DATA ABSEN
// =======================================================
if (isset($_GET['hapus'])) {
    $idabsen = (int)$_GET['hapus'];
    $query_hapus = "DELETE FROM absen WHERE idabsen = '$idabsen'";
    $result_hapus = mysqli_query($koneksi, $query_hapus);

    if ($result_hapus) {
        header("Location: ../index.php?halaman=absen&pesan=berhasil_hapus");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}
?>