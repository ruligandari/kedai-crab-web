<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixQrCodeColumn extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('transaksi', [
            'qr_code' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('transaksi', [
            'qr_code' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
    }
}
