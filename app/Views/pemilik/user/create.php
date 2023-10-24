<?= $this->extend('layouts/pemilik/main-layouts'); ?>
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
                            <h5 class="card-title">Tambah User</h5>
                            <form action="<?= base_url('pemilik/add') ?>" method="post" enctype="multipart/form-data">
                                <div class="form-control">
                                    <label for="nama_produk">Nama</label>
                                    <input type="text" name="nama" id="" class="form-control mt-2">
                                    <label for="harga" class="mt-2 form-label">Email</label>
                                    <input type="text" name="email" id="" class="form-control mt-2">
                                    <label for="stok" class="mt-2">Password</label>
                                    <input type="password" name="password" id="stok" class="form-control mt-2">
                                    <label for="no_hp" class="mt-2">No Telepon</label>
                                    <input type="text" name="no_telp" id="stok" class="form-control mt-2">
                                    <label for="deskripsi" class="mt-2">Role</label>
                                    <select class="form-control" name="role">
                                        <option value="1">Pemilik</option>
                                        <option value="2">Kasir</option>
                                        <option value="3">Chef</option>
                                        <option value="4">Kurir</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Save</button>
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