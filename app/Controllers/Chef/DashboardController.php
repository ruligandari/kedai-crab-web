<?php

namespace App\Controllers\Chef;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $transaksi = new \App\Models\TransaksiModel();
        $user = new \App\Models\UserModel();

        $totaltransaksi = $transaksi->countAllResults();
        $totalPendapatan = $transaksi->selectSum('total_harga')->get()->getRowArray();
        $totalUser = $user->countAllResults();

        $data = [
            'title' => 'Dashboard',
            'collapsed' => '',
            'totaltransaksi' => $totaltransaksi ?? '0',
            'totalPendapatan' => $totalPendapatan['total_harga'] ?? '0',
            'totalUser' => $totalUser ?? '0',
        ];

        return view('chef/dashboard/index', $data);
    }
}
