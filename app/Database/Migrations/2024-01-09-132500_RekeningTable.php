<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RekeningTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'nama_bank' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'no_rekening' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'saldo' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
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

        $this->forge->addKey('id', true);
        $this->forge->createTable('rekening');
    }

    public function down()
    {
        $this->forge->dropTable('rekening');
    }
}
