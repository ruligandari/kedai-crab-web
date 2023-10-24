<?= $this->extend('layouts/main-layouts'); ?>

<?= $this->section('content'); ?>
<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">
        <!-- Reports -->
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Detail Pesanan</h5>

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name Produk</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Kuantitas</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1 ?>
                  <?php foreach ($listpesanan as $item) : ?>
                    <tr>
                      <th scope="row"><?= $no++ ?></th>
                      <td><?= $item['nama_produk'] ?></td>
                      <td>Rp. <?= $item['harga_produk'] ?></td>
                      <td>X <?= $item['kuantitas_produk'] ?></td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3">Total Harga</th>
                    <th>Rp. <?= $listpesanan[0]['total_harga'] ?></th>
                  </tr>
                </tfoot>
              </table>
              <?php if ($status_pesanan == 'Pesanan Berhasil') : ?>

                <form action="<?= base_url('chef/update-status') ?>" method="post">
                  <!-- dapatkan nilai dari segement url terakhir -->

                  <input type="hidden" name="id" value="<?= $id ?>">
                  <button type="submit" class="btn btn-success mb-3">Proses Pesanan</button>
                </form>
              <?php endif; ?>
            </div>

          </div>
        </div><!-- End Reports -->

      </div>
    </div><!-- End Left side columns -->

  </div><!-- End Right side columns -->

  </div>
</section>
<?= $this->endSection(); ?>