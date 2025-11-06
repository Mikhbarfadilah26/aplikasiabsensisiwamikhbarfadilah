<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Aplikasi Absensi Digital</title>

    <!-- Font & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap / AdminLTE -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
        }

        .card {
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
            border-radius: 12px;
        }

        .card-header {
            background: linear-gradient(90deg, #0062E6, #33AEFF);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        h2,
        h4 {
            font-weight: 600;
        }

        .list-group-item {
            font-size: 16px;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            background: rgba(0, 123, 255, 0.1);
            transform: translateX(5px);
        }

        .lead {
            font-size: 18px;
            line-height: 1.8;
        }

        /* Animasi Fade In */
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

        /* Scroll Animation */
        .fade-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 1s ease;
        }

        .fade-scroll.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Tombol Kembali */
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
                    <div class="card-header text-white text-center">
                        <h2 class="font-weight-bold mb-0">
                            <i class="fas fa-info-circle mr-2"></i> Tentang Aplikasi Absensi Digital
                        </h2>
                    </div>
                    <div class="card-body p-5">

                        <h4 class="text-secondary mb-4">Pengenalan Singkat</h4>
                        <p class="lead text-justify">
                            Aplikasi Absensi Digital ini dirancang khusus untuk
                            <strong>SMK NEGERI 1 KARANG BARU</strong> sebagai solusi modern
                            dalam manajemen kehadiran seluruh civitas akademika. Sistem ini
                            menggantikan absensi manual yang sering memakan waktu dan rawan kesalahan.
                        </p>

                        <hr class="my-4">

                        <h4 class="text-secondary mb-3">Tujuan Utama</h4>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <strong>Akurasi Data:</strong> Setiap catatan kehadiran terekam secara real-time dan tepat.
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-chart-line text-success mr-2"></i>
                                <strong>Efisiensi:</strong> Menghemat waktu rekapitulasi dan pelaporan data absensi.
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-eye text-success mr-2"></i>
                                <strong>Transparansi:</strong> Data dapat dipantau kapan saja oleh yang berwenang.
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-sync-alt text-success mr-2"></i>
                                <strong>Integrasi:</strong> Data siap dikirim ke sistem informasi sekolah lainnya.
                            </li>
                        </ul>

                        <hr class="my-4">

                        <div class="text-center mt-4">
                            <p class="font-italic text-muted">
                                "Aplikasi ini adalah bentuk komitmen kami dalam mewujudkan lingkungan sekolah yang
                                Cepat, Akurat, dan Terintegrasi."
                            </p>
                        </div>

                        <!-- Tombol Kembali -->
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

    <!-- JavaScript Scroll Animation -->
    <script>
        const scrollElements = document.querySelectorAll('.fade-scroll');
        const scrollAnimation = () => {
            scrollElements.forEach((el) => {
                const position = el.getBoundingClientRect().top;
                if (position < window.innerHeight - 100) {
                    el.classList.add('active');
                }
            });
        };
        window.addEventListener('scroll', scrollAnimation);
        window.addEventListener('load', scrollAnimation);
    </script>

</body>

</html>