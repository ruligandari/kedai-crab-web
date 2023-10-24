<?php

namespace App\Helpers;

use App\Models\TransaksiModel;

function generateNoTransaksi($tahun){
    // ambil data dari Transaksi model kemudian ambil data terakhir
    $transaksi = new TransaksiModel();
    $dataTransaksi = $transaksi->orderBy('no_transaksi', 'DESC')->first();
    if(empty($dataTransaksi)){
        $nomorTerakhir = 1;
        $nomortransaksi = sprintf("%03d",$nomorTerakhir);
        $nomortransaksi .= "/KRAB18/".$tahun;
        return $nomortransaksi;
    } else {
        // ambil nomor transaksi dari 001/KRAB18/2023
        $nomorTerakhir = explode('/', $dataTransaksi['no_transaksi']);
        $nomor = $nomorTerakhir[0];
        $newNumber = $nomor + 1;
        $nomortransaksi = sprintf("%03d",$newNumber);
        $nomortransaksi .= "/KRAB18/".$tahun;
        return $nomortransaksi;
    }

}