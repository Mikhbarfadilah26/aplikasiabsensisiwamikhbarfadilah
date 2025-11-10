<?php
// ================================
// FILE: views/laporan/export_pdf_absensi.php
// ================================

require('../../koneksi.php');
require('../../plugins/fpdf186/fpdf.php');

// Ambil filter dari URL
$tgl_mulai   = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$idkelas     = $_GET['idkelas'] ?? '';
$status      = $_GET['status'] ?? '';

// Konfigurasi judul laporan
$periode = ($tgl_mulai && $tgl_selesai) ? 
    "Periode: $tgl_mulai s.d $tgl_selesai" : 
    "Semua Periode";

// Buat objek PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// ================================
// HEADER / KOP SURAT (TANPA LOGO)
// ================================
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 7, 'SMK NEGERI 1 KARANG BARU', 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, 'Desa Bundar, Kecamatan Karang Baru, Kabupaten Aceh Tamiang', 0, 1, 'C');
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 5, 'Email: info@smkn1karangbaru.sch.id | Telp: (0000) 000000', 0, 1, 'C');
$pdf->Ln(3);

// Garis pembatas
$pdf->SetLineWidth(0.5);
$pdf->Line(10, 37, 200, 37);
$pdf->SetLineWidth(0);
$pdf->Line(10, 38, 200, 38);
$pdf->Ln(10);

// ================================
// JUDUL LAPORAN
// ================================
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, 'LAPORAN ABSENSI SISWA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, $periode, 0, 1, 'C');
$pdf->Ln(8);

// ================================
// HEADER TABEL
// ================================
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(52, 152, 219);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Nama Siswa', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Kelas', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jam Masuk', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jam Keluar', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Status', 1, 1, 'C', true);

// ================================
// DATA TABEL
// ================================
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

$query = "
SELECT 
    s.namasiswa, 
    k.namakelas, 
    a.tanggal, 
    ab.jammasuk, 
    ab.jamkeluar, 
    sa.nama_status AS status_kehadiran
FROM kehadiran a
JOIN siswa s ON a.idsiswa = s.idsiswa
JOIN kelas k ON s.idkelas = k.idkelas
LEFT JOIN absen ab ON a.idkehadiran = ab.idabsen
JOIN status_absen sa ON a.id_status = sa.id_status
WHERE 1=1
";

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
$result = mysqli_query($koneksi, $query);

$no = 1;
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(10, 8, $no++, 1, 0, 'C');
        $pdf->Cell(40, 8, $row['namasiswa'], 1, 0, 'L');
        $pdf->Cell(30, 8, $row['namakelas'], 1, 0, 'C');
        $pdf->Cell(25, 8, $row['tanggal'], 1, 0, 'C');
        $pdf->Cell(25, 8, $row['jammasuk'] ?: '-', 1, 0, 'C');
        $pdf->Cell(25, 8, $row['jamkeluar'] ?: '-', 1, 0, 'C');
        $pdf->Cell(25, 8, $row['status_kehadiran'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 8, 'Tidak ada data absensi.', 1, 1, 'C');
}

$pdf->Ln(10);

// ================================
// FOOTER TANDA TANGAN
// ================================
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Aceh Tamiang, ' . date('d-m-Y'), 0, 1, 'C');
$pdf->Cell(130, 8, '', 0, 0);
$pdf->Cell(60, 8, 'Kepala Sekolah,', 0, 1, 'C');
$pdf->Ln(20);
$pdf->Cell(130, 8, '', 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 8, 'Fahmi Putra', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(130, 6, '', 0, 0);
$pdf->Cell(60, 6, 'NIP. 1234567890', 0, 1, 'C');

// Output PDF ke browser
$pdf->Output('I', 'Laporan_Absensi_Siswa.pdf');
?>
