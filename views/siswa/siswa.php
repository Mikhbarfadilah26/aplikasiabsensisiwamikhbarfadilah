<?php
// views/siswa/siswa.php

// Pastikan koneksi ada
if (!isset($koneksi)) {
    include __DIR__ . '/../../koneksi.php';
}

// Pesan status (add/edit/delete)
$status = $_GET['status'] ?? '';
$pesanMap = [
    'sukses_tambah' => ['Data siswa berhasil ditambahkan.', 'alert-success'],
    'sukses_edit'   => ['Data siswa berhasil diperbarui.', 'alert-warning'],
    'sukses_hapus'  => ['Data siswa berhasil dihapus.', 'alert-danger']
];
$pesan = $pesanMap[$status][0] ?? '';
$alert_class = $pesanMap[$status][1] ?? '';

// Ambil data siswa + kelas
$sql = mysqli_query($koneksi, "
    SELECT s.idsiswa, s.NIS, s.namasiswa, s.fotosiswa, k.idkelas, k.namakelas
    FROM siswa s
    INNER JOIN kelas k ON s.idkelas = k.idkelas
    ORDER BY k.namakelas ASC, s.namasiswa ASC
");

// Kelompokkan siswa per kelas
$grouped = [];
while ($row = mysqli_fetch_assoc($sql)) {
    $kelas = $row['namakelas'];
    if (!isset($grouped[$kelas])) {
        $grouped[$kelas] = ['idkelas' => $row['idkelas'], 'siswa' => []];
    }
    $grouped[$kelas]['siswa'][] = $row;
}

// Daftar warna elegan untuk tiap kelas (acak tapi terkelola)
$colorList = ['#28a745','#007bff','#fd7e14','#6f42c1','#20c997','#17a2b8','#6610f2','#e83e8c'];
$kelasWarna = [];
$i = 0;
foreach ($grouped as $kelasName => $dataKelas) {
    $kelasWarna[$kelasName] = $colorList[$i % count($colorList)];
    $i++;
}
?>
<?php
// (Asumsikan ini ditempatkan setelah bagian PHP di Bagian 1 dan setelah tag ?>)
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Daftar Siswa Per Kelas</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="index.php?halaman=tambahsiswa" class="btn btn-sm btn-success">
                    <i class="fas fa-user-plus"></i> Tambah Siswa
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <?php if (!empty($pesan)) : ?>
            <div class="alert <?= $alert_class; ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($pesan); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (empty($grouped)) : ?>
            <div class="alert alert-info">Belum ada data siswa yang ditemukan.</div>
        <?php else: ?>

            <!-- Loop per kelas -->
            <?php foreach ($grouped as $kelasName => $kelasData) : 
                $headerColor = $kelasWarna[$kelasName] ?? '#28a745';
                $textColor = '#ffffff';
                // sedikit pengaturan kontras jika warna header terang (opsional)
                ?>
                <div class="card class-card mb-4" style="border-top:4px solid <?= $headerColor ?>;">
                    <div class="card-header p-3" style="background: <?= $headerColor ?>; color: <?= $textColor ?>;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 class-title" style="font-weight:800; letter-spacing:0.4px;">
                                    <?= htmlspecialchars($kelasName); ?>
                                </h4>
                                <small style="opacity:.95;">Daftar siswa per kelas</small>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-light" style="font-weight:700; color:#333;">
                                    <?= count($kelasData['siswa']); ?> Siswa
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($kelasData['siswa'] as $datasiswa) :
                                $foto_path = (!empty($datasiswa['fotosiswa'])) 
                                             ? 'foto/siswa/' . htmlspecialchars($datasiswa['fotosiswa'])
                                             : 'dist/img/default-user.png';
                            ?>
                                <div class="col-6 col-md-4 col-lg-3 d-flex mb-4">
                                    <div class="student-card w-100">
                                        <div class="student-image">
                                            <img src="<?= $foto_path; ?>" 
                                                 alt="<?= htmlspecialchars($datasiswa['namasiswa']); ?>"
                                                 onerror="this.onerror=null; this.src='https://placehold.co/300x220/cccccc/333333?text=No+Photo';">
                                        </div>

                                        <div class="student-body p-3 d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="student-name"><?= htmlspecialchars($datasiswa['namasiswa']); ?></div>
                                                <div class="student-nis text-muted">NIS: <?= htmlspecialchars($datasiswa['NIS']); ?></div>
                                            </div>

                                            <div class="mt-3 d-flex justify-content-center gap-2">
                                                <a href="index.php?halaman=editsiswa&id=<?= $datasiswa['idsiswa']; ?>" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="db/dbsiswa.php?proses=hapussiswa&id=<?= $datasiswa['idsiswa']; ?>" 
                                                   class="btn btn-sm btn-danger" title="Hapus"
                                                   onclick="return confirm('Yakin ingin menghapus data siswa <?= addslashes($datasiswa['namasiswa']); ?>?');">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div> <!-- .row -->
                    </div> <!-- .card-body -->
                </div> <!-- .card -->
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</section>

<style>
    /* Kartu kelas (container besar) */
    .class-card {
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.06);
    }

    .class-title {
        font-size: 1.15rem;
        text-transform: uppercase;
        margin: 0;
        color: #fff;
    }

    /* Student card kecil */
    .student-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.06);
        display: flex;
        flex-direction: column;
        transition: transform .22s ease, box-shadow .22s ease;
    }

    .student-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 32px rgba(16, 185, 129, 0.12);
        border-color: rgba(34,197,94,0.45);
    }

    .student-image {
        height: 140px;
        overflow: hidden;
        border-bottom: 4px solid rgba(0,0,0,0.03);
    }

    .student-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .5s;
    }

    .student-card:hover .student-image img {
        transform: scale(1.08);
    }

    .student-body {
        padding: 12px;
    }

    .student-name {
        font-weight: 800;
        color: #1e7e34; /* sedikit aksen hijau agar konsisten profesional */
        font-size: 14px;
        text-transform: capitalize;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-nis {
        font-size: 12px;
        color: #6c757d;
    }

    .gap-2 > * { margin-right: 6px; } /* simple spacing for action buttons */

    /* Responsive tweaks */
    @media (max-width: 576px) {
        .student-image { height: 110px; }
    }
</style>
