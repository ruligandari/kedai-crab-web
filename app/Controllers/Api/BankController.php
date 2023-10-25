<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BankModel;
use CodeIgniter\API\ResponseTrait;

class BankController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $bank = new BankModel();

        $getRekening = $bank->findAll();

        $dataResponse = [
            'status' => 200,
            'message' => 'success',
            'data' => $getRekening[0]
        ];

        return $this->respond($dataResponse, 200);
    }
}
