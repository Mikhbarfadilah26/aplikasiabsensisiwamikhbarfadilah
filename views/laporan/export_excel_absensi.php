<?php
// ============================================================
// FILE: export_excel_absensi.php
// Fungsi: Ekspor data absensi ke Excel tanpa Composer
// ============================================================

require('../../koneksi.php');

// ============================================================
// 1️⃣  Load PSR SimpleCache agar PhpSpreadsheet tidak error
// ============================================================
spl_autoload_register(function ($class) {
    $prefix = 'Psr\\SimpleCache\\';
    $base_dir = __DIR__ . '/../../plugins/Psr/SimpleCache/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// ============================================================
// 2️⃣  Muat manual semua file penting dari PhpSpreadsheet
// ============================================================
$basePath = __DIR__ . '/../../plugins/PhpSpreadsheet-5.2.0/src/PhpSpreadsheet/';

require_once $basePath . 'Spreadsheet.php';
require_once $basePath . 'Cell/Cell.php';
require_once $basePath . 'Cell/Coordinate.php';
require_once $basePath . 'Worksheet/Worksheet.php';

// Bagian writer
require_once $basePath . 'Writer/IWriter.php';
require_once $basePath . 'Writer/BaseWriter.php';
require_once $basePath . 'Writer/Xlsx.php';

// ============================================================
// 3️⃣  Import kelas yang akan digunakan
// ============================================================
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ============================================================
// 4️⃣  Ambil filter dari URL
// ============================================================
$tgl_mulai   = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
$idkelas     = isset($_GET['idkelas']) ? $_GET['idkelas'] : '';
$status      = isset($_GET['status']) ? $_GET['status'] : '';

// ============================================================
// 5️⃣  Query data absensi (disesuaikan dengan struktur kamu)
// ============================================================
$query = "SELECT a.idabsen, s.namasiswa, k.namakelas, a.tanggal, sa.nama_status, a.keterangan
          FROM absen a
          LEFT JOIN siswa s ON a.idsiswa = s.idsiswa
          LEFT JOIN kelas k ON s.idkelas = k.idkelas
          LEFT JOIN status_absen sa ON a.id_status = sa.id_status
          WHERE 1=1";

if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query .= " AND a.tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}
if (!empty($idkelas)) {
    $query .= " AND s.idkelas = '$idkelas'";
}
if (!empty($status)) {
    $query .= " AND a.id_status = '$status'";
}

$query .= " ORDER BY a.tanggal DESC";
$result = mysqli_query($koneksi, $query);

// ============================================================
// 6️⃣  Buat file Excel
// ============================================================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul
$sheet->setCellValue('A1', 'LAPORAN ABSENSI SISWA SMK NEGERI 1 KARANG BARU');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

// Header kolom
$sheet->setCellValue('A3', 'No');
$sheet->setCellValue('B3', 'Nama Siswa');
$sheet->setCellValue('C3', 'Kelas');
$sheet->setCellValue('D3', 'Tanggal');
$sheet->setCellValue('E3', 'Status');
$sheet->setCellValue('F3', 'Keterangan');
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
// 7️⃣  Output ke browser
// ============================================================
$filename = 'Laporan_Absensi_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
