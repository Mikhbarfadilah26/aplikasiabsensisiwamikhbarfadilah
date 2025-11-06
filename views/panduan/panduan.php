<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Absensi Siswa</title>

    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .card {
            animation: fadeIn 1s ease-in-out;
            border-radius: 15px;
        }

        .card-header {
            background: linear-gradient(90deg, #ffc107, #ffda5c);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        h5 {
            font-weight: 600;
        }

        .list-group-item {
            border-radius: 10px;
            background-color: #fff;
            transition: all 0.3s ease-in-out;
        }

        .list-group-item:hover {
            transform: translateX(5px);
            background-color: #fffcea;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .badge-primary {
            background-color: #007bff;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 12px;
        }

        /* Animasi scroll */
        .fade-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease;
        }

        .fade-scroll.active {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tombol kembali */
        .back-to-welcome {
            padding: 30px 0 10px;
            border-top: 1px solid #ddd;
            margin-top: 30px;
        }

        .back-to-welcome img {
            height: 60px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container my-5 fade-scroll">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header text-dark text-center">
                        <h2 class="font-weight-bold mb-0">
                            <i class="fas fa-book-open mr-2"></i> Panduan Absensi untuk Siswa
                        </h2>
                    </div>

                    <div class="card-body p-5">
                        <h4 class="text-secondary mb-4">Ikuti 5 Langkah Mudah di Bawah Ini:</h4>

                        <div class="list-group">
                            <a href="#langkah1" class="list-group-item list-group-item-action mb-3">
                                <h5 class="text-primary">
                                    <span class="badge badge-primary mr-2">1</span> Akses dan Login
                                </h5>
                                <p class="mb-1">Kunjungi aplikasi dan klik <b>"Login Siswa"</b>.</p>
                                <small class="text-muted">Masukkan <b>NIS/NISN</b> dan <b>Password</b>.</small>
                            </a>

                            <a href="#langkah2" class="list-group-item list-group-item-action mb-3">
                                <h5 class="text-primary">
                                    <span class="badge badge-primary mr-2">2</span> Menuju Halaman Presensi
                                </h5>
                                <p class="mb-1">Setelah login, pilih menu <b>"Presensi Harian"</b>.</p>
                            </a>

                            <a href="#langkah3" class="list-group-item list-group-item-action mb-3">
                                <h5 class="text-primary">
                                    <span class="badge badge-primary mr-2">3</span> Pilih Status Kehadiran
                                </h5>
                                <p class="mb-1">Pilih Hadir, Sakit, Izin, atau Alpha. Upload bukti jika diperlukan.</p>
                            </a>

                            <a href="#langkah4" class="list-group-item list-group-item-action mb-3">
                                <h5 class="text-primary">
                                    <span class="badge badge-primary mr-2">4</span> Verifikasi (Jika Ada)
                                </h5>
                                <p class="mb-1">Aktifkan izin kamera atau lokasi untuk verifikasi.</p>
                            </a>

                            <a href="#langkah5" class="list-group-item list-group-item-action">
                                <h5 class="text-primary">
                                    <span class="badge badge-primary mr-2">5</span> Konfirmasi dan Selesai
                                </h5>
                                <p class="mb-1">Klik <b>"Submit"</b> atau <b>"Simpan Absensi"</b>.</p>
                                <small class="text-success font-weight-bold">Absensi hanya bisa 1x per hari.</small>
                            </a>
                        </div>

                        <div class="text-center back-to-welcome">
                            <a href="index.php?halaman=welcome" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-home mr-2"></i> Kembali ke Halaman Utama
                            </a>
                            <p class="text-muted mt-3 mb-0">SMK NEGERI 1 KARANG BARU</p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const scrollElements = document.querySelectorAll('.fade-scroll');

        function scrollAnimation() {
            scrollElements.forEach(el => {
                if (el.getBoundingClientRect().top < window.innerHeight - 100) {
                    el.classList.add('active');
                }
            });
        }
        window.addEventListener('scroll', scrollAnimation);
        window.addEventListener('load', scrollAnimation);
    </script>

</body>

</html>