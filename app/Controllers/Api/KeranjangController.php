<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KeranjangModel;
use CodeIgniter\API\ResponseTrait;

class KeranjangController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $keranjangModel = new KeranjangModel();

        $id_user = $this->request->getPost('id_user');
        $id_makanan = $this->request->getPost('id_makanan');
        $kuantitas = $this->request->getPost('kuantitas');

        $insert =  $keranjangModel->insert([
            'id_user' => $id_user,
            'id_makanan' => $id_makanan,
            'kuantitas' => $kuantitas
        ]);

        if ($insert) {
            return $this->respond([
                'status' => 200,
                'message' => 'Berhasil Menambahkan Keranjang',
            ], 200);
        } else {
            return $this->fail('Gagal Menambahkan Keranjang', 400);
        }
    }

    public function getAllKeranjang()
    {
        $transaksi = new KeranjangModel();
        $getKeranjang = $transaksi->findAll();
        if ($getKeranjang) {
            return $this->respond($getKeranjang, 200);
        } else {
            return $this->fail("Belum Ada Transaksi", 400);
        }
    }

    public function getKeranjang($id)
    {
        $keranjangModel = new KeranjangModel();
        try {
            $keranjang = $keranjangModel->getKeranjang($id);
            if ($keranjang) {

                $data = [
                    'status' => 200,
                    'message' => 'Berhasil Mendapatkan Data Keranjang',
                    'data' => $keranjang,
                ];
                return $this->respond($data, 200);
            } else {
                return $this->fail('Keranjang Tidak Ditemukan', 400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function updateKeranjang()
    {
        $keranjangModel = new KeranjangModel();
        $id = $this->request->getPost('id_keranjang');
        $kuantitas = $this->request->getVar('kuantitas');

        $update = $keranjangModel->where('id_keranjang', $id)->set([
            'kuantitas' => $kuantitas
        ])->update();

        if ($update) {
            return $this->respond([
                'status' => 200,
                'message' => 'Berhasil Mengubah Keranjang',
            ], 200);
        } else {
            return $this->fail('Gagal Mengubah Keranjang', 400);
        }
    }

    public function delete($id)
    {
        $keranjangModel = new KeranjangModel();
        $delete = $keranjangModel->delete($id);
        if ($delete) {
            return $this->respond([
                'status' => 200,
                'message' => 'Berhasil Menghapus Item',
            ], 200);
        } else {
            return $this->fail('Gagal Menghapus Keranjang', 400);
        }
    }

    public function totalKeranjang()
    {
        $keranjangModel = new KeranjangModel();
        $id_user = $this->request->getPost('id_user');
        // mengambil data keranjang berdasarkan id user kemudian mengembalikan total data
        $total = $keranjangModel->where('id_user', $id_user)->countAllResults();

        return $this->respond([
            'status' => 200,
            'message' => 'Berhasil Mendapatkan Data Keranjang',
            'data' => [
                'total' => $total,
            ]
        ], 200);
    }
}
