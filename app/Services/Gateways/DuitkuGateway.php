<?php

namespace App\Services\Gateways;

use App\Services\PaymentGatewayAdapter;

class DuitkuGateway implements PaymentGatewayAdapter
{
    protected string $baseUrl;
    protected string $merchantCode;
    protected string $apiKey;

    public function __construct()
    {
        $env = 'sandbox'; //Set 'prod' or sandbox and set $signature
        $this->baseUrl = ($env === 'prod')
            ? (getenv('DUITKU_BASE_PROD') ?: 'https://api-prod.duitku.com')
            : (getenv('DUITKU_BASE_SANDBOX') ?: 'https://api-sandbox.duitku.com');
			
// 		INI UNTUK ENVIRONMENT SANDBOX
		$this->merchantCode = 'DS25232';
        $this->apiKey       = 'c1ebc753cb08168a4f1acb46b509098d';
		
		// INI UNTUK ENVIRONMENT PRODUCTION
// 		$this->merchantCode = 'D20122';
//         $this->apiKey       = 'f179901a05a83248d91128342718c695';
    }

    /**
     * Membuat sesi pembayaran (invoice) ke Duitku.
     * $params: order_ref, amount (IDR), currency (IDR), customer[email,phone],
     *          callback, success (returnUrl), failed (returnUrl juga), item, addresses
     */
    public function createPaymentSession(array $params): array
    {
        $endpoint = rtrim($this->baseUrl, '/') . '/api/merchant/createInvoice';

        // timestamp Jakarta (ms)
        $timestamp = (int) round(microtime(true) * 1000);
		
// 		INI UNTUK ENVIRONMENT SANDBOX
		$signature = hash('sha256', 'DS25232' . $timestamp . 'c1ebc753cb08168a4f1acb46b509098d');
        
// 		// INI UNTUK ENVIRONMENT PRODUCTION
// 		$signature = hash('sha256', 'D20122' . $timestamp . 'f179901a05a83248d91128342718c695');

        $firstName = $params['customer']['firstName'] ?? 'Customer';
        $lastName  = $params['customer']['lastName'] ?? '';
        $email     = $params['customer']['email'] ?? '';
        $phone     = $params['customer']['phone'] ?? '';

        $billing = $params['billing'] ?? [];
        $shipping= $params['shipping'] ?? $billing;

        $addressBilling = [
            'firstName'  => $billing['firstName']  ?? $firstName,
            'lastName'   => $billing['lastName']   ?? $lastName,
            'address'    => $billing['address']    ?? '',
            'city'       => $billing['city']       ?? '',
            'postalCode' => $billing['postalCode'] ?? '',
            'phone'      => $billing['phone']      ?? $phone,
            'countryCode'=> $billing['countryCode']?? 'ID',
        ];
        $addressShipping = [
            'firstName'  => $shipping['firstName']  ?? $firstName,
            'lastName'   => $shipping['lastName']   ?? $lastName,
            'address'    => $shipping['address']    ?? '',
            'city'       => $shipping['city']       ?? '',
            'postalCode' => $shipping['postalCode'] ?? '',
            'phone'      => $shipping['phone']      ?? $phone,
            'countryCode'=> $shipping['countryCode']?? 'ID',
        ];

        // Item minimal 1 baris
        $itemDetails = $params['items'] ?? [[
            'name'     => $params['productDetails'] ?? 'Order',
            'price'    => (int) $params['amount'],
            'quantity' => 1,
        ]];

        $body = [
            'paymentAmount'   => (int) $params['amount'],
            'merchantOrderId' => $params['order_ref'],
            'productDetails'  => $params['productDetails'] ?? 'Pembayaran',
            'email'           => $email,
            'phoneNumber'     => $phone,
            'itemDetails'     => $itemDetails,
            'customerDetail'  => [
                'firstName'      => $firstName,
                'lastName'       => $lastName,
                'email'          => $email,
                'phoneNumber'    => $phone,
                'billingAddress' => $addressBilling,
                'shippingAddress'=> $addressShipping,
            ],
            'callbackUrl'     => $params['callback'],
            'returnUrl'       => $params['success'], // Duitku akan redirect ke sini
            // 'paymentMethod' => 'I1', // optional: direct ke metode tertentu (lihat tabel kode)
            // 'expiryPeriod'  => 10,   // menit
        ];

        $ch = curl_init();
        $payload = json_encode($body, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        curl_setopt_array($ch, [
            CURLOPT_URL            => $endpoint,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'x-duitku-signature: ' . $signature,
                'x-duitku-timestamp: ' . $timestamp,
                'x-duitku-merchantcode: ' . $this->merchantCode,
            ],
            CURLOPT_SSL_VERIFYPEER => false, // set true di produksi jika CA sudah benar
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $resp = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http === 200) {
            $json = json_decode($resp, true);
            // response: paymentUrl, reference, statusCode == "00"
            return [
                'id'       => $json['reference'] ?? null,
                'pay_url'  => $json['paymentUrl'] ?? null,
                'raw'      => $json,
            ];
        }

        throw new \RuntimeException("Duitku createInvoice gagal (HTTP $http): $resp");
    }

    /**
     * Verifikasi signature WEBHOOK/CALLBACK dari Duitku.
     * Callback menaruh signature di body (POST form):
     * md5(merchantCode + amount + merchantOrderId + apiKey)
     */
    public function verifySignature(string $rawBody, string $signature, string $timestamp): bool
    {
        // Pada callback POP, signature tidak berasal dari header tetapi dari $_POST['signature'].
        // Method ini dibiarkan ada demi kompatibilitas interface; verifikasi dilakukan di controller.
        return true;
    }

    public function extractEventId(array $payload): string
    {
        // Gunakan reference dari Duitku jika ada
        return $payload['reference'] ?? ($payload['merchantOrderId'] ?? '');
    }

    public function extractOrderRef(array $payload): string
    {
        return $payload['merchantOrderId'] ?? '';
    }

    public function extractStatus(array $payload): string
    {
        // resultCode: "00" = sukses; "01"/"02" = belum bayar/gagal
        $rc = $payload['resultCode'] ?? '';
        return $rc === '00' ? 'paid' : 'failed';
    }
}
