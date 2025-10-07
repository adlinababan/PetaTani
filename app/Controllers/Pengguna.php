<?php

namespace App\Controllers;

use App\Models\PenggunaModel;
use CodeIgniter\Controller;

class Pengguna extends Controller
{
    protected $penggunaModel;
    protected $session;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
        $this->session = session();
    }

    // Tampilkan semua pengguna
    public function index()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data['pengguna'] = $this->penggunaModel->data();
		
        return view('pengguna/index', $data);
    }

    // Tampilkan form tambah
    public function create()
    {
		$data['list_group'] = $this->penggunaModel->list_group();
		
        return view('pengguna/create', $data);
    }

    // Simpan pengguna
    public function store()
    {
        $nama = $this->request->getPost('name');
        $email = $this->request->getPost('email');
		$group = $this->request->getPost('kode_group');

        $this->penggunaModel->save([
            'name' => $nama,
            'email' => $email,
			'kode_group' => $group,
			'password' => md5('123')
        ]);

        return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil ditambahkan');
    }

    // Form edit pengguna
    public function edit($id)
    {
        $data['pengguna'] = $this->penggunaModel->find($id);
		$data['list_group'] = $this->penggunaModel->list_group();
		
        return view('pengguna/edit', $data);
    }

    // Update pengguna
    public function update($id)
    {
        $nama = $this->request->getPost('name');
        $email = $this->request->getPost('email');
		$group = $this->request->getPost('kode_group');

        $this->penggunaModel->update($id, [
             'name' => $nama,
            'email' => $email,
			'kode_group' => $group,
        ]);

        return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil diperbarui');
    }

    // Hapus pengguna
    public function delete($id)
    {
        $this->penggunaModel->delete($id);
        return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil dihapus');
    }
}
