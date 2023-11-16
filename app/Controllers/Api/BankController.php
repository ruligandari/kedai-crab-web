<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class BankController extends BaseController
{
    use ResponseTrait;
    public function index($id)
    {
        $user = new UserModel();
        $bank = new BankModel();

        $getUser = $user->where('id', $id)->first();
        if ($getUser) {
            $alamat = $getUser['alamat'];
            // Jl. Sadapaingan No. 45, Cigasong , Majalengka ambil data Cigasong saja, atau setelah delimiter ',';
            // Menggunakan fungsi strstr untuk mencari teks setelah koma
            $alamatArray = explode(',', $alamat);

            // Ambil elemen kedua dari array (indeks 1) dan hapus whitespace menggunakan trim
            $kecamatan = trim($alamatArray[1]);
            $dataKecamatan = [
                'Cigasong' => '10000',
                'Jatiwangi' => '15000',
                'Majalengka' => '12000'
            ];

            // lakukan pencocokan $kecamatan yang isinya dynamic dan $dataKecamatan berdasarkan key, dan keluarkan isinya
            $ongkir = $dataKecamatan[$kecamatan];


            $getRekening = $bank->findAll();

            $dataResponse = [
                'status' => 200,
                'message' => 'success',
                'ongkir' => $ongkir,
                'data' => $getRekening[0]
            ];

            return $this->respond($dataResponse, 200);
        } else {
            $dataResponse = [
                'status' => 404,
                'message' => 'User Tidak Ditemukan',
            ];

            return $this->respond($dataResponse, 404);
        }
    }
}
