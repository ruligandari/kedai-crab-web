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
                                <h5 class="card-title">List Customer</h5>
                            </div>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col">No. Telp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($customer as $item) : ?>
                                        <tr>
                                            <th scope="row"><?= $no++ ?></th>
                                            <td><?= $item['nama'] ?></td>
                                            <td><?= $item['email'] ?></td>
                                            <td><?= $item['alamat'] ?></td>
                                            <td><?= $item['no_telp'] ?></td>
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