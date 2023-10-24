<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
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
        $noTransaksi = generateNoTransaksi($tahun);
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

        // insert data ke tabel transaksi
        $dataTransaksi = [
            'no_transaksi' => generateNoTransaksi($tahun),
            'no_order' => $no_order,
            'nama_pembeli' => $user->find($idUser)['nama'],
            'total_harga' => $total,
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'status' => $status,
            'status_pesanan' => 'Pesanan Berhasil',
            'qr_code' => generateQrCode(base64_encode(generateNoTransaksi($tahun)))
        ];
        try {
            $transaksi->insert($dataTransaksi);
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

        $data = [
            'messages' => "Transaksi Berhasil, Tunjukan QR Code ini ke Kasir",
            'qrcode' => generateQrCode(base64_encode(generateNoTransaksi($tahun))),
        ];


        return $this->respond($data, 200);
    }

    public function getTransaksiById($id)
    {
        $transaksi = new TransaksiModel();
        $user = new UserModel();
        $getNamaUser = $user->find($id);
        if ($getNamaUser) {
            $getDataOrder = $transaksi->where('nama_pembeli', $getNamaUser['nama'])->findAll();
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
}
