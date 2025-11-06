<section class="content">

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chalkboard-teacher mr-1"></i>
                Daftar Seluruh Kelas
            </h3>
            <div class="card-tools">
                <a href="index.php?halaman=tambahkelas" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> Tambah Kelas
                </a>
            </div>
        </div>
    </div>
    
    <?php
    // PATH KONEKSI: Pastikan ini sesuai dengan lokasi file Anda.
    include __DIR__ . '/../../koneksi.php';

    // 1. Ambil SEMUA data kelas dan kelompokkan di PHP
    $sqlkelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY namakelas ASC");
    
    // Array untuk menampung kelas berdasarkan tingkatan
    $grouped_classes = [
        'X' => [],
        'XI' => [],
        'XII' => [],
        'Lainnya' => []
    ];

    while ($datakelas = mysqli_fetch_array($sqlkelas)) {
        $nama = $datakelas['namakelas'];
        // Deteksi tingkatan kelas dari huruf pertama (X, XI, XII)
        if (str_starts_with($nama, 'X ')) {
            $grouped_classes['X'][] = $datakelas;
        } elseif (str_starts_with($nama, 'XI ')) {
            $grouped_classes['XI'][] = $datakelas;
        } elseif (str_starts_with($nama, 'XII ')) {
            $grouped_classes['XII'][] = $datakelas;
        } else {
            $grouped_classes['Lainnya'][] = $datakelas;
        }
    }
    
    // 2. Loop dan Tampilkan setiap kelompok kelas
    foreach ($grouped_classes as $tingkat => $classes) :
        // Hanya tampilkan kelompok yang memiliki kelas
        if (!empty($classes)) :
    ?>
    
    <h4 class="mt-4 mb-2" style="color: #fff; border-bottom: 2px solid #007bff; padding-bottom: 5px;">
        Tingkat Kelas <?= $tingkat; ?>
    </h4>

    <div class="row">
        <?php foreach ($classes as $datakelas) : 
            
            // Tentukan path foto
            $foto_path = (!empty($datakelas['fotokelas'])) 
                         ? 'foto/kelas/' . htmlspecialchars($datakelas['fotokelas'])
                         : 'dist/img/default-class.png'; // Pastikan path ini benar
        ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                <div class="info-box class-card text-center" style="box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-radius: 10px; overflow: hidden;">
                    
                    <div class="class-image-container">
                        <img src="<?= $foto_path; ?>" 
                             alt="<?= htmlspecialchars($datakelas['namakelas']); ?>" 
                             class="img-fluid class-image">
                    </div>
                    
                    <div class="info-box-content">
                        <span class="info-box-number class-name-bold mb-1">
                            <?= htmlspecialchars($datakelas['namakelas']); ?>
                        </span>
                        <span class="info-box-text text-muted mb-3">ID Kelas: <?= $datakelas['idkelas']; ?></span>
                        
                        <div class="class-actions mt-2">
                            <a href="index.php?halaman=editkelas&id=<?= $datakelas['idkelas']; ?>" 
                                class="btn btn-sm btn-warning mx-1" title="Edit">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="db/dbkelas.php?proses=hapus&idkelas=<?= $datakelas['idkelas']; ?>" 
                                class="btn btn-sm btn-danger mx-1" title="Hapus"
                                onclick="return confirm('Yakin ingin menghapus data kelas <?= htmlspecialchars($datakelas['namakelas']); ?>?');">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php 
        endif; // End if !empty($classes)
    endforeach; // End foreach grouped_classes
    
    // Tambahkan pesan jika tidak ada kelas sama sekali (Opsional)
    if (empty($grouped_classes['X']) && empty($grouped_classes['XI']) && empty($grouped_classes['XII']) && empty($grouped_classes['Lainnya'])) {
        echo '<div class="alert alert-info mt-4">Belum ada data kelas yang ditambahkan.</div>';
    }
    ?>
    
</section>

<style>
    /* Hapus custom CSS 5 kolom sebelumnya */

    /* Style Card Kelas */
    .class-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #2b3543; /* Darker background for contrast */
        /* Sesuaikan min-height karena 3 kolom akan lebih lebar */
        min-height: 280px; 
    }

    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.5) !important;
        border-color: #007bff;
    }

    .class-image-container {
        height: 150px; /* Diperbesar karena 3 kolom lebih lebar */
        overflow: hidden;
        margin-bottom: 10px;
        border-bottom: 3px solid #007bff;
    }

    .class-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .class-card:hover .class-image {
        transform: scale(1.05);
    }
    
    .info-box-content {
        padding: 10px;
    }
    
    /* CSS untuk membuat nama kelas bold dan bagus */
    .class-name-bold {
        font-size: 24px; /* Ukuran lebih besar */
        font-weight: 700 !important; /* Bold */
        color: #00ffcc !important; /* Warna aksen yang menonjol */
        display: block;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .class-actions {
        padding-bottom: 10px;
    }
    
    .class-actions .btn {
        font-size: 14px;
    }
</style>