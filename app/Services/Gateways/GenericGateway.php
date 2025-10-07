<?php
namespace App\Services\Gateways;

use App\Services\PaymentGatewayAdapter;

class GenericGateway implements PaymentGatewayAdapter
{
    public function createPaymentSession(array $params): array
    {
        $payUrl = '/checkout/success?order=' . urlencode($params['order_ref']);
        return [
            'id'      => 'GENERIC-' . strtoupper(substr($params['order_ref'], 0, 8)),
            'pay_url' => $payUrl,
            'raw'     => [],
        ];
    }

    public function verifySignature(string $rawBody, string $signature, string $timestamp): bool { return true; }
    public function extractEventId(array $payload): string { return $payload['event_id'] ?? uniqid('evt_', true); }
    public function extractOrderRef(array $payload): string { return $payload['order_ref'] ?? ''; }
    public function extractStatus(array $payload): string { return strtolower($payload['status'] ?? 'paid'); }
}
