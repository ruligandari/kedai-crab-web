<?= $this->extend('layouts/kurir/main-layouts'); ?>

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
                            <h5 class="card-title">Pesan Antar</h5>
                            <table class="table" id="table-transaksi">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Pembeli</th>
                                        <th scope="col">Nomor Order</th>
                                        <th scope="col">Total Harga</th>
                                        <th scope="col">Jenis Pesanan</th>
                                        <th scope="col">Status Pesanan</th>
                                        <th scope="col">Tgl</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($transaksi as $item) : ?>
                                        <tr>
                                            <th scope="row"><?= $no++ ?></th>
                                            <td><?= $item['nama_pembeli'] ?></td>
                                            <td><?= $item['no_order'] ?></td>
                                            <td><?= $item['total_harga'] ?></td>
                                            <td><?= $item['status'] ?></td>
                                            <td><span class="badge bg-success text-light"><?= $item['status_pesanan'] ?></span></td>
                                            <td><?= $item['tgl_transaksi'] ?></td>
                                            <td>
                                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#antar-pesanan" onClick="getOrder('<?= $item['no_order'] ?>', '<?= $item['nama_pembeli'] ?>' ,'<?= $item['id'] ?>')">
                                                    <i class="bi bi-truck"></i>
                                                </button>
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
    <div class="modal fade" id="antar-pesanan" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengantaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-control">
                        <label for="nama">Nama Pembeli</label>
                        <input type="text" id="nama_pembeli" class="form-control md-2">
                        <label for="nama_pembeli">Produk Order</label>
                        <div id="inputContainer"></div>
                        <label for="total_harga">Alamat</label>
                        <input type="text" id="alamat" class="form-control md-2" readonly>
                        <label for="total_harga">No Hp</label>
                        <input type="text" id="nohp" class="form-control md-2" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="<?= base_url('kurir/antar') ?>" method="post">
                        <input type="hidden" id="no_id" name="id">
                        <button class="btn btn-success">Antarkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<!-- Datatables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    // ajax untuk mendapatkan data order dari no_order
    // define saat halaman dimua

    function getOrder(id_order, nama, id) {
        // hapus inputan sebelumnya
        $('#inputContainer').empty();
        $('#alamat').empty();
        $.ajax({
            url: "<?= base_url('kurir/list-antar') ?>",
            type: "POST",
            data: {
                id_order: id_order,
                nama: nama,
                id: id
            },
            dataType: "JSON",
            success: function(data) {
                // alamat
                $('#alamat').val(data.alamat[0].alamat);
                $('#nama_pembeli').val(data.nama_pembeli.nama_pembeli);
                // nohp
                $('#nohp').val(data.alamat[0].no_telp);
                for (let i = 0; i < data.order.length; i++) {
                    $('#inputContainer').append(`
                        <div>
                            <input type="text" value="${data.order[i].nama_produk} X ${data.order[i].kuantitas_produk}" class="form-control md-2 mt-2">
                        </div>
                        `);
                }

                // id
                $('#no_id').val(id);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }
</script>

<script>
    //    datatable dengan id table-transaksi, tambah script export ke excel
    $(document).ready(function() {
        $('#table-transaksi').DataTable({
            dom: 'lBfrtip',
            buttons: [
                'excel',
                'pdf',
            ]
        });
    });
</script>
<?= $this->endSection(); ?>