<?php

namespace App\Controllers;

use App\Models\ProfilModel;
use CodeIgniter\Controller;

class Profil extends Controller
{
    protected $profilModel;
    protected $session;

    public function __construct()
    {
        $this->profilModel = new ProfilModel();
        $this->session = session();
    }

    public function index()
    {
		$session = \Config\Services::session();
		
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data['data'] = $this->profilModel->data($session->get('user_id'));
		
        return view('profil/edit', $data);
    }
	
	public function update($id)
    {
        $values = array(
			'name' => $this->request->getPost('name'),
			'email' => $this->request->getPost('email')
		);
		
		if(!empty($this->request->getPost('password')))
		{
			$values['password'] = md5($this->request->getPost('password'));
		}

        $this->profilModel->update($id, $values);

        return redirect()->to('/profil')->with('success', 'Profil berhasil diperbarui');
    }
}
