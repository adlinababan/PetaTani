<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentEventModel extends Model
{
    protected $table      = 'payment_events';
    protected $primaryKey = 'id';
    protected $allowedFields = ['event_id','order_ref','payload','processed_at'];
    protected $useTimestamps = false;

    public function isProcessed(string $eventId): bool
    {
        return (int) $this->where('event_id', $eventId)->countAllResults() > 0;
    }

    public function insertEvent(string $eventId, string $orderRef, array $payload): void
    {
        $this->insert([
            'event_id'    => $eventId,
            'order_ref'   => $orderRef,
            'payload'     => json_encode($payload, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),
            'processed_at'=> date('Y-m-d H:i:s'),
        ]);
    }
}
