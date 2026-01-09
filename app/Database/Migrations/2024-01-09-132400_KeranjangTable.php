<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KeranjangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_keranjang' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_makanan' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'kuantitas' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_keranjang', true);
        $this->forge->createTable('keranjang');
    }

    public function down()
    {
        $this->forge->dropTable('keranjang');
    }
}
