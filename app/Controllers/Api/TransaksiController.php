<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Models\KeranjangModel;
use App\Models\MakananModel;
use App\Models\OrderModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;
use \Config\Services;
use CodeIgniter\API\ResponseTrait;
use Exception;

use function App\Helpers\generateNoOrder;
use function App\Helpers\generateNoTransaksi;
use function App\Helpers\generateQrCode;

class TransaksiController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $request = service('request');
        helper('transaksi');
        $tahun = date('Y');
        $noTransaksi = generateNoTransaksi();
        $enkripsiNoTransaksi = base64_encode($noTransaksi);
        helper('qrcode');
        helper('order');

        $orderItem = new OrderModel();
        $transaksi = new TransaksiModel();

        $jsonPayload = $request->getVar();
        if (empty($jsonPayload)) {
            return $this->fail('Data Tidak Ditemukan', 400);
        }

        if (!is_object($jsonPayload)) {
            return $this->fail('Data Harus Berupa Object', 400);
        }
        $tglTransaksi = date('Y-m-d');
        $namaPembeli = $jsonPayload->nama_pembeli;
        $itemsOrder = $jsonPayload->items;
        $hargaTotal = $jsonPayload->total_harga;
        $status = $jsonPayload->status;

        $data_transaksi = [
            'no_transaksi' => $noTransaksi,
            'no_order' => generateNoOrder($tahun),
            'nama_pembeli' => $namaPembeli,
            'total_harga' => $hargaTotal,
            'tgl_transaksi' => $tglTransaksi,
            'status' => $status,
            'qr_code' => generateQrCode($enkripsiNoTransaksi)
        ];
        try {
            $transaksi->insert($data_transaksi);
        } catch (Exception $e) {
            return $this->fail('Gagal Menambahkan Data', 400);
        }

        foreach ($itemsOrder as $item) {
            $orders = [
                'no_order' => $data_transaksi['no_order'],
                'nama_produk' =>  $item->nama_produk,
                'kuantitas_produk' => $item->kuantitas,
                'harga_produk' => $item->harga_produk,
            ];
            try {
                $orderItem->insert($orders);
            } catch (Exception $e) {
                return $this->fail('Gagal Menambahkan Data', 400);
            }
        }
        $data = [
            'messages' => "Transaksi Berhasil, Tunjukan QR Code ini ke Kasir",
            'qrcode' => generateQrCode($enkripsiNoTransaksi),
        ];

        return $this->respond($data, 200);
    }

    public function addTransaksi()
    {
        date_default_timezone_set('Asia/Jakarta'); // Set zona waktu ke Asia/Jakarta
        helper('qrcode');
        helper('order');
        helper('transaksi');
        $tahun = date('Y');
        $idUser = $this->request->getVar('id_user');
        $status = $this->request->getVar('status');
        $total = $this->request->getVar('total');


        $transaksi = new TransaksiModel();
        $user = new UserModel();
        $keranjang = new KeranjangModel();
        $orders = new OrderModel();
        $makanan = new MakananModel();
        $bank = new BankModel();

        // switch status
        switch ($status) {
            case 1:
                $status = 'Dilevery';
                break;
            case 2:
                $status = 'Dine In';
                break;
            case 3:
                $status = 'Take Away';
                break;
            default:
                $status = 'Belum Ada Status';
                break;
        }
        // insert data dikeranjang ke order
        $getKeranjang = $keranjang->getKeranjang($idUser);
        $no_order = generateNoOrder($tahun);
        if (count($getKeranjang) > 1 && $getKeranjang != null) {
            $data = [];
            foreach ($getKeranjang as $item) {
                $datas = [
                    'no_order' => $no_order,
                    'nama_produk' => $item['nama_produk'],
                    'kuantitas_produk' => $item['kuantitas'],
                    'harga_produk' => $item['harga'],
                ];

                $kuantitas = $datas['kuantitas_produk'];

                $makanan->where('id', $item['id'])->set('stok', "stok - $kuantitas", false)->update();

                $data[] = $datas;
            }
            try {
                $orders->insertBatch($data);
            } catch (Exception $e) {
                return $this->fail($e, 400);
            }
        } else if (count($getKeranjang) == 1 && $getKeranjang != null) {
            $data = [
                'no_order' => $no_order,
                'nama_produk' => $getKeranjang[0]['nama_produk'],
                'kuantitas_produk' => $getKeranjang[0]['kuantitas'],
                'harga_produk' => $getKeranjang[0]['harga'],
            ];
            try {
                $orders->insert($data);
                $id_produk = $getKeranjang[0]['id'];
                $kuantitas_produk = $getKeranjang[0]['kuantitas'];
                $makanan->where('id', $id_produk)->set('stok', "stok - $kuantitas_produk", false)->update();
            } catch (Exception $e) {
                return $this->fail($e, 400);
            }
        } else {
            return $this->fail("Keranjang Kosong", 400);
        }

        $no_transaksi = generateNoTransaksi();
        // insert data ke tabel transaksi
        $dataTransaksi = [
            'no_transaksi' => $no_transaksi,
            'no_order' => $no_order,
            'nama_pembeli' => $user->find($idUser)['nama'],
            'total_harga' => $total,
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'status' => $status,
            'status_pesanan' => 'Pesanan Berhasil',
            'qr_code' => generateQrCode(base64_encode(generateNoTransaksi()))
        ];
        try {
            $transaksi->insert($dataTransaksi);
            if ($status == 'Dilevery') {
                $bank->where('id', '1')->set('saldo', "saldo - $total", false)->update();
            }
        } catch (Exception $e) {
            return $this->fail("Gagal Menambahkan Data", 400);
        }


        // hapus data di keranjang
        try {
            // delete data keranjang berdasarkan id user
            $keranjang->where('id_user', $idUser)->delete();
        } catch (\Throwable $th) {
            return $this->fail("Gagal Menghapus Data Keranjang", 400);
        }
        
        $encode = base64_encode($no_transaksi);
        
        // cari id terakhir dari $transaksi
        try {
            $id_transaksi = $transaksi->select('id')->orderBy('id', 'DESC')->first();
            $getQr = $transaksi->where('id', $id_transaksi['id'])->select('qr_code')->first();
        } catch (\Throwable $th) {
            return $this->fail("Gagal Mengambil Data Transaksi", 400);
        }
        
        if ($status == 'Dilevery') {
            // mendapatkan saldo dari model bank hanya jika status Dilevery
            try {
                $sisa_saldo = $bank->where('id', 1)->select('saldo')->first();
                $data = [
                    'messages' => "Transaksi Berhasil, Tunjukan QR Code ini ke Kasir",
                    'saldo' => $sisa_saldo['saldo'],
                    'qrcode' => $getQr['qr_code'],
                    'no_order' => $no_order,
                    'encode' => $encode,
                    'total' => $total,
                ];
            } catch (\Throwable $th) {
                return $this->fail("Gagal Mengambil Data Saldo Bank", 400);
            }
        } else {
            $data = [
                'messages' => "Transaksi Berhasil, Tunjukan QR Code ini ke Kasir",
                'qrcode' => $getQr['qr_code'],
                'no_order' => $no_order,
                'encode' => $encode,
                'total' => $total,
            ];
        }


        return $this->respond($data, 200);
    }

    public function getTransaksiById($id)
    {
        $transaksi = new TransaksiModel();
        $user = new UserModel();
        $getNamaUser = $user->find($id);
        if ($getNamaUser) {
            // urutan dsc

            $getDataOrder = $transaksi->where('nama_pembeli', $getNamaUser['nama'])->orderBy('id', 'DESC')->findAll();
            // menambahkan data nama user ke $getDataOrder
            foreach ($getDataOrder as $key => $value) {
                // mendapatkan no_transaksi, kemudian enkripsi dengan base 64, menambahkan key baru dengan nama encode
                $getDataOrder[$key]['encode'] = base64_encode($value['no_transaksi']);
            }
            return $this->respond($getDataOrder, 200);
        } else {
            return $this->fail("Belum Ada Transaksi", 400);
        }
    }

    public function detailOrder()
    {
        $noOrder = $this->request->getVar('no_order');
        $orderItem = new OrderModel();
        $getOrderItem = $orderItem->where('no_order', $noOrder)->findAll();
        if (!$getOrderItem) {
            return $this->fail("Tidak Ada Order", 400);
        }
        $data = [
            'data' => $getOrderItem,
        ];
        return $this->respond($data, 200);
    }

    public function updatePesanan()
    {
        $no_transaksiEncode = $this->request->getVar('no_transaksi');
        $no_transaksi = base64_decode($no_transaksiEncode);

        // cari tabel transaksi dengan no_transaksi
        $transaksi = new TransaksiModel();
        $getTransaksi = $transaksi->where('no_transaksi', $no_transaksi)->first();
        if (!$getTransaksi) {
            return $this->fail("Transaksi Tidak Ditemukan", 400);
        }
        // update status pesanan jika status = Dilevery
        if ($getTransaksi['status'] == 'Dilevery') {
            $data = [
                'status_pesanan' => 'Selesai',
            ];
            try {
                $transaksi->where('no_transaksi', $no_transaksi)->set($data)->update();
                return $this->respond('Pesanan Berhasil Diterima', 200);
            } catch (Exception $e) {
                return $this->fail("Gagal Mengupdate Data", 400);
            }
        } else {

            $data = [
                'status_pesanan' => 'Diterima',
            ];
            try {
                $transaksi->where('no_transaksi', $no_transaksi)->set($data)->update();
                return $this->respond('Pesanan Berhasil Diterima', 200);
            } catch (Exception $e) {
                return $this->fail("Gagal Mengupdate Data", 400);
            }
        }
    }
}
