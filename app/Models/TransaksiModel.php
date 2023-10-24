<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'transaksi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getTransaksi($id)
    {
        // buat relasi dari tabel transaksi ke tabel order build
        $result = $this->join('order', 'order.no_order = transaksi.no_order');
        return $result->where('transaksi.no_order', $id)->findAll();
    }

    public function getByNoTransaksi($id)
    {
        // buat relasi dari tabel transaksi ke tabel order build
        $result = $this->join('order', 'order.no_order = transaksi.no_order');
        return $result->where('transaksi.no_transaksi', $id)->find();
    }
    public function getAllTransaksiByUser($id)
    {
        // buat relasi dengan tabel user dengan id untuk mendapatkan nama

    }
}
