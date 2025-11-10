<?php
// db/dbabsen.php
include '../koneksi.php';

// Folder upload
$uploadDir = '../foto/keterangan/';

// Pastikan folder tersedia
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ========== SIMPAN ABSEN BARU ==========
if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $idsiswa = $_POST['idsiswa'];
    $id_status = $_POST['id_status'];
    $id_pola = $_POST['id_pola'] ?? null;
    $jammasuk = $_POST['jammasuk'] ?? date('H:i:s');
    $jamkeluar = $_POST['jamkeluar'] ?? null;
    $keterangan = $_POST['keterangan'] ?? null;
    $file_bukti = null;

    // Upload file hanya jika izin/sakit
    if (in_array($id_status, ['2', '3'])) { // 2 = Izin, 3 = Sakit
        if (isset($_FILES['file_bukti']) && $_FILES['file_bukti']['error'] == 0) {
            $namaFile = time() . '_' . basename($_FILES['file_bukti']['name']);
            $targetFile = $uploadDir . $namaFile;

            if (move_uploaded_file($_FILES['file_bukti']['tmp_name'], $targetFile)) {
                $file_bukti = $namaFile;
            } else {
                $keterangan .= " (Gagal upload bukti)";
            }
        } else {
            $keterangan .= " (Bukti wajib diunggah)";
        }
    }

    $query = "INSERT INTO absen (idsiswa, tanggal, jammasuk, jamkeluar, id_status, id_pola, keterangan, file_bukti)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'isssiiss', $idsiswa, $tanggal, $jammasuk, $jamkeluar, $id_status, $id_pola, $keterangan, $file_bukti);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data absensi berhasil disimpan!'); window.location='../index.php?halaman=absen';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!'); history.back();</script>";
    }
}

// ========== EDIT ABSEN ==========
if (isset($_POST['edit'])) {
    $idabsen = $_POST['idabsen'];
    $idsiswa = $_POST['idsiswa'];
    $id_status = $_POST['id_status'];
    $id_pola = $_POST['id_pola'];
    $tanggal = $_POST['tanggal'];
    $jammasuk = $_POST['jammasuk'];
    $jamkeluar = $_POST['jamkeluar'];
    $keterangan = $_POST['keterangan'] ?? null;
    $file_bukti = null;

    // Ambil file lama
    $result = mysqli_query($koneksi, "SELECT file_bukti FROM absen WHERE idabsen='$idabsen'");
    $old = mysqli_fetch_assoc($result);
    $old_file = $old['file_bukti'];

    // Upload baru jika izin/sakit
    if (in_array($id_status, ['2', '3'])) {
        if (isset($_FILES['file_bukti']) && $_FILES['file_bukti']['error'] == 0) {
            $namaFile = time() . '_' . basename($_FILES['file_bukti']['name']);
            $targetFile = $uploadDir . $namaFile;

            if (move_uploaded_file($_FILES['file_bukti']['tmp_name'], $targetFile)) {
                $file_bukti = $namaFile;
                // hapus file lama
                if ($old_file && file_exists($uploadDir . $old_file)) {
                    unlink($uploadDir . $old_file);
                }
            }
        } else {
            $file_bukti = $old_file; // tidak upload ulang
        }
    } else {
        // kalau status bukan izin/sakit, hapus file lama
        if ($old_file && file_exists($uploadDir . $old_file)) {
            unlink($uploadDir . $old_file);
        }
        $file_bukti = null;
    }

    $query = "UPDATE absen 
              SET idsiswa=?, id_status=?, id_pola=?, tanggal=?, jammasuk=?, jamkeluar=?, keterangan=?, file_bukti=? 
              WHERE idabsen=?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'iiisssssi', $idsiswa, $id_status, $id_pola, $tanggal, $jammasuk, $jamkeluar, $keterangan, $file_bukti, $idabsen);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data absensi berhasil diperbarui!'); window.location='../index.php?halaman=absen';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!'); history.back();</script>";
    }
}

// ========== HAPUS ABSEN ==========
if (isset($_GET['hapus'])) {
    $idabsen = $_GET['hapus'];
    $res = mysqli_query($koneksi, "SELECT file_bukti FROM absen WHERE idabsen='$idabsen'");
    $data = mysqli_fetch_assoc($res);
    if ($data['file_bukti'] && file_exists($uploadDir . $data['file_bukti'])) {
        unlink($uploadDir . $data['file_bukti']);
    }
    mysqli_query($koneksi, "DELETE FROM absen WHERE idabsen='$idabsen'");
    echo "<script>alert('Data absen berhasil dihapus!'); window.location='../index.php?halaman=absen';</script>";
}
?>
