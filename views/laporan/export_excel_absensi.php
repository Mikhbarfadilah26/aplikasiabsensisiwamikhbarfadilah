<?php
// ============================================================
// FILE: export_excel_absensi.php
// Fungsi: Ekspor data absensi ke Excel (versi Composer)
// ============================================================

// Koneksi database
require('../../koneksi.php');

// ============================================================
// 1️⃣ Load autoload dari Composer
// ============================================================
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ============================================================
// 2️⃣ Ambil filter dari URL
// ============================================================
$tgl_mulai   = $_GET['tgl_mulai']   ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$idkelas     = $_GET['idkelas']     ?? '';
$status      = $_GET['status']      ?? '';

// ============================================================
// 3️⃣ Query data absensi (sesuaikan dengan struktur kamu)
// ============================================================
$query = "SELECT a.idabsen, s.namasiswa, k.namakelas, a.tanggal, sa.nama_status, a.keterangan
          FROM absen a
          LEFT JOIN siswa s ON a.idsiswa = s.idsiswa
          LEFT JOIN kelas k ON s.idkelas = k.idkelas
          LEFT JOIN status_absen sa ON a.id_status = sa.id_status
          WHERE 1=1";

if ($tgl_mulai && $tgl_selesai) {
    $query .= " AND a.tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}
if ($idkelas) {
    $query .= " AND s.idkelas = '$idkelas'";
}
if ($status) {
    $query .= " AND a.id_status = '$status'";
}

$query .= " ORDER BY a.tanggal DESC";
$result = mysqli_query($koneksi, $query);

// ============================================================
// 4️⃣ Buat file Excel
// ============================================================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul laporan
$sheet->setCellValue('A1', 'LAPORAN ABSENSI SISWA SMK NEGERI 1 KARANG BARU');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

// Header kolom
$headers = ['No', 'Nama Siswa', 'Kelas', 'Tanggal', 'Status', 'Keterangan'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '3', $header);
    $col++;
}
$sheet->getStyle('A3:F3')->getFont()->setBold(true);

// Isi data
$no = 1;
$row = 4;
while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $no++);
    $sheet->setCellValue('B' . $row, $data['namasiswa']);
    $sheet->setCellValue('C' . $row, $data['namakelas']);
    $sheet->setCellValue('D' . $row, $data['tanggal']);
    $sheet->setCellValue('E' . $row, $data['nama_status']);
    $sheet->setCellValue('F' . $row, $data['keterangan']);
    $row++;
}

// Lebar kolom otomatis
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================================
// 5️⃣ Output ke browser (download Excel)
// ============================================================
$filename = 'Laporan_Absensi_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
