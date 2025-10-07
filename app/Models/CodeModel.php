<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeModel extends Model
{
    protected $table            = 'codes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'code',
        'sku',
        'meta',
        'status',
        'order_id',
        'created_at',
        'updated_at',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cek apakah SKU tertentu masih tersedia stok kodenya
     */
    public function hasAvailableStock(string $sku): bool
    {
        return (int) $this->where('sku', $sku)
            ->where('status', 'available')
            ->countAllResults() > 0;
    }

    /**
     * Lock dan ambil 1 kode pertama yang tersedia (FOR UPDATE), lalu assign ke order_id
     * Harus dipanggil dalam transaksi DB
     */
    public function lockAndAssignFirstAvailable(string $sku, int $orderId): ?array
    {
        $row = $this->db->query(
            "SELECT * FROM `{$this->table}` 
             WHERE `sku` = ? AND `status` = 'available' 
             ORDER BY `id` ASC 
             LIMIT 1 FOR UPDATE",
            [$sku]
        )->getRowArray();

        if (!$row) return null;

        $this->update($row['id'], [
            'status'    => 'sold',
            'order_id'  => $orderId,
        ]);

        return $row;
    }

    /**
     * (Opsional) Enkripsi kode sebelum disimpan, dengan AES-256-GCM
     */
    protected function encryptValue(string $plain): string
    {
        $key = base64_decode(getenv('CODES_ENC_KEY') ?: '', true);
        if (!$key || strlen($key) !== 32) return $plain;

        $iv  = random_bytes(12);
        $tag = '';
        $cipher = openssl_encrypt($plain, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return base64_encode($cipher) . ':' . base64_encode($iv) . ':' . base64_encode($tag);
    }

    /**
     * (Opsional) Dekripsi kode yang disimpan terenkripsi
     */
    protected function decryptValue(string $stored): string
    {
        $key = base64_decode(getenv('CODES_ENC_KEY') ?: '', true);
        if (!$key || strlen($key) !== 32) return $stored;

        $parts = explode(':', $stored);
        if (count($parts) !== 3) return $stored;

        [$c, $iv, $tag] = array_map('base64_decode', $parts);
        $plain = openssl_decrypt($c, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return $plain !== false ? $plain : $stored;
    }
}
