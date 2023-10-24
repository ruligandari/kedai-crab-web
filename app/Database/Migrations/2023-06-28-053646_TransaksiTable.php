<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransaksiTable extends Migration
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
                'nama_pembeli' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'no_order' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'total_harga' => [
                    'type' => 'VARCHAR',
                    'constraint' => 60,
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'tgl_transaksi' => [
                    'type' => 'DATETIME',
                ],
                'qrcode' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
            ]
        );

        $this->forge->addKey('id');
        $this->forge->createTable('transaksi');
        
    }

    public function down()
    {
        $this->forge->dropTable('transaksi');
    }
}
