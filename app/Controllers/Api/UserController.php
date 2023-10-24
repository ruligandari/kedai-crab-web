<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
{
    use ResponseTrait;
    public function getUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        $data = [
            'status' => 200,
            'message' => 'Berhasil Mendapatkan Data User',
            'data' => [
                'nama' => $user['nama'],
                'alamat' => $user['alamat'],
                'no_hp' => $user['no_telp'],
            ],
        ];
        if (!$user) {
            return $this->failNotFound('User Tidak Ditemukan', 400);
        }
        return $this->respond($data, 200);
    }
}
