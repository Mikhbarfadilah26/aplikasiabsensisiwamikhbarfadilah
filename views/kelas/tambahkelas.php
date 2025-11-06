<!-- Main content -->
<section class="content">

  <div class="card shadow-sm">
    <div class="card-header bg-primary">
      <h3 class="card-title text-white m-0">Tambah Kelas</h3>
    </div>

    <form action="db/dbkelas.php?proses=tambah" method="POST" enctype="multipart/form-data">
      <div class="card-body">

        <!-- Nama Kelas -->
        <div class="form-group mb-3">
          <label for="namakelas" class="fw-bold">Nama Kelas</label>
          <input type="text" class="form-control" id="namakelas" name="namakelas"
            placeholder="Masukkan nama kelas (contoh: X IPA 1)" required>
        </div>

        <!-- Foto Kelas -->
        <div class="form-group mb-4">
          <label for="fotokelas" class="fw-bold">Foto Kelas</label>
          <input type="file" class="form-control mt-2" id="fotokelas" name="fotokelas" accept="image/*">
        </div>

      </div>

      <!-- Tombol Aksi -->
      <div class="card-footer text-right">
        <button type="reset" class="btn btn-warning mr-2">
          <i class="fa fa-retweet"></i> Reset
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-save"></i> Simpan
        </button>
      </div>
    </form>
  </div>

</section>
