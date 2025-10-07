<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CodeModel;
use App\Models\PaymentEventModel;
use CodeIgniter\HTTP\ResponseInterface;

class Webhook extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Endpoint webhook utama.
     * Saat ini fokus ke callback Duitku (POP).
     * Format callback (application/x-www-form-urlencoded) minimal:
     *  - merchantCode
     *  - amount
     *  - merchantOrderId
     *  - resultCode ("00" sukses)
     *  - signature = md5(merchantCode + amount + merchantOrderId + apiKey)
     *  - reference (opsional)
     */
    public function payment()
    {
        // Simpan raw body untuk debug/log
        $raw = $this->request->getBody();
        $post = $this->request->getPost();

        // Deteksi callback Duitku
        if ($this->isDuitkuCallback($post)) {
            return $this->handleDuitkuCallback($post, $raw);
        }

        // Tambahkan handler gateway lain di sini bila diperlukan...
        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
            ->setBody('Unknown or unsupported webhook payload');
    }

    // =========================
    // Handler khusus: DUITKU
    // =========================
    protected function isDuitkuCallback(array $post): bool
    {
        return isset($post['merchantCode'], $post['merchantOrderId'], $post['signature']);
    }

    protected function handleDuitkuCallback(array $post, string $raw)
    {
        $merchantCode = getenv('DUITKU_MERCHANT_CODE') ?: '';
        $apiKey       = getenv('DUITKU_API_KEY') ?: '';

        if ($merchantCode === '' || $apiKey === '') {
            log_message('error', 'Webhook Duitku: env not set (merchant or apiKey empty)');
            return $this->jsonError('Server configuration error', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Field penting dari callback
        $amount          = (string) ($post['amount'] ?? '');
        $orderRef        = (string) ($post['merchantOrderId'] ?? '');
        $resultCode      = (string) ($post['resultCode'] ?? '');
        $reference       = (string) ($post['reference'] ?? '');
        $signatureSent   = (string) ($post['signature'] ?? '');

        // Verifikasi signature (MD5)
        // Catatan: amount harus sama persis seperti yang dikirim Duitku (string).
        $signatureCalc = md5($merchantCode . $amount . $orderRef . $apiKey);
        if (strtolower($signatureSent) !== strtolower($signatureCalc)) {
            log_message('error', sprintf('Webhook Duitku: bad signature (order=%s)', $orderRef));
            return $this->response->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        // Siapkan payload untuk disimpan di payment_events
        $payload = [
            'reference'       => $reference ?: null,
            'merchantOrderId' => $orderRef,
            'amount'          => $amount,
            'resultCode'      => $resultCode,
            'raw'             => $post, // simpan full post untuk audit
        ];

        // Idempotensi event_id: pakai reference jika ada, fallback gabungan
        $eventId = $reference !== ''
            ? $reference
            : ('duitku-' . $orderRef . '-' . ($resultCode !== '' ? $resultCode : 'na'));

        $events = new PaymentEventModel();

        // Jika sudah pernah diproses, segera OK (idempotent)
        if ($events->isProcessed($eventId)) {
            return $this->response->setJSON(['ok' => true, 'idempotent' => true]);
        }

        $orders = new OrderModel();
        $codes  = new CodeModel();

        try {
            $this->db->transException(true)->transStart();

            // Ambil order dengan lock
            $order = $orders->findByRef($orderRef, true);
            if (!$order) {
                throw new \RuntimeException('Order not found: ' . $orderRef);
            }

            // Map status Duitku
            $normalized = $this->mapDuitkuStatus($resultCode);

            if ($normalized === 'paid' && $order['status'] !== 'paid') {
                // Assign 1 kode atomik (SELECT ... FOR UPDATE)
                $code = $codes->lockAndAssignFirstAvailable($order['sku'], (int) $order['id']);
                if (!$code) {
                    // Tidak ada stok, tandai gagal agar downstream aware (refund/manual check)
                    $orders->update($order['id'], ['status' => 'failed']);
                    throw new \RuntimeException('No stock available for SKU: ' . $order['sku']);
                }
                // Tandai paid dan rekam code_id
                $orders->update($order['id'], [
                    'status'  => 'paid',
                    'code_id' => $code['id'],
                ]);
            } else {
                // Selain "00", anggap gagal (atau Anda bisa mapping ke expired/cancelled sesuai kebutuhan)
                if ($order['status'] !== 'paid') {
                    $orders->update($order['id'], ['status' => $normalized]);
                }
            }

            // Simpan event idempotensi
            $events->insertEvent($eventId, $orderRef, $payload);

            $this->db->transComplete();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Webhook Duitku error: ' . $e->getMessage());
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->response->setJSON(['ok' => true]);
    }

    protected function mapDuitkuStatus(string $resultCode): string
    {
        // "00" = sukses; lainnya: belum bayar/gagal
        return $resultCode === '00' ? 'paid' : 'failed';
    }

    // =========================
    // Util helpers
    // =========================
    protected function jsonError(string $message, int $http = 400)
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $message])
            ->setStatusCode($http);
    }
}
