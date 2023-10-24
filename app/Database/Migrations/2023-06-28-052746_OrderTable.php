<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrderTable extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => true,
                ],
                'no_order' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'nama_produk' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'kuantitas_produk' => [
                    'type' => 'VARCHAR',
                    'constraint' => 60,
                ],
                'harga_produk' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
            ]
        );

        $this->forge->addKey('id');
        $this->forge->createTable('order');
    }

    public function down()
    {
        $this->forge->dropTable('order');
    }
}
