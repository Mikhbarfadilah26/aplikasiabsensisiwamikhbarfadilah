<section class="content">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h3 class="card-title">Tambah Status Absen</h3>
        </div>

        <form action="db/dbstatus_absen.php?proses=tambah" method="POST">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label>Nama Status</label>
                    <input type="text" name="nama_status" class="form-control" placeholder="Contoh: Hadir, Izin, Sakit, Alpha" required>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="index.php?halaman=status_absen" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</section>
