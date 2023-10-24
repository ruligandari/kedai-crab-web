<?php

namespace App\Controllers\Pemilik;

use App\Controllers\BaseController;

class ListTransaksiController extends BaseController
{
    public function index()
    {
        $transaksi = new \App\Models\TransaksiModel();
        $transaksis = $transaksi->findAll();
        $data = [
            'title' => 'List Transaksi',
            'transaksi' => $transaksis,
        ];

        return view('pemilik/transaksi/index', $data);
    }
}
