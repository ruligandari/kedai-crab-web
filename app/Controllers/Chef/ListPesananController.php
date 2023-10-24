<?php

namespace App\Controllers\Chef;

use App\Controllers\BaseController;

class ListPesananController extends BaseController
{
    public function index()
    {
        $listPesanan = new \App\Models\TransaksiModel();
        $data = [
            'title' => 'List Pesanan',
            // tambahkan order descending
            'listpesanan' => $listPesanan->orderBy('id', 'DESC')->findAll(),
        ];

        return view('chef/list-pesanan/index', $data);
    }

    public function detail($id)
    {
        $listPesanan = new \App\Models\TransaksiModel();
        $noOrder = $listPesanan->select('no_order')->where('id', $id)->first();
        $status_pesanan = $listPesanan->select('status_pesanan')->where('id', $id)->first();
        $pesanan = $status_pesanan['status_pesanan'];
        $no_order = $listPesanan->getTransaksi($noOrder);
        $data = [
            'title' => 'Detail Pesanan',
            'listpesanan' => $no_order,
            'id' => $id,
            'status_pesanan' => $pesanan,
        ];

        return view('chef/list-pesanan/detail', $data);
    }

    public function updateStatus()
    {
        $request = service('request');

        // dapatkan id dari /chef/detail-pesanan/(:any)

        $id = $this->request->getPost('id');
        $status = 'Diproses';
        $listPesanan = new \App\Models\TransaksiModel();
        $listPesanan->update($id, ['status_pesanan' => $status]);
        session()->setFlashdata('success', 'Status Pesanan Berhasil Diubah');
        return redirect()->to(base_url('chef/list-pesanan'));
    }
}
