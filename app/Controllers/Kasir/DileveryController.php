<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class DileveryController extends BaseController
{
    public function index()
    {
        $transaksi = new TransaksiModel();
        $dataTransaksi = $transaksi->getTransaksiWithKurir();
        $kurir = new AdminModel();
        $data_kurir = $kurir->where('role', '4')->findAll();
        $data = [
            'title' => 'Delivery',
            'data_transaksi' => $dataTransaksi,
            'data_kurir' => $data_kurir,
        ];

        return view('kasir/dilevery/index', $data);
    }

    public function pilihKurir()
    {
        $id_kurir = $this->request->getPost('id_kurir');
        $no_transaksi = $this->request->getPost('no_transaksi');

        $transaksi = new TransaksiModel();
        $data = [
            'kurir' => $id_kurir,
        ];
        // update $trasaksi denga no_transaksi
        $transaksi->where('no_transaksi', $no_transaksi)->set($data)->update();

        return redirect()->to(base_url('kasir/dilevery'))->with('success', 'Berhasil memilih kurir');
    }
}
