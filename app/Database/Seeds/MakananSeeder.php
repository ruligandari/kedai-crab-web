<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MakananSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_produk' => 'Kepiting Bakar',
                'harga' => '120000',
                'deskripsi' => 'Kepiting pilihan terbaik dan diolah dengan bumbu nikmat',
                'foto' => 'kepiting.jpeg'
            ],
            [
                'nama_produk' => 'Iga Bakar',
                'harga' => '150000',
                'deskripsi' => 'Iga Sapi pilihan terbaik dan diolah dengan bumbu nikmat',
                'foto' => 'Iga Bakar.jpeg'
            ]
        ];

        $this->db->table('makanan')->insertBatch($data);
    }
}
