<?php
// ============================================================
// FILE: views/laporan/export_csv_absensi.php
// FUNGSI: Mengekspor data absensi ke format CSV
// ============================================================

// 🔹 1. Koneksi ke database
require_once __DIR__ . '/../../koneksi.php';

// 🔹 2. Ambil filter dengan keamanan terhadap SQL Injection
$tgl_mulai   = mysqli_real_escape_string($koneksi, $_GET['tgl_mulai'] ?? '');
$tgl_selesai = mysqli_real_escape_string($koneksi, $_GET['tgl_selesai'] ?? '');
$idkelas     = mysqli_real_escape_string($koneksi, $_GET['idkelas'] ?? '');
$status      = mysqli_real_escape_string($koneksi, $_GET['status'] ?? '');

// 🔹 3. Set header agar browser langsung mendownload file CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_absensi.csv');
header('Pragma: no-cache');
header('Expires: 0');

// 🔹 4. Buka output stream untuk menulis CSV
$output = fopen('php://output', 'w');

// 🔹 5. Tulis header kolom CSV
fputcsv($output, [
    'No',
    'Nama Siswa',
    'Kelas',
    'Tanggal',
    'Jam Masuk',
    'Jam Keluar',
    'Status Kehadiran',
    'Admin Input'
]);

// 🔹 6. Buat query utama
$query = "
    SELECT 
        s.namasiswa, 
        k.namakelas, 
        a.tanggal, 
        ab.jammasuk, 
        ab.jamkeluar, 
        sa.nama_status AS status_kehadiran,
        ad.namaadmin 
    FROM kehadiran a
    JOIN siswa s ON a.idsiswa = s.idsiswa
    JOIN kelas k ON s.idkelas = k.idkelas
    LEFT JOIN admin ad ON a.idadmin = ad.idadmin
    LEFT JOIN absen ab ON a.idkehadiran = ab.idabsen
    JOIN status_absen sa ON a.id_status = sa.id_status
    WHERE 1=1
";

// 🔹 7. Tambahkan filter jika ada
if ($tgl_mulai && $tgl_selesai) {
    $query .= " AND a.tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}
if ($idkelas) {
    $query .= " AND k.idkelas = '$idkelas'";
}
if ($status) {
    $query .= " AND a.id_status = '$status'";
}

$query .= " ORDER BY a.tanggal DESC";

// 🔹 8. Eksekusi query dan cek error
$result = mysqli_query($koneksi, $query);

if (!$result) {
    fputcsv($output, ["QUERY ERROR", mysqli_error($koneksi)]);
    fclose($output);
    exit;
}

// 🔹 9. Loop hasil query dan tulis ke CSV
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $no++,
        $row['namasiswa'],
        $row['namakelas'],
        $row['tanggal'],
        $row['jammasuk'] ?? '-',
        $row['jamkeluar'] ?? '-',
        $row['status_kehadiran'],
        $row['namaadmin'] ?? '-'
    ]);
}

// 🔹 10. Tutup output stream
fclose($output);
exit;
