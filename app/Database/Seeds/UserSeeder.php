<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama'=> 'Dwi Putra',
            'email' => 'dwiputra@gmail.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'alamat' => 'Jl. Raya Kedai Ledang',
            'no_telp' => '081234567890',
        ];

        $this->db->table('user')->insert($data);
    }
}
