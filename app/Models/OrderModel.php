<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'order_ref',
        'product_id',
        'sku',
		'name',
		'address',
		'city',
		'state',
		'postal_code',
		'country_code',
        'email',
        'phone',
        'amount',
        'currency',
        'status',
        'gateway_session_id',
        'code_id',
        'created_at',
        'updated_at',
    ];

    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Cari order berdasarkan order_ref
     *
     * @param string $ref
     * @param bool $forUpdate apakah SELECT ... FOR UPDATE (dalam transaksi)
     * @return array|null
     */
    public function findByRef(string $ref, bool $forUpdate = false): ?array
    {
        if ($forUpdate) {
            return $this->db->query(
                "SELECT * FROM `{$this->table}` WHERE `order_ref` = ? LIMIT 1 FOR UPDATE",
                [$ref]
            )->getRowArray() ?: null;
        }

        return $this->where('order_ref', $ref)->first();
    }
}
