<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

use function App\Helpers\createJWT;

class AuthController extends BaseController
{
    use ResponseTrait;
    var $dataResponse = array();

    public function __construct()
    {
        $this->dataResponse = [
            'status' => 200,
            'message' => 'success',
            'data' => null,
        ];
    }
    public function login()
    {
        // membuat logika login dengan rest api
        $email = $this->request->getPost('email');
        $password = $this->request->getVar('password');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $datas = [
                    'user_id' => $user['id'],
                    'nama' => $user['nama'],
                ];
                helper('jwt');
                $jwt = createJWT($email);
                $dataResponse = [
                    'status' => 200,
                    'message' => 'Login Berhasil',
                    'data' => $datas,
                    'token' => $jwt,

                ];
                return $this->respond($dataResponse, 200);
            } else {
                return $this->fail('Email atau Password Tidak ditemukan', 400);
            }
        } else {
            return $this->fail('Email atau Password Tidak ditemukan', 400);
        }
    }

    public function register()
    {
        // membuat logika register dengan rest api
        $password = $this->request->getVar('password');
        $confirm_password = $this->request->getVar('confirm_password');
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'alamat' => $this->request->getPost('alamat'),
            'no_telp' => $this->request->getPost('no_telp'),
        ];

        // membuat validation data ketika ada yang error data bisa dikembalikan ke json
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required',
            'email' => 'required|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'alamat' => 'required',
            'no_telp' => 'required',
        ]);
        $userModel = new UserModel();

        if ($validation->withRequest($this->request)->run()) {
            # code...
            $userModel->insert($data);
            $dataResponse = [
                'status' => 200,
                'message' => 'Register Berhasil',
            ];
            return $this->respond($dataResponse, 200);
        } else {
            return $this->fail($validation->getErrors(), 400);
        }
    }
}
