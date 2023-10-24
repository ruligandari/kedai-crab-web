<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder2 extends Seeder
{
    public function run()
    {
        $data = [
            [

                'nama' => "Owner Kedai Crab",
                'email' => "owner@gmail.com",
                'password' => password_hash("owner1234", PASSWORD_DEFAULT),
                'no_telp' => '0877762211',
                'role' => 1
            ],
            [
                'nama' => "Asep Jajang",
                'email' => "kurir1@gmail.com",
                'password' => password_hash("kurir1234", PASSWORD_DEFAULT),
                'no_telp' => '0877762211',
                'no_telp' => '0877762211',
                'role' => 4

            ]
        ];

        $this->db->table('admin')->insertBatch($data);
    }
}
