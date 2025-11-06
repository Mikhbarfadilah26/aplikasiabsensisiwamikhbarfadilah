<style>
/* ======================================= */
/* FOOTER RAMPING, TENGAH & POSISI DI BAWAH */
/* ======================================= */
.main-footer {
    position: fixed;      /* Menempel di layar */
    bottom: 0;            /* Di bagian paling bawah */
    left: 50%;            /* Mulai dari tengah layar */
    transform: translateX(-50%); /* Geser agar benar-benar di tengah */
    width: 100%;          /* Panjang mengikuti layar */
    text-align: center;
    padding: 8px 15px;
    font-size: 0.85rem;
    background-color: #343a40;
    color: #adb5bd;
    border-top: 1px solid #454d55;
    z-index: 999;         /* Supaya tidak tertutup elemen lain */
}

.main-footer strong {
    color: #f8f9fa;
}

.main-footer a {
    color: #00c0ef;
    transition: color 0.3s ease;
}

.main-footer a:hover {
    color: #007bff;
    text-decoration: none;
}

/* Hilangkan elemen kanan agar tidak ganggu */
.main-footer .float-right {
    display: none;
}
</style>

<footer class="main-footer">
    <strong>
        Dibuat: 2 November 2025.
        <a href="#">M. Ikhbar Fadilah</a>
    </strong>
    <span class="ml-2">
        | Aplikasi Absensi | Didukung oleh <b>CSS</b> & <b>JavaScript</b> terbaik.
    </span>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versi</b> 1.0.0
    </div>
</footer>
