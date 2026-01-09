<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterMakananTable extends Migration
{
    public function up()
    {
        // Add satuan column to existing makanan table
        $fields = [
            'satuan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'stok',
            ],
        ];
        
        $this->forge->addColumn('makanan', $fields);
    }

    public function down()
    {
        // Remove the satuan column
        $this->forge->dropColumn('makanan', 'satuan');
    }
}
