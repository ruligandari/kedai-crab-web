<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;

class TransaksiController extends BaseController
{
    public function index()
    {
        $transaksi = new TransaksiModel();
        $dataTransaksi = $transaksi->orderby('id', 'DESC')->where('status_pesanan', 'Selesai')->findAll();
        // dd($dataTransaksi);
        $data = [
            'title' => 'Transaksi',
            'data_transaksi' => $dataTransaksi,
        ];

        return view('kasir/transaksi/index', $data);
    }
    public function detail($id)
    {
        $modelTransaksi = new TransaksiModel();

        $decodeNoTransaksi = base64_decode($id);
        $getTransaksi = $modelTransaksi->find($decodeNoTransaksi);
        $getID = $getTransaksi['id'];
        $noOrder = $getTransaksi['no_order'];
        $datasOrder = $modelTransaksi->getTransaksi($noOrder);
        $data = [
            'id' => $getID,
            'id_transaksi' => $decodeNoTransaksi,
            'nama_pembeli' => $getTransaksi['nama_pembeli'],
            'total_harga' => $getTransaksi['total_harga'],
            'data_order' => $datasOrder
        ];
        return json_encode($data);
    }

    public function bayar()
    {
        $transaksi = new TransaksiModel();
        $id = $this->request->getPost('id');
        $update = $transaksi->update($id, ['status_pesanan' => 'Selesai']);
        if ($update) {
            # code...
            return redirect()->to(base_url('kasir/transaksi'))->with('success', 'Transaksi Berhasil');
        } else {
            return redirect()->to(base_url('kasir/transaksi'))->with('error', 'Transaksi Gagal');
        }
    }
}
