<section class="content">

    <div class="card shadow-sm">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white m-0">Tambah Admin</h3>
        </div>

        <form action="db/dbadmin.php?proses=tambah" method="POST" enctype="multipart/form-data">
            <div class="card-body">

                <div class="form-group mb-3">
                    <label for="namaadmin" class="fw-bold">Nama Admin</label>
                    <input type="text" class="form-control" id="namaadmin" name="namaadmin"
                        placeholder="Masukkan Nama Lengkap Admin" required>
                </div>

                <div class="form-group mb-3">
                    <label for="nohp" class="fw-bold">Nomor HP</label>
                    <input type="text" class="form-control" id="nohp" name="nohp"
                        placeholder="Nomor Telepon/HP" required>
                </div>

                <div class="form-group mb-3">
                    <label for="username" class="fw-bold">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Username untuk Login" required>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="fw-bold">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan Password" required>
                    <small class="form-text text-warning">Password akan dienkripsi saat disimpan.</small>
                </div>

                <div class="form-group mb-4">
                    <label for="fotoadmin" class="fw-bold">Foto Admin</label>
                    <input type="file" class="form-control mt-2" id="fotoadmin" name="fotoadmin" accept="image/*">
                </div>

            </div>

            <div class="card-footer text-right">
                <button type="reset" class="btn btn-warning mr-2">
                    <i class="fa fa-retweet"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <a href="index.php?halaman=admin" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>

</section>