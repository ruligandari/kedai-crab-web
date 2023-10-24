<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [

                'nama' => "Dede Solihin",
                'email' => "kasir1@gmail.com",
                'password' => password_hash("kasir1234", PASSWORD_DEFAULT),
                'no_telp' => '0877762211',
                'role' => 2
            ],
            [
                'nama' => "Andi Gunawan",
                'email' => "chef1@gmail.com",
                'password' => password_hash("chef1234", PASSWORD_DEFAULT),
                'no_telp' => '0877762211',
                'no_telp' => '0877762211',
                'role' => 3

            ]
        ];

        $this->db->table('admin')->insertBatch($data);
    }
}
