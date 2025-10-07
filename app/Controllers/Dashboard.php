<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\KategoriModel;

class Dashboard extends BaseController
{
    public function index()
    {
		$session = \Config\Services::session();
		
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $produkModel   = new ProductModel();
        $kategoriModel = new KategoriModel();
        $kategoriModel = new KategoriModel();
        $totalKategori = $kategoriModel->countAll();
	
        $totalProduk   = $produkModel->countAll();
        $totalKategori = $kategoriModel->countAll();
		
		return view('dashboard/index', [
			'info'   => $produkModel->info($session->get('user_id'), $session->get('group_code'))
		]);
    }
}
