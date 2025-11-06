<section class="content">
    <div class="card bg-transparent border-0">
        <div class="card-header bg-gradient-primary shadow-sm rounded-3 mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="text-white fw-bold mb-0" style="font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-user-shield me-2"></i>Halaman Tampil Admin
                    </h5>
                </div>
                <div class="col text-end">
                    <a href="index.php?halaman=tambahadmin" class="btn btn-light btn-sm shadow-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Admin
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row" id="adminContainer">
                <?php
                // ASUMSI: $koneksi sudah didefinisikan (misalnya di index.php)
                // Jika tidak, pastikan path include koneksi.php sudah benar:
                include __DIR__ . '/../../koneksi.php'; 
                
                $no = 1;
                // Query mengambil semua data yang dibutuhkan
                $sqladmin = mysqli_query($koneksi, "SELECT idadmin, username, namaadmin, fotoadmin FROM admin ORDER BY namaadmin ASC");
                if (!$sqladmin) die("Query Error: " . mysqli_error($koneksi));

                $data_ditemukan = false;

                while ($dataadmin = mysqli_fetch_array($sqladmin)) :
                    $data_ditemukan = true;
                    // Tentukan sumber foto
                    $foto_src = !empty($dataadmin['fotoadmin']) ? 
                        'foto/admin/' . htmlspecialchars($dataadmin['fotoadmin']) : 
                        'dist/img/default-user.jpg'; // Ganti dengan path default Anda
                ?>
                    <div class="col-md-3 mb-4 admin-card">
                        <div class="card admin-item h-100">
                            <div class="card-body text-center p-4">
                                <?php if (!empty($dataadmin['fotoadmin'])): ?>
                                    <img src="<?= $foto_src; ?>" 
                                         alt="Foto Admin"
                                         class="rounded-circle mb-3 admin-photo shadow"
                                         width="100" height="100" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary mb-3 d-flex align-items-center justify-content-center admin-photo shadow"
                                         style="width:100px; height:100px;">
                                        <i class="fa fa-user text-white fa-2x"></i>
                                    </div>
                                <?php endif; ?>

                                <h6 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($dataadmin['namaadmin']); ?></h6>
                                <p class="text-muted mb-3">@<?= htmlspecialchars($dataadmin['username']); ?></p>

                                <div class="d-flex justify-content-center gap-2">
                                    <a href="index.php?halaman=tampiladmin&id=<?= $dataadmin['idadmin']; ?>" 
                                       class="btn btn-outline-info btn-sm px-3" title="Lihat Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="index.php?halaman=editadmin&id=<?= $dataadmin['idadmin']; ?>"
                                       class="btn btn-outline-warning btn-sm px-3" title="Edit Data">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="db/dbadmin.php?proses=hapus&idadmin=<?= $dataadmin['idadmin']; ?>"
                                       class="btn btn-outline-danger btn-sm px-3"
                                       onclick="return confirm('Yakin ingin menghapus data admin <?= htmlspecialchars($dataadmin['namaadmin']); ?>?');" title="Hapus Data">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    $no++;
                endwhile;
                
                if (!$data_ditemukan) {
                    echo '<div class="col-12 text-center"><p class="text-muted">Data admin masih kosong.</p></div>';
                }
                ?>
            </div>
        </div>

        <div class="card-footer text-center text-muted">
            <small>© 2025 Sistem Absensi Siswa - Admin Panel</small>
        </div>
    </div>
</section>

<style>
    /* CSS sebelumnya... */
    
    .btn-outline-info {
        color: #17a2b8; /* Warna default info */
        border-color: #17a2b8;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: #fff;
    }
    
    /* ... CSS lainnya ... */
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const cards = document.querySelectorAll('.admin-item');
    cards.forEach((card, i) => {
        card.style.animationDelay = `${i * 0.1}s`;
    });
});
</script>