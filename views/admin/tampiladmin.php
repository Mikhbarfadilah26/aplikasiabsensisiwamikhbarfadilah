<?php
// File: views/admin/tampiladmin.php

// =======================================================
// 1. PENGATURAN KONEKSI & PENCEGAHAN ERROR
// =======================================================
// Panggil variabel koneksi global yang harusnya sudah ada dari index.php
global $koneksi; 

// Pengecekan dasar apakah koneksi sudah ada
if (!isset($koneksi) || $koneksi === false) {
    die('<div class="alert alert-danger">FATAL ERROR: Koneksi database tidak tersedia. Pastikan koneksi.php sudah di-include di index.php.</div>');
}

// 2. Pastikan ID Admin tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect jika tidak ada ID
    header("Location: index.php?halaman=admin");
    exit;
}

// Ambil dan bersihkan ID Admin (Menggunakan integer casting sesuai link Anda)
$idadmin_target = (int)$_GET['id']; 

// =======================================================
// 3. QUERY DATA DENGAN PREPARED STATEMENT (Keamanan)
// =======================================================

// Kolom yang ada di database Anda berdasarkan gambar: idadmin, username, namaadmin, nohp, fotoadmin
$stmt = $koneksi->prepare("
    SELECT idadmin, username, namaadmin, nohp, fotoadmin, 
           -- Kami asumsikan 'status' dan 'role' ada, atau kita gunakan nilai default
           'Disetujui' as status, 
           'Admin' as role,
           NOW() as tgl_persetujuan 
    FROM admin
    WHERE idadmin = ?
");

if (!$stmt) {
    // Tampilkan error jika query gagal disiapkan
    die('<div class="alert alert-danger">Query Preparation Error: ' . $koneksi->error . '</div>'); 
}

// Bind ID sebagai integer ('i')
$stmt->bind_param("i", $idadmin_target);
$stmt->execute();
$query = $stmt->get_result();

if ($query->num_rows == 0) {
    echo '<div class="alert alert-danger">Admin tidak ditemukan.</div>';
    $stmt->close();
    exit;
}

$data_admin = $query->fetch_assoc();
$stmt->close();

// Tentukan sumber foto dan status
$foto_src = !empty($data_admin['fotoadmin']) ? 
    'foto/admin/' . htmlspecialchars($data_admin['fotoadmin']) : 
    'dist/img/default-user.jpg'; // Mengganti default-user.png ke default-user.jpg sesuai kode awal Anda
    
$status_badge = ($data_admin['status'] == 'Disetujui') ? 
    '<span class="badge bg-success">Disetujui</span>' : 
    '<span class="badge bg-danger">Belum Disetujui</span>';
?>

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Detail Admin</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-widget widget-user shadow-lg">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center border-right">
                        <img class="img-circle elevation-2 mb-3" 
                              src="<?= $foto_src ?>" 
                              alt="Foto Admin"
                              style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #007bff;">
                        
                        <h4 class="mt-2"><?= htmlspecialchars($data_admin['namaadmin']); ?></h4>
                        <p class="text-muted"><?= htmlspecialchars($data_admin['role']); ?></p>
                        <a href="index.php?halaman=editadmin&id=<?= $data_admin['idadmin']; ?>" 
                           class="btn btn-warning btn-sm mt-2">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                    </div>

                    <div class="col-md-6 border-right">
                        <h4 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> Informasi Lengkap</h4>
                        
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong><i class="fas fa-user"></i> Nama:</strong> <?= htmlspecialchars($data_admin['namaadmin']); ?></li>
                            <li class="mb-2"><strong><i class="fas fa-at"></i> Username:</strong> @<?= htmlspecialchars($data_admin['username']); ?></li>
                            <li class="mb-2"><strong><i class="fas fa-lock"></i> Password:</strong> ******** <small class="text-muted">(Tidak ditampilkan untuk keamanan)</small></li>
                            <li class="mb-2"><strong><i class="fas fa-phone-alt"></i> No. HP:</strong> <?= htmlspecialchars($data_admin['nohp']); ?></li>
                            <li class="mb-2"><strong><i class="fas fa-shield-alt"></i> Role:</strong> <?= htmlspecialchars($data_admin['role']); ?></li>
                            <li class="mb-2"><strong><i class="fas fa-check-circle"></i> Status Akun:</strong> <?= $status_badge; ?></li>
                            <li class="mb-2"><strong><i class="fas fa-calendar-alt"></i> Tgl. Persetujuan:</strong> <?= date('d M Y', strtotime($data_admin['tgl_persetujuan'])); ?></li>
                        </ul>
                    </div>

                    <div class="col-md-3 text-center">
                        <h4 class="mb-3 text-danger"><i class="fas fa-tools"></i> Aksi Cepat</h4>
                        <a href="db/dbadmin.php?proses=hapus&idadmin=<?= $data_admin['idadmin']; ?>"
                           class="btn btn-danger mt-2"
                           onclick="return confirm('Yakin ingin menghapus Admin ini?');">
                            <i class="fas fa-trash"></i> Hapus Admin
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-footer text-center">
                <a href="index.php?halaman=admin" class="btn btn-secondary">
                    <i class="fas fa-arrow-circle-left"></i> Kembali ke Daftar Admin
                </a>
            </div>
        </div>
    </div>
</section>