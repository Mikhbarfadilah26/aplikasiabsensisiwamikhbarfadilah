<?php
// views/status_absen/editstatus_absen.php

include "koneksi.php";

// 1. PENCEGAHAN WARNING PHP: Cek apakah 'id_status' ada di URL
if (!isset($_GET['id_status']) || empty($_GET['id_status'])) {
    // Jika tidak ada ID, redirect kembali atau tampilkan pesan error
    echo "<script>alert('ID Status tidak ditemukan!'); window.location.href='index.php?halaman=status_absen';</script>";
    exit();
}

// 2. KEAMANAN: Bersihkan input dari URL untuk mencegah SQL Injection
$id_status = mysqli_real_escape_string($koneksi, $_GET['id_status']);

$query = mysqli_query($koneksi, "SELECT * FROM status_absen WHERE id_status='$id_status'");

// 3. PENGECEKAN DATA: Pastikan data ditemukan
if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Data Status Absen tidak ditemukan!'); window.location.href='index.php?halaman=status_absen';</script>";
    exit();
}

$data = mysqli_fetch_assoc($query);
?>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h3 class="card-title">📝 Edit Status Absen</h3>
        </div>

        <form action="db/dbstatus_absen.php?proses=edit" method="POST">
            <div class="card-body">
                <input type="hidden" name="id_status" value="<?= htmlspecialchars($data['id_status']); ?>">

                <div class="form-group mb-3">
                    <label for="nama_status">Nama Status</label>
                    <input 
                        type="text" 
                        name="nama_status" 
                        id="nama_status"
                        value="<?= htmlspecialchars($data['nama_status']); ?>" 
                        class="form-control" 
                        required>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                <a href="index.php?halaman=status_absen" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
            </div>
        </form>
    </div>
</section>