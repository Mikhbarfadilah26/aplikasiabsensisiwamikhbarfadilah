<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h3 class="card-title">Tambah Pola Jadwal</h3>
        </div>

        <form action="db/dbpolajadwal.php?proses=tambah" method="POST">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label>Nama Pola</label>
                    <input type="text" name="nama_pola" class="form-control" placeholder="Contoh: Pola Normal" required>
                </div>
                <div class="form-group mb-3">
                    <label>Jam Masuk Ideal</label>
                    <input type="time" name="jam_masuk_ideal" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Jam Pulang Ideal</label>
                    <input type="time" name="jam_pulang_ideal" class="form-control" required>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="index.php?halaman=polajadwal" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</section>
