<?= $this->extend('layouts/kasir/main-layouts'); ?>

<?= $this->section('head'); ?>

<?= $this->endSection(); ?>

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
              <h5 class="card-title">Transaksi</h5>
              <div class="col d-flex justify-content-end">

                <button href="<?= base_url('chef/create-menu') ?>" class="btn btn-success mb-2" id="startScan" data-bs-toggle="modal" data-bs-target="#camera"> <i class="bi bi-upc-scan"></i> Scan QR</button>
              </div>
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
                    <!-- <th scope="col">Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; ?>
                  <?php foreach ($data_transaksi as $item) : ?>
                    <tr>
                      <th scope="row"><?= $no++ ?></th>
                      <td><?= $item['nama_pembeli'] ?></td>
                      <td><?= $item['no_order'] ?></td>
                      <td><?= $item['total_harga'] ?></td>
                      <td><?= $item['status'] ?></td>
                      <td><span class="badge bg-success text-light"><?= $item['status_pesanan'] ?></span></td>
                      <td><?= $item['tgl_transaksi'] ?></td>
                      <!-- <td>
                        <a href="<?= base_url('kasir/detail-pesanan/' . $item['id']) ?>" class="btn btn-primary"><i class="bi bi-eye"></i></a>
                      </td> -->
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

  </div>
</section>

<div class="modal fade" id="camera" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <center>
          <div id="preview" class="viewport"></div>
          <div id="result"></div>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="detail" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bayar" hidden>Save changes</button>
      </div>
    </div>
  </div>
</div><!-- End Basic Modal-->
<div class="modal fade" id="bayar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-control">
          <label for="id_transaksi">ID Transaksi</label>
          <input type="text" id="id_transaksi" class="form-control md-2">
          <label for="nama_pembeli">Nama</label>
          <input type="text" id="nama_pembeli" class="form-control md-2">
          <label for="nama_pembeli">Produk Order</label>
          <div id="inputContainer"></div>
          <label for="total_harga">Total Harga</label>
          <input type="text" id="total_harga" class="form-control md-2">
          <label for="total_harga">Masukan Jumlah Bayar</label>
          <input type="text" id="jumlah_bayar" class="form-control md-2">
          <label for="total_harga">Kembalian</label>
          <span id="kembalian" class="form-control md-2 text-success">Rp. 0</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <form action="<?= base_url('kasir/bayar') ?>" method="post">
          <input type="hidden" id="idTransaksi" name="id">
          <button class="btn btn-success">Bayar</button>
        </form>
      </div>
    </div>
  </div>
</div><!-- End Basic Modal-->
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Membuat instance HTML5 QR Code Scanner
  var result = document.getElementById("result");
  var html5QrcodeScanner = new Html5QrcodeScanner(
    "preview", {
      fps: 10,
      qrbox: 250,
    }, {
      facingMode: "environment"
    });

  var modalIsDisplayed = false; // Variabel untuk melacak apakah modal ditampilkan

  // Tambahkan event listener untuk tombol "Scan QR"
  document.getElementById("startScan").addEventListener("click", function() {
    // Memulai pemindaian QR Code dari webcam
    html5QrcodeScanner.render(onScanSuccess, onScanError);
    modalIsDisplayed = false; // Set variabel modalIsDisplayed ke true saat pemindaian dimulai
  });

  // Callback ketika QR Code terdeteksi
  function onScanSuccess(qrCodeMessage) {
    console.log("Hasil pemindaian: " + qrCodeMessage);
    html5QrcodeScanner.clear()
    // Jika modal belum ditampilkan, tampilkan modal
    if (!modalIsDisplayed) {
      $.ajax({
        url: "<?= base_url('kasir/transaksi/') ?>" + qrCodeMessage,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          console.log(data);

          // Tampilkan modal pembayaran
          document.getElementById('detail').click();
          document.getElementById('id_transaksi').value = data.id_transaksi;
          document.getElementById('nama_pembeli').value = data.nama_pembeli;
          document.getElementById('total_harga').value = data.total_harga;
          document.getElementById('idTransaksi').value = data.id;

          for (var i = 0; i < data.data_order.length; i++) {
            var divGroup = document.createElement("div");
            var inputText = document.createElement("input");
            var spanText = document.createElement("span");
            var spanText1 = document.createElement("span");

            divGroup.className = "input-group mt-2";

            spanText.className = "input-group-text";
            spanText.textContent = 'x' + data.data_order[i].kuantitas_produk;

            spanText1.className = "input-group-text";
            spanText1.textContent = 'Rp. ' + data.data_order[i].harga_produk

            inputText.type = "text";
            inputText.id = "nama_produk" + (i + 1);
            inputText.className = "form-control";
            inputText.value = data.data_order[i].nama_produk;

            divGroup.appendChild(spanText);
            divGroup.appendChild(inputText);
            divGroup.appendChild(spanText1);

            var inputContainer = document.getElementById("inputContainer");
            inputContainer.appendChild(divGroup)
          }


          // Hitung kembalian saat jumlah bayar berubah
          document.getElementById('jumlah_bayar').addEventListener('keyup', function() {
            var total_harga = parseFloat(data.total_harga);
            var jumlah_bayar = parseFloat(document.getElementById('jumlah_bayar').value);
            var kembalian = jumlah_bayar - total_harga;
            document.getElementById('kembalian').textContent = 'Rp. ' + kembalian;
          });

          // Set modal pembayaran sebagai target
          document.getElementById('bayar').setAttribute('data-bs-target', "#bayar");

          // Menandai modal sudah ditampilkan

        },
        error: function(jqXHR, textStatus, errorThrown) {
          // tampilkan alert qrcode tidak valid
          Swal.fire({
            title: 'Error!',
            text: 'QR Code Tidak Valid',
            icon: 'error',
          })
          // modal dengan id camera ditutup
          $('#camera').modal('hide');
        }
      });
    }
  }

  $('#camera').on('hidden.bs.modal', function() {
    // Hentikan pemindaian QR Code dan kamera
    html5QrcodeScanner.clear(); // Hentikan kamera

    // Set variabel modalIsDisplayed kembali ke false
    modalIsDisplayed = false;
  });

  // Event listener untuk menangani penutupan modal
  $('#bayar').on('hidden.bs.modal', function() {

    // Reset the input for 'jumlah_bayar'
    document.getElementById('jumlah_bayar').value = '';

    // Reset the 'kembalian' text
    document.getElementById('kembalian').textContent = 'Rp. 0';

    // Set the modalIsDisplayed variable back to false
    modalIsDisplayed = false;
  });

  // Callback ketika terjadi kesalahan saat pemindaian
  function onScanError(errorMessage) {}
</script>
<?= $this->endSection(); ?>