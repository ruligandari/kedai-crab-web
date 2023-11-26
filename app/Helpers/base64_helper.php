<?php

namespace App\Helpers;

function enkripsi_base64($teks)
{
    $basis64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    $teks_enkripsi = '';
    $panjang_teks = strlen($teks);

    for ($i = 0; $i < $panjang_teks; $i += 3) {
        $blok = (ord($teks[$i]) << 16) + ((($i + 1) < $panjang_teks) ? (ord($teks[$i + 1]) << 8) : 0) + (($i + 2) < $panjang_teks ? ord($teks[$i + 2]) : 0);

        $teks_enkripsi .= $basis64[(($blok >> 18) & 0x3F)];
        $teks_enkripsi .= $basis64[(($blok >> 12) & 0x3F)];
        $teks_enkripsi .= (($i + 1) < $panjang_teks) ? $basis64[(($blok >> 6) & 0x3F)] : '=';
        $teks_enkripsi .= (($i + 2) < $panjang_teks) ? $basis64[($blok & 0x3F)] : '=';
    }

    return $teks_enkripsi;
}

// Fungsi untuk melakukan dekripsi base64
function dekripsi_base64($teks_enkripsi)
{
    $basis64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    $teks_asli = '';
    $panjang_teks = strlen($teks_enkripsi);

    for ($i = 0; $i < $panjang_teks; $i += 4) {
        $blok = ($basis64[strpos($basis64, $teks_enkripsi[$i])] << 18) + ($basis64[strpos($basis64, $teks_enkripsi[$i + 1])] << 12) + (isset($teks_enkripsi[$i + 2]) ? ($basis64[strpos($basis64, $teks_enkripsi[$i + 2])] << 6) : 0) + (isset($teks_enkripsi[$i + 3]) ? $basis64[strpos($basis64, $teks_enkripsi[$i + 3])] : 0);

        $teks_asli .= chr(($blok >> 16) & 0xFF);
        if (isset($teks_enkripsi[$i + 2])) {
            $teks_asli .= chr(($blok >> 8) & 0xFF);
        }
        if (isset($teks_enkripsi[$i + 3])) {
            $teks_asli .= chr($blok & 0xFF);
        }
    }

    return $teks_asli;
}
