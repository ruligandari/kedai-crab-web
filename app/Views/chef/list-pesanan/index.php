<?= $this->extend('layouts/main-layouts'); ?>

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
              <h5 class="card-title">List Pesanan</h5>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Pembeli</th>
                    <th scope="col">Nomor Order</th>
                    <th scope="col">Total Harga</th>
                    <th scope="col">Jenis Pesanan</th>
                    <th scope="col">Status Pesanan</th>
                    <th scope="col">Tgl</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; ?>
                  <?php foreach ($listpesanan as $item) : ?>
                    <tr>
                      <th scope="row"><?= $no++ ?></th>
                      <td><?= $item['nama_pembeli'] ?></td>
                      <td><?= $item['no_order'] ?></td>
                      <td><?= $item['total_harga'] ?></td>
                      <td><?= $item['status'] ?></td>
                      <td><span class="badge bg-success text-light"><?= $item['status_pesanan'] ?></span></td>
                      <td><?= $item['tgl_transaksi'] ?></td>
                      <td>
                        <a href="<?= base_url('chef/detail-pesanan/' . $item['id']) ?>" class="btn btn-primary"><i class="bi bi-eye"></i></a>
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

</section>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>

<?= $this->endSection(); ?>