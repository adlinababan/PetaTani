<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use CodeIgniter\Controller;

class Kategori extends Controller
{
    protected $kategoriModel;
    protected $session;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->session = session();
    }

    // Tampilkan semua kategori
    public function index()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data['kategori'] = $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();
        return view('kategori/index', $data);
    }

    // Tampilkan form tambah
    public function create()
    {
        return view('kategori/create');
    }

    // Simpan kategori
    public function store()
    {
        $nama = $this->request->getPost('nama_kategori');
        $slug = url_title($nama, '-', true);

        $this->kategoriModel->save([
            'nama_kategori' => $nama,
            'slug'          => $slug,
        ]);

        return redirect()->to('/kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    // Form edit kategori
    public function edit($id)
    {
        $data['kategori'] = $this->kategoriModel->find($id);
        return view('kategori/edit', $data);
    }

    // Update kategori
    public function update($id)
    {
        $nama = $this->request->getPost('nama_kategori');
        $slug = url_title($nama, '-', true);

        $this->kategoriModel->update($id, [
            'nama_kategori' => $nama,
            'slug'          => $slug,
        ]);

        return redirect()->to('/kategori')->with('success', 'Kategori berhasil diperbarui');
    }

    // Hapus kategori
    public function delete($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->to('/kategori')->with('success', 'Kategori berhasil dihapus');
    }
}
