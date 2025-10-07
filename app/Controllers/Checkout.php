<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CodeModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;

date_default_timezone_set('Asia/Jakarta');

class Checkout extends BaseController
{
    public function index()
    {
        // Opsional: bisa dihapus bila tidak dipakai
        return $this->response->setBody('OK');
    }

    /**
     * Menerima POST dari detailProduk.php untuk membuat sesi pembayaran Duitku.
     * - TANPA ONGKIR (grand total = harga × qty)
     * - Total dihitung ulang di server dari DB produk berdasarkan SKU
     * - Mendukung AJAX (JSON) dan non-AJAX (redirect)
     */
	 public function create()
	 {
		$productId = (int) ($this->request->getPost('product_id') ?? 0);
		$email     = trim((string) $this->request->getPost('email'));
		$phone     = trim((string) $this->request->getPost('phone'));
		$qty       = max(1, (int) ($this->request->getPost('qty') ?? 1));
		
		$produkModel = new ProductModel();
		$produk = $produkModel->select('id, nama_produk, harga')->find($productId);
		if (!$produk) return $this->failBadRequest('Produk tidak ditemukan.');

		$unitPrice = (int) $produk['harga'];
		if ($unitPrice < 1) return $this->failBadRequest('Harga produk tidak valid.');

		$amount = $unitPrice * $qty; // TANPA ONGKIR

		// 4) Buat order pending
		$orders   = new \App\Models\OrderModel();
		$orderRef1 = bin2hex(random_bytes(12));

		// SKU turunan hanya untuk pencatatan (tetap isi kolom 'sku' di orders bila model Anda butuh)
		$sku = 'SKU-' . $produk['id'];
		
		$values = array(
			'order_ref' => $orderRef1,
			'product_id'=> $produk['id'],
			'sku'       => 'SKU-' . $produk['id'],  // simpan SKU turunan supaya alur downstream tetap sama
			'name' => $this->request->getPost('billing_name'),
			'address' => $this->request->getPost('billing_address'),
			'city' => $this->request->getPost('billing_city'),
			'state' => $this->request->getPost('billing_state'),
			'postal_code' => $this->request->getPost('billing_zip'),
			'email'     => $email,
			'phone'     => $phone,
			'amount'    => $amount,
			'currency'  => 'IDR',
			'status'    => 'pending',
		);

		$orders->insert($values);

		
		$billing = [
			'firstName'  => $this->request->getPost('billing_name'),
			'lastName'   => '',
			'address'    => $this->request->getPost('billing_address'),
			'city'       => $this->request->getPost('billing_city'),
			'postalCode' => $this->request->getPost('billing_zip'),
			'phone'      => $phone,
			'countryCode'=> 'ID',
		];
		
		$shipping = [
			'firstName'  => $this->request->getPost('shipping_name') ?: $billing['firstName'],
			'lastName'   => '',
			'address'    => $this->request->getPost('shipping_address') ?: $billing['address'],
			'city'       => $this->request->getPost('shipping_city') ?: $billing['city'],
			'postalCode' => $this->request->getPost('shipping_zip') ?: $billing['postalCode'],
			'phone'      => $phone,
			'countryCode'=> 'ID',
		];

		$productName = 'Pembelian ' . ($produk['nama_produk'] ?? ('Produk #'.$produk['id']));
		
		// GATEWAY
		 $gateway = new \App\Services\Gateways\DuitkuGateway();
		 
		 $orderRef = 'SKU-' . bin2hex(random_bytes(4));
		 
		 $session = $gateway->createPaymentSession([
			'order_ref' => $orderRef,
			'amount'    => $amount,  // Rp10.000
			'productDetails' => $productName,
			'customer'       => [
				'firstName' => $billing['firstName'],
				'lastName'  => '',
				'email'     => $email,
				'phone'     => $phone,
			],
			'billing'   => $billing,
			'shipping'  => $shipping,
			'callback'  => 'https://petatani.xyz/checkout/callback',
			'success'   => 'https://petatani.xyz/checkout/success?ref='.$orderRef1,
			'failed'    => 'https://petatani.xyz/checkout/failed',
		]);
		
		$orders->where('order_ref', $orderRef)
	   ->set('gateway_session_id', $session['id'] ?? null)
	   ->update();
		
		return redirect()->to($session['pay_url']);
	 }
	 
	 public function success()
	 {
		 $orders   = new \App\Models\OrderModel();
		 
		$orders->where('order_ref', $this->request->getGet('ref'))
	   ->set('status', 'paid')
	   ->update();
	   
	    return redirect()->to('/produk/detail');
	 }
	 
	 public function failed()
	 {
	    return redirect()->to('/produk/detail');
	 }
	 
	 public function callback()
	 {
	    return redirect()->to('/produk/detail');
	 }
	 
    public function create_ori()
{
    // 1) Ambil input
    $productId = (int) ($this->request->getPost('product_id') ?? 0);
    $email     = trim((string) $this->request->getPost('email'));
    $phone     = trim((string) $this->request->getPost('phone'));
    $qty       = max(1, (int) ($this->request->getPost('qty') ?? 1));

    // 2) Validasi
    $rules = [
        'product_id'      => 'required|is_natural_no_zero',
        'email'           => 'required|valid_email|max_length[190]',
        'phone'           => 'required|min_length[5]|max_length[50]',
        'billing_name'    => 'required|min_length[2]|max_length[190]',
        'billing_address' => 'required|min_length[5]|max_length[500]',
        'billing_city'    => 'required|min_length[2]|max_length[120]',
        'billing_state'   => 'required|min_length[2]|max_length[120]',
        'billing_zip'     => 'required|min_length[3]|max_length[15]',
    ];
    if (!$this->validate($rules)) {
        return $this->failValidation('Validasi gagal', $this->validator->getErrors());
    }

    // 3) Ambil produk berdasarkan ID
    $produkModel = new ProductModel();
    $produk = $produkModel->select('id, nama_produk, harga')->find($productId);
    if (!$produk) return $this->failBadRequest('Produk tidak ditemukan.');

    $unitPrice = (int) $produk['harga'];
    if ($unitPrice < 1) return $this->failBadRequest('Harga produk tidak valid.');

    $amount = $unitPrice * $qty; // TANPA ONGKIR

    // 4) Buat order pending
    $orders   = new \App\Models\OrderModel();
    $orderRef = bin2hex(random_bytes(12));

    // SKU turunan hanya untuk pencatatan (tetap isi kolom 'sku' di orders bila model Anda butuh)
    $sku = 'SKU-' . $produk['id'];

    $orders->insert([
        'order_ref' => $orderRef,
        'product_id'=> $produk['id'],
        'sku'       => 'SKU-' . $produk['id'],  // simpan SKU turunan supaya alur downstream tetap sama
        'email'     => $email,
        'phone'     => $phone,
        'amount'    => $amount,
        'currency'  => 'IDR',
        'status'    => 'pending',
    ]);

    // 5) Siapkan payload untuk Duitku (alamat dll ambil dari POST seperti versi Anda)
    $billing = [
        'firstName'  => $this->request->getPost('billing_name'),
        'lastName'   => '',
        'address'    => $this->request->getPost('billing_address'),
        'city'       => $this->request->getPost('billing_city'),
        'postalCode' => $this->request->getPost('billing_zip'),
        'phone'      => $phone,
        'countryCode'=> 'ID',
    ];
    $shipping = [
        'firstName'  => $this->request->getPost('shipping_name') ?: $billing['firstName'],
        'lastName'   => '',
        'address'    => $this->request->getPost('shipping_address') ?: $billing['address'],
        'city'       => $this->request->getPost('shipping_city') ?: $billing['city'],
        'postalCode' => $this->request->getPost('shipping_zip') ?: $billing['postalCode'],
        'phone'      => $phone,
        'countryCode'=> 'ID',
    ];

    $productName = 'Pembelian ' . ($produk['nama_produk'] ?? ('Produk #'.$produk['id']));

    $params = [
        'order_ref'      => $orderRef,
        'amount'         => $amount,
        'currency'       => 'IDR',
        'customer'       => [
            'firstName' => $billing['firstName'],
            'lastName'  => '',
            'email'     => $email,
            'phone'     => $phone,
        ],
        'billing'        => $billing,
        'shipping'       => $shipping,
        'items'          => [[
            'name'     => $productName,
            'price'    => $amount,
            'quantity' => 1,
        ]],
        'productDetails' => $productName,
        'callback'       => rtrim(getenv('APP_BASE_URL') ?: base_url(), '/') . '/webhook/payment',
        'success'        => rtrim(getenv('APP_BASE_URL') ?: base_url(), '/') . '/checkout/success?order=' . $orderRef,
        'failed'         => rtrim(getenv('APP_BASE_URL') ?: base_url(), '/') . '/checkout/failed?order=' . $orderRef,
    ];

    // 6) Panggil gateway (Duitku)
    $gatewayName = strtolower(getenv('PAYMENT_GATEWAY') ?: 'duitku');
    $gatewayMap = [
        'duitku'  => \App\Services\Gateways\DuitkuGateway::class,
        'generic' => \App\Services\Gateways\GenericGateway::class,
    ];
    $gatewayClass = $gatewayMap[$gatewayName] ?? \App\Services\Gateways\GenericGateway::class;
    $gateway = new $gatewayClass();

    try {
        $session = $gateway->createPaymentSession($params);
        $orders->where('order_ref', $orderRef)
               ->set('gateway_session_id', $session['id'] ?? null)
               ->update();

        if (!$this->isAjax()) {
            return redirect()->to($session['pay_url'] ?? ('/checkout/failed?order=' . $orderRef));
        }
        return $this->response->setJSON([
            'status'       => 'ok',
            'order_ref'    => $orderRef,
            'payment_url'  => $session['pay_url'] ?? null,
            'reference_id' => $session['id'] ?? null,
        ])->setStatusCode(\CodeIgniter\HTTP\ResponseInterface::HTTP_OK);

    } catch (\Throwable $e) {
        log_message('error', 'createPaymentSession error: '.$e->getMessage());
        $orders->where('order_ref', $orderRef)->set('status', 'failed')->update();
        if ($this->isAjax()) {
            return $this->response->setJSON(['status'=>'error','message'=>'Gagal membuat sesi pembayaran.'])
                ->setStatusCode(\CodeIgniter\HTTP\ResponseInterface::HTTP_BAD_GATEWAY);
        }
        return redirect()->to('/checkout/failed?order=' . $orderRef);
    }
}

    private function isAjax(): bool
    {
        return $this->request->isAJAX()
            || strtolower($this->request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest'
            || strpos((string) $this->request->getHeaderLine('Accept'), 'application/json') !== false;
    }

    private function failBadRequest(string $message)
    {
        if ($this->isAjax()) {
            return $this->response->setJSON(['status' => 'error', 'message' => $message])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        return redirect()->back()->with('error', $message);
    }

    private function failValidation(string $message, array $errors = [])
    {
        if ($this->isAjax()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $message,
                'errors'  => $errors,
            ])->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        }
        return redirect()->back()->with('error', $message)->with('errors', $errors)->withInput();
    }
}
