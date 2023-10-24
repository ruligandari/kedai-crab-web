<?php

namespace App\Controllers\Kurir;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('status', 'Dilevery')->findAll();
        $data = [
            'title' => 'Pesan Antar',
            'transaksi' => $transaksi
        ];
        return view('kurir/dashboard/index', $data);
    }

    public function listAntar()
    {
        $id_order = $this->request->getPost('id_order');
        $nama = $this->request->getPost('nama');
        $id = $this->request->getPost('id');

        $dataOrder = new OrderModel();
        $dataUser = new UserModel();
        $dataTransaksi = new TransaksiModel();

        $nama_pembeli = $dataTransaksi->select('nama_pembeli')->where('id', $id)->first();
        $alamat = $dataUser->where('nama', $nama)->find();
        $order = $dataOrder->where('no_order', $id_order)->findAll();
        $data = [
            'order' => $order,
            'alamat' => $alamat,
            'nama_pembeli' => $nama_pembeli
        ];
        return json_encode($data);
    }

    public function antar()
    {
        $transaksi = new TransaksiModel();
        // update status transaksi jadi selesai
        $id = $this->request->getPost('id');

        $data = [
            'status_pesanan' => 'Selesai'
        ];

        $transaksi->update($id, $data);
        return redirect()->to(base_url('kurir/dashboard'))->with('success', 'Silahkan Antrakan Pesanan');
    }
}
