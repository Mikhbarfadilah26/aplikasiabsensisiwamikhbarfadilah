<?php
// ====================================================================
// 📄 views/laporan/export_pdf_absensi.php
// REVISI FINAL: Versi rapi dengan penyesuaian posisi tanda tangan
// ====================================================================

// ===================================================
// 🔹 Koneksi Database & Library FPDF
// ===================================================
require('../../koneksi.php');
require('../../plugins/fpdf186/fpdf.php');

// ===================================================
// 🔹 Ambil Parameter Filter dari URL
// ===================================================
$tgl_mulai   = $_GET['tgl_mulai']   ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$idkelas     = $_GET['idkelas']     ?? '';
$status      = $_GET['status']      ?? '';

// ===================================================
// 🔹 Query SQL (Sinkron dengan laporan di tampilan browser)
// ===================================================
$query = "
    SELECT 
        s.namasiswa       AS nama_siswa,
        k.namakelas,
        a.tanggal,
        ab.jammasuk,
        ab.jamkeluar,
        sa.nama_status    AS nama_status,
        ad.namaadmin,
        ab.keterangan
    FROM kehadiran a
    JOIN siswa s         ON a.idsiswa   = s.idsiswa
    JOIN kelas k         ON s.idkelas   = k.idkelas
    LEFT JOIN admin ad   ON a.idadmin   = ad.idadmin
    LEFT JOIN absen ab   ON a.idkehadiran = ab.idabsen
    JOIN status_absen sa ON a.id_status = sa.id_status
    WHERE 1=1
";

// Filter tambahan
if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query .= " AND a.tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}
if (!empty($idkelas)) {
    $query .= " AND k.idkelas = '$idkelas'";
}
if (!empty($status)) {
    $query .= " AND a.id_status = '$status'";
}

$query .= " ORDER BY a.tanggal DESC";

$result = mysqli_query($koneksi, $query);
if (!$result) {
    die("Query Error: " . mysqli_error($koneksi) . "<br>Query: " . $query);
}

// ===================================================
// 🔹 Generate PDF menggunakan FPDF
// ===================================================
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// ---------------------------------------------------
// 🧾 Header Dokumen
// ---------------------------------------------------
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(280, 10, 'LAPORAN ABSENSI SISWA', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$periode_text = (empty($tgl_mulai) && empty($tgl_selesai))
    ? 'Semua Periode'
    : 'Periode: ' . date('d M Y', strtotime($tgl_mulai)) . ' s/d ' . date('d M Y', strtotime($tgl_selesai));

$pdf->Cell(280, 5, $periode_text, 0, 1, 'C');
$pdf->Cell(280, 5, 'Tahun Ajaran 2025/2026', 0, 1, 'C');
$pdf->Ln(5);

// ---------------------------------------------------
// 🧱 Header Tabel
// ---------------------------------------------------
$pdf->SetFillColor(40, 96, 144); // Biru tua
$pdf->SetTextColor(255);         // Putih
$pdf->SetFont('Arial', 'B', 10);

// Lebar kolom
$header_widths = [10, 40, 30, 25, 20, 20, 30, 95];

$pdf->Cell($header_widths[0], 8, 'No',          1, 0, 'C', 1);
$pdf->Cell($header_widths[1], 8, 'Nama Siswa',  1, 0, 'C', 1);
$pdf->Cell($header_widths[2], 8, 'Kelas',       1, 0, 'C', 1);
$pdf->Cell($header_widths[3], 8, 'Tanggal',     1, 0, 'C', 1);
$pdf->Cell($header_widths[4], 8, 'Jam Masuk',   1, 0, 'C', 1);
$pdf->Cell($header_widths[5], 8, 'Jam Keluar',  1, 0, 'C', 1);
$pdf->Cell($header_widths[6], 8, 'Status',      1, 0, 'C', 1);
$pdf->Cell($header_widths[7], 8, 'Keterangan',  1, 1, 'C', 1);

// ---------------------------------------------------
// 📋 Isi Tabel
// ---------------------------------------------------
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0); // Hitam

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $nama_siswa  = $row['nama_siswa']  ?? '-';
    $kelas       = $row['namakelas']   ?? '-';
    $tanggal     = $row['tanggal']     ?? '-';
    $jam_masuk   = $row['jammasuk']    ?? '-';
    $jam_keluar  = $row['jamkeluar']   ?? '-';
    $status_nama = $row['nama_status'] ?? '-';
    $keterangan  = $row['keterangan']  ?? '-';

    // Posisi awal sebelum mencetak baris
    $xStart = $pdf->GetX();
    $yStart = $pdf->GetY();

    // Cetak kolom keterangan (bisa panjang)
    $pdf->SetXY($xStart + array_sum(array_slice($header_widths, 0, 7)), $yStart);
    $pdf->MultiCell($header_widths[7], 7, $keterangan, 1, 'L');
    $yEnd = $pdf->GetY();

    // Hitung tinggi baris
    $rowHeight = max(7, $yEnd - $yStart);

    // Kembalikan posisi
    $pdf->SetXY($xStart, $yStart);

    // Cetak kolom lain
    $pdf->Cell($header_widths[0], $rowHeight, $no++,         1, 0, 'C');
    $pdf->Cell($header_widths[1], $rowHeight, $nama_siswa,   1, 0, 'L');
    $pdf->Cell($header_widths[2], $rowHeight, $kelas,        1, 0, 'L');
    $pdf->Cell($header_widths[3], $rowHeight, $tanggal,      1, 0, 'C');
    $pdf->Cell($header_widths[4], $rowHeight, $jam_masuk,    1, 0, 'C');
    $pdf->Cell($header_widths[5], $rowHeight, $jam_keluar,   1, 0, 'C');
    $pdf->Cell($header_widths[6], $rowHeight, $status_nama,  1, 0, 'C');

    // Pindah ke bawah kolom keterangan
    $pdf->SetY($yEnd);
}

// ---------------------------------------------------
// ✍️ Tanda Tangan
// ---------------------------------------------------
$pdf->Ln(7);
$pdf->SetFont('Arial', '', 10);

$tanggal_cetak = date('d F Y');

// Baris pertama
$pdf->Cell(140, 5, 'Mengetahui,', 0, 0, 'L');
$pdf->Cell(140, 5, 'Karang Baru, ' . $tanggal_cetak, 0, 1, 'R');

// Jabatan
$pdf->Cell(140, 5, 'Kepala Sekolah', 0, 0, 'L');
$pdf->Cell(140, 5, 'Kepala Bidang Kesiswaan', 0, 1, 'R');

$pdf->Ln(15);

// Nama pejabat
$pdf->SetFont('Arial', 'U', 10);
$pdf->Cell(140, 5, 'Fahmi Putra, S.Pd', 0, 0, 'L');
$pdf->Cell(140, 5, 'Fajar Darma Syahputra, S.Pd', 0, 1, 'R');

// NIP
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(140, 5, 'NIP. 19791108 200904 1 001', 0, 0, 'L');
$pdf->Cell(140, 5, 'NIP. 19860505 201403 1 003', 0, 1, 'R');

// ---------------------------------------------------
// 📤 Output PDF ke Browser
// ---------------------------------------------------
$pdf->Output('I', 'Laporan_Absensi_Final.pdf');
exit;
?>
