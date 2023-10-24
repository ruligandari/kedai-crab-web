<?php

namespace App\Controllers\Chef;

use App\Controllers\BaseController;

class ListMenuController extends BaseController
{
    public function index()
    {
        $listMenu = new \App\Models\MakananModel();
        $menus = $listMenu->findAll();
        $data = [

            'title' => 'List Menu',
            'listmenu' => $menus

        ];

        return view('chef/list-menu/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Menu',
        ];

        return view('chef/list-menu/create', $data);
    }

    public function add()
    {
        $listMenu = new \App\Models\MakananModel();

        $validation = \Config\Services::validation();

        $namaProduk = $this->request->getVar('nama_produk');
        $harga = $this->request->getVar('harga');
        $stok = $this->request->getVar('stok');
        $deskripsi = $this->request->getVar('deskripsi');

        // Validasi input
        $validation->setRules([
            'nama_produk' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'deskripsi' => 'required',
            'foto' => 'uploaded[foto]|max_size[foto,1024]|is_image[foto]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/chef/create-menu')->withInput()->with('error', $validation->listErrors());
        }

        // Ambil file foto
        $file = $this->request->getFile('foto');

        if ($file->isValid()) {
            $newName = $file->getRandomName();
            
            // Pindahkan file ke folder public/foto
            $file->move(ROOTPATH . 'public/foto', $newName);
            
            // Simpan data menu ke database
            $listMenu->save([
                'nama_produk' => $namaProduk,
                'harga' => $harga,
                'stok' => $stok,
                'deskripsi' => $deskripsi,
                'foto' => $newName,
            ]);
            
            // Redirect dengan pesan sukses
            return redirect()->to('/chef/create-menu')->with('success', 'Berhasil menambahkan menu');
        } else {
            // Jika terjadi kesalahan saat mengunggah file
            return redirect()->to('/chef/create-menu')->withInput()->with('error', 'Terjadi kesalahan saat mengunggah foto.');
        }

    }

    public function delete($id)
    {
        $listMenu = new \App\Models\MakananModel();
        $listMenu->delete($id);

        return redirect()->to('/chef/list-menu')->with('success', 'Berhasil menghapus menu');
    }
    public function detail($id)
    {
        $listMenu = new \App\Models\MakananModel();
        $datas = $listMenu->find($id);

        $data = [
            'title' => 'Detail Menu',
            'menu' => $datas
        ];

        return view('chef/list-menu/detail', $data);
    }

    public function stok($id)
    {
        $listMenu = new \App\Models\MakananModel();
        $listMenu->update($id, [
            'stok' => $this->request->getVar('stok')
        ]);

        return redirect()->to('/chef/list-menu/')->with('success', 'Berhasil mengubah stok');
    }

    public function update($id)
    {
        $listMenu = new \App\Models\MakananModel();
$validation = \Config\Services::validation();

$namaProduk = $this->request->getVar('nama_produk');
$harga = $this->request->getVar('harga');
$stok = $this->request->getVar('stok');
$deskripsi = $this->request->getVar('deskripsi');

// Validasi input
$validation->setRules([
    'nama_produk' => 'required',
    'harga' => 'required',
    'stok' => 'required',
    'deskripsi' => 'required',
    'foto' => 'max_size[foto,1024]|is_image[foto]',
]);

if (!$validation->withRequest($this->request)->run()) {
    return redirect()->to('/chef/detail-menu/'.$id)->withInput()->with('error', $validation->listErrors());
}

// Ambil file foto
$file = $this->request->getFile('foto');

if ($file->isValid()) {
    $newName = $file->getRandomName();
    
    // Pindahkan file ke folder public/foto
    $file->move(ROOTPATH . 'public/foto', $newName);
    
    // Simpan data menu ke database
    $listMenu->update($id,[
        'nama_produk' => $namaProduk,
        'harga' => $harga,
        'stok' => $stok,
        'deskripsi' => $deskripsi,
        'foto' => $newName,
    ]);
    
    // Redirect dengan pesan sukses
    return redirect()->to('/chef/detail-menu/'.$id)->with('success', 'Berhasil mengubah menu');
} else {
    // Jika terjadi kesalahan saat mengunggah file atau tidak ada file yang diubah
    $existingMenu = $listMenu->find($id);

    if ($existingMenu) {
        // Gunakan foto sebelumnya
        $listMenu->update($id,[
            'nama_produk' => $namaProduk,
            'harga' => $harga,
            'stok' => $stok,
            'deskripsi' => $deskripsi,
            'foto' => $existingMenu['foto'],
        ]);
        
        // Redirect dengan pesan sukses
        return redirect()->to('/chef/detail-menu/'.$id)->with('success', 'Berhasil mengubah menu');
    } else {
        // Jika menu tidak ditemukan
        return redirect()->to('/chef/list-menu')->with('error', 'Menu tidak ditemukan.');
    }
}

    }
}
