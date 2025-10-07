<?php
namespace App\Services;

interface PaymentGatewayAdapter
{
    /**
     * Buat sesi/invoice pembayaran ke gateway.
     * Wajib mengembalikan minimal:
     *  - id:   string|null  (reference/invoice id dari gateway)
     *  - pay_url: string    (URL halaman pembayaran)
     *  - raw: array         (opsional, respons mentah)
     */
    public function createPaymentSession(array $params): array;

    /** Verifikasi signature (untuk gateway yang kirim header/tanda tangan) */
    public function verifySignature(string $rawBody, string $signature, string $timestamp): bool;

    /** Ekstraksi id event dari payload webhook (opsional, untuk idempotensi) */
    public function extractEventId(array $payload): string;

    /** Ekstraksi order_ref/merchantOrderId dari payload webhook */
    public function extractOrderRef(array $payload): string;

    /** Normalisasi status pembarayan (paid/failed/…) dari payload webhook */
    public function extractStatus(array $payload): string;
}
