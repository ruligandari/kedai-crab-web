<?php

namespace App\Helpers;

use App\Models\OrderModel;

function generateNoOrder($tahun)
{
    // mengambil data dari Model Order kemudian mengambil data terakhir dari no_order
    $order = new OrderModel();
    $dataOrder = $order->orderBy('no_order', 'DESC')->first();
    if (empty($dataOrder)) {
        $nomorTerakhir = "1";

        $noorder = sprintf("%03d", $nomorTerakhir);
        $order = "ORD/" . $noorder . "/" . $tahun;
        return $order;
    } else {
        // ambil nomor transaksi dari 001/KRAB18/2023
        $nomorTerakhir = explode('/', $dataOrder['no_order']);
        $nomor = $nomorTerakhir[1];
        $newNumber = $nomor + 1;

        $noorder = sprintf("%03d", $newNumber);
        $order = "ORD/" . $noorder . "/" . $tahun;
        return $order;
    }
}
