<?php

namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function doLogin()
    {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // $user = $model->where('email', $email)->first();
		
		$db = db_connect();
		
		$user   = $db->query("
			SELECT 
				a.*,
				b.kode_group,
				b.nama_group
			FROM users a
			JOIN groups b ON b.kode_group = a.kode_group
			WHERE a.email = '".$email."'
			AND a.password = '".md5($password)."'
		");
		
		// return $query->getResult();

        if ($user->getNumRows() > 0) 
		{
			$session->set([
				'user_id' => $user->getRowArray()['id'],
				'user_name' => $user->getRowArray()['name'],
				'email' => $user->getRowArray()['email'],
				'group_code' => $user->getRowArray()['kode_group'],
				'logged_in' => true
			]);
			
			return redirect()->to('/dashboard');
        } 
		else 
		{
            return redirect()->back()->with('error', 'Nama Pengguna dan sandi tidak valid');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
    
}
