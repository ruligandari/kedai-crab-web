<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTransaksiTable extends Migration
{
    public function up()
    {
        // Add missing columns to existing transaksi table
        $fields = [
            'no_transaksi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'id',
            ],
            'status_pesanan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'status',
            ],
            'kurir' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'status_pesanan',
            ],
        ];
        
        $this->forge->addColumn('transaksi', $fields);
        
        // Rename qrcode to qr_code if it exists
        if ($this->db->fieldExists('qrcode', 'transaksi')) {
            $this->forge->modifyColumn('transaksi', [
                'qrcode' => [
                    'name' => 'qr_code',
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('transaksi', ['no_transaksi', 'status_pesanan', 'kurir']);
        
        // Rename back qr_code to qrcode
        if ($this->db->fieldExists('qr_code', 'transaksi')) {
            $this->forge->modifyColumn('transaksi', [
                'qr_code' => [
                    'name' => 'qrcode',
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
            ]);
        }
    }
}
