<style>
/* ======================================= */
/* CSS KUSTOM UNTUK HALAMAN NOT FOUND (VERSI KECIL) */
/* ======================================= */

.error-page-custom {
    text-align: center;
    padding: 30px 15px; /* Padding dikurangi */
    background-color: #343a40; 
    color: #f8f9fa; 
    border-radius: 12px; /* Border radius dikurangi */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    max-width: 550px; /* Lebar maksimum dikurangi */
    margin: 40px auto; /* Margin dikurangi */
}

.error-page-custom .headline {
    font-size: 90px; /* Ukuran headline sangat dikurangi */
    font-weight: 900;
    color: #ffc107 !important; 
    position: relative;
    display: inline-block;
    padding-right: 10px;
    text-shadow: 4px 4px 8px rgba(0,0,0,0.5);
}

.error-page-custom .headline::after {
    content: '';
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px; /* Lebar garis dikurangi */
    height: 70%; /* Tinggi garis dikurangi */
    background-color: #ffc107;
    border-radius: 4px;
}

.error-page-custom .error-content h3 {
    margin-top: 20px; /* Margin atas dikurangi */
    color: #dee2e6; 
    font-size: 1.5rem; /* Ukuran judul dikurangi */
    font-weight: 700;
}

.error-page-custom .error-content p {
    font-size: 0.95rem; /* Ukuran paragraf dikurangi */
    line-height: 1.5;
    color: #adb5bd; 
}

/* Kustomisasi Ikon Tong Sampah */
.error-page-custom .fas.fa-trash-alt {
    color: #dc3545; 
    font-size: 2rem; /* Ukuran ikon dikurangi */
    margin-right: 8px;
    vertical-align: middle;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
}

.error-page-custom .error-content span {
    color: #ff4d4d; 
    font-weight: 600;
}

.error-page-custom .btn-primary {
    /* Menggunakan class bawaan AdminLTE/Bootstrap btn-sm */
    padding: 8px 20px; /* Padding tombol dikurangi */
    font-size: 0.9rem; /* Ukuran font tombol dikurangi */
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 123, 255, 0.3);
    transition: all 0.3s ease;
}

.error-page-custom .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    box-shadow: 0 3px 10px rgba(0, 123, 255, 0.4);
    transform: translateY(-1px);
}

.error-page-custom .footer-text {
    margin-top: 30px; /* Margin atas dikurangi */
    color: #6c757d;
    font-size: 0.8rem; /* Ukuran teks footer dikurangi */
}

</style>

<section class="content">
  <div class="error-page error-page-custom">
    <h2 class="headline">404</h2>

    <div class="error-content">
      <h3>
        <i class="fas fa-trash-alt"></i> Upss, Halaman tidak ditemukan!
      </h3>

      <p>
        Halaman yang Anda cari sepertinya sudah <span style="color:#ff4d4d;">dibuang ke tempat sampah</span>.<br>
        Mungkin ada <span style="color:#ff4d4d;">kesalahan penulisan</span> pada tautan, <br>
        atau halaman tersebut memang sudah <span style="color:#ff4d4d;">dihapus</span> oleh Admin.
      </p>

      <a href="index.php?halaman=dashboard" class="btn btn-primary">
        <i class="fas fa-home mr-1"></i> Kembali Ke Halaman Awal
      </a>

      <p class="footer-text">
        <strong>Colongan</strong> © 2018 - 2019. All Rights Reserved
      </p>
    </div>
  </div>
</section>