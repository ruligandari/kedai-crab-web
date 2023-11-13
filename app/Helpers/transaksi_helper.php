<?php

namespace App\Helpers;

use App\Models\TransaksiModel;

function generateNoTransaksi()
{
    // Mendapatkan tanggal, bulan, dan tahun saat ini
    $tanggal = date('d');
    $bulan = date('m');
    $tahun = date('Y');

    // Ambil data dari Transaksi model kemudian ambil data terakhir
    $transaksi = new TransaksiModel();
    $dataTransaksi = $transaksi->orderBy('no_transaksi', 'DESC')->first();

    if (empty($dataTransaksi)) {
        // Jika tidak ada data transaksi sebelumnya, nomor terakhir diatur ke 1
        $nomorTerakhir = 1;
    } else {
        // Jika ada data transaksi sebelumnya, ambil nomor transaksi terakhir
        $nomorTerakhir = intval(substr($dataTransaksi['no_transaksi'], 2, 2)) + 1;
    }

    // Format nomor transaksi dengan leading zeros
    $nomorTransaksi = sprintf("%02d", $nomorTerakhir);

    // Format nomor transaksi lengkap
    $nomorTransaksiLengkap = "CB{$nomorTransaksi}{$tanggal}{$bulan}{$tahun}";

    return $nomorTransaksiLengkap;
}
