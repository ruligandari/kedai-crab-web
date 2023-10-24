<?= $this->extend('layouts/pemilik/main-layouts'); ?>

<?= $this->section('content'); ?>
<section class="section dashboard">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="row">
                <!-- Reports -->
                <div class="col-12">
                    <!-- set flash data -->
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
                            <div class="card-header">
                                <h5 class="card-title">List User</h5>
                                <div class="col d-flex justify-content-end">
                                    <a href="<?= base_url('pemilik/tambah-user') ?>" class="btn btn-primary">Tambah User</a>
                                </div>
                            </div>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($admin as $item) : ?>
                                        <tr>
                                            <th scope="row"><?= $no++ ?></th>
                                            <td><?= $item['nama'] ?></td>
                                            <td><?= $item['email'] ?></td>
                                            <?php
                                            $roles = $item['role'];
                                            if ($roles == 1) {
                                                $role = 'Pemilik';
                                            } elseif ($roles == 2) {
                                                $role = 'Kasir';
                                            } elseif ($roles == 3) {
                                                $role = 'Chef';
                                            } elseif ($roles == 4) {
                                                $role = 'Kurir';
                                            }
                                            ?>
                                            <td><?= $role ?></td>
                                            <td>
                                                <!-- buat rata kanan -->
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-danger mx-1" onclick="deleteUsers(<?= $item['id'] ?>)">Delete</button>
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?= $item['id'] ?>">Edit</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div><!-- End Reports -->

            </div>
        </div><!-- End Left side columns -->

    </div><!-- End Right side columns -->

    <!-- modal -->
    <?php foreach ($admin as $item) : ?>
        <div class="modal fade" id="edit<?= $item['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('pemilik/edit-user') ?>" method="post">
                            <input type="hidden" value="<?= $item['id'] ?>" name="id">
                            <div class="form-control">
                                <label for="nama">Nama</label>
                                <input type="text" value="<?= $item['nama'] ?>" name="nama" class="form-control md-2">
                                <label for="total_harga">Email</label>
                                <input type="text" value="<?= $item['email'] ?>" name="email" class="form-control md-2">
                                <label for="total_harga">Password Baru</label>
                                <input type="password" name="new_password" class="form-control md-2">
                                <label for="total_harga">No Hp</label>
                                <input type="text" name="no_telp" value="<?= $item['no_telp'] ?>" class="form-control md-2">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<!-- tambahkan sweet alert -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteUsers(id) {
        Swal.fire({
            title: 'Apakah Kamu yakin ingin menghapus user ini?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('pemilik/delete-user') ?>',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function() {
                        Swal.fire('Berhasil menghapus user', '', 'success')
                    }
                }).then(function() {
                    location.reload();
                })
            } else if (result.isDenied) {
                Swal.fire('Perubahan Gagal Disimpan', '', 'info')
            }
        })
    }
</script>
<?= $this->endSection(); ?>