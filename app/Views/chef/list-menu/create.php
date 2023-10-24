<?= $this->extend('layouts/main-layouts'); ?>

<?= $this->section('content'); ?>
<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">
        <!-- Reports -->
        <div class="col-12">
          <?php
          // Cek apakah terdapat session nama message
          if (session()->getFlashdata('success')) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= session()->getFlashdata('success'); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php } elseif (session()->getFlashdata('error')) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= session()->getFlashdata('error'); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php } ?>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tambah Menu</h5>
              <form action="<?= base_url('chef/tambah-menu') ?>" method="post" enctype="multipart/form-data">
                <div class="form-control">
                  <label for="nama_produk">Nama Produk</label>
                  <input type="text" name="nama_produk" id="nama_produk" class="form-control mt-2">
                  <label for="harga" class="mt-2 form-label">Harga</label>
                  <div class="input-group">
                    <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                    <input type="number" name="harga" id="harga" class="form-control">
                  </div>
                  <label for="stok" class="mt-2">Stok</label>
                  <input type="text" name="stok" id="stok" class="form-control mt-2">
                  <label for="deskripsi" class="mt-2">Deskripsi</label>
                  <textarea type="text" name="deskripsi" id="deskripsi" class="form-control mt-2"></textarea>
                  <label for="foto" class="mt-2">Foto</label>
                  <input type="file" name="foto" id="foto" class="form-control mt-2">
                  <img id="preview" class="mt-3" src="" alt="Preview Foto" style="max-width: 200px; max-height: 200px;" hidden>
                </div>
                <button class="btn btn-success mt-3">Save</button>
              </form>
            </div>

          </div>
        </div><!-- End Reports -->

      </div>
    </div><!-- End Left side columns -->

  </div><!-- End Right side columns -->

  </div>
</section>
<?= $this->endSection(); ?>
<?= $this->section('script'); ?>
<script>
  // menampilkan foto yang telah dipilih dari input file dengan id=foto
  const fotoInput = document.getElementById('foto');
  const previewImage = document.getElementById('preview');

  fotoInput.addEventListener('change', function() {
    const file = this.files[0];

    if (file) {
      const reader = new FileReader();

      reader.addEventListener('load', function() {
        previewImage.setAttribute('src', reader.result);
        previewImage.removeAttribute('hidden'); // Menghilangkan atribut 'hidden'
      });

      reader.readAsDataURL(file);
    } else {
      previewImage.removeAttribute('src');
      previewImage.setAttribute('hidden', 'hidden'); // Menambahkan atribut 'hidden'
    }
  });
</script>
<?= $this->endSection(); ?>