<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MakananModel;
use CodeIgniter\API\ResponseTrait;

class MakananController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $makanaModel = new MakananModel();
        $getAllData = $makanaModel->findAll();

        $dataResponse = [
            'status' => 200,
            'message' => 'success',
            'data' => $getAllData
        ];

        if($getAllData){

            return $this->respond($dataResponse, 200);
        } else {
            return $this->fail("Tidak Dapat Mengambil Data", 400);
        }
    }

    public function detail($id)
    {
        $makanaModel = new MakananModel();
        $getData = $makanaModel->find($id);

        $dataResponse = [
            'status' => 200,
            'message' => 'success',
            'data' => $getData
        ];

        if($getData){
            return $this->respond($dataResponse, 200);
        } else {
            return $this->fail("Data Tidak ditemukan", 400);
        }
    }
}
