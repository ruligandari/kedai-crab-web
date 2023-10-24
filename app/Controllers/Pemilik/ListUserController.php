<?php

namespace App\Controllers\Pemilik;

use App\Controllers\BaseController;

class ListUserController extends BaseController
{
    public function index()
    {
        $admin = new \App\Models\AdminModel();
        $admins = $admin->findAll();
        $data = [
            'title' => 'List User',
            'admin' => $admins,
        ];

        return view('pemilik/user/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah User',
        ];

        return view('pemilik/user/create', $data);
    }

    public function add()
    {
        $nama = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        $password = $this->request->getVar('password');
        $role = $this->request->getPost('role');
        $no_telp = $this->request->getPost('no_telp');

        $admin = new \App\Models\AdminModel();
        $admin->insert([
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'no_telp' => $no_telp,
        ]);

        return redirect()->to(base_url('pemilik/list-user'))->with('success', 'User berhasil ditambahkan');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $admin = new \App\Models\AdminModel();
        $admin->delete($id);

        return json_encode([
            'status' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        $password = $this->request->getVar('new_password');
        $no_telp = $this->request->getPost('no_telp');

        $data = [
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'no_telp' => $no_telp,
        ];

        $admin = new \App\Models\AdminModel();
        $admin->update($id, $data);

        return redirect()->to(base_url('pemilik/list-user'))->with('success', 'User berhasil diupdate');
    }
}
