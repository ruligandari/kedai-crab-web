<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Login'
        ];

        return view('auth/index', $data);
    }

    public function auth()
    {
        $adminModel = new AdminModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // proses login
        $getEmail = $adminModel->where('email', $email)->find();
        if ($getEmail) {

            $verifyPassword = password_verify($password, $getEmail[0]['password']);

            if ($verifyPassword) {

                session()->set('id', $getEmail[0]['id']);
                session()->set('nama', $getEmail[0]['nama']);
                session()->set('email', $getEmail[0]['email']);
                session()->set('role', $getEmail[0]['role']);
                session()->set('isLoggedIn', true);

                switch (session('role')) {
                    case 1:
                        return redirect('pemilik/dashboard');
                        break;
                    case 2:
                        return redirect('kasir/dashboard');
                        break;
                    case 3:
                        return redirect('chef/dashboard');
                        break;
                    case 4:
                        return redirect('kurir/dashboard');
                        break;
                }
            } else {
                return redirect()->to('login')->with('error', 'Email atau Katasandi salah');
            }
        } else {
            return redirect()->to('login')->with('error', 'Email atau Katasandi salah');
        }
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('login');
    }
}
