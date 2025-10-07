<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\KategoriModel;
use CodeIgniter\Controller;

class Produk extends Controller
{
    protected $produkModel;
    protected $kategoriModel;
    protected $session;

    public function __construct()
    {
        $this->produkModel = new ProductModel();
        $this->kategoriModel = new KategoriModel();
        $this->session = session();
    }

    // Tampilkan daftar produk
    public function index()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $srcseller = $this->request->getPost('srcseller');

        $data = [
            'produk'       => $this->produkModel->withKategori(
                                $this->session->get('user_id'),
                                $this->session->get('group_code'),
                                $srcseller
                             )->findAll(),
            'totalProduk'  => $this->produkModel->countAll(),
            'list_seller'  => $this->produkModel->list_seller(),
            'srcseller'    => $srcseller
        ];

        return view('produk/index', $data);
    }

    // Form tambah produk
    public function create()
    {
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('produk/create', $data);
    }

    // Simpan produk baru
    public function store()
    {
        $in_date  = $this->request->getPost('in_date');
        $exp_date = $this->request->getPost('exp_date');

        // Validasi tanggal
        if (strtotime($exp_date) < strtotime($in_date)) {
            return redirect()->back()->withInput()->with('errors', ['Tanggal Expired tidak boleh lebih awal dari Tanggal Masuk.']);
        }

        $file = $this->request->getFile('gambar');
        $newName = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/produk', $newName);
        }

        $slug = url_title($this->request->getPost('nama_produk'), '-', true);

        if (!$this->produkModel->save([
            'nama_produk' => $this->request->getPost('nama_produk'),
            'slug'        => $slug,
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'satuan'      => $this->request->getPost('satuan'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'gambar'      => $newName,
            'produk_by'   => $this->request->getPost('produk_by'),
            'link_wa'     => $this->request->getPost('link_wa'),
            'in_date'     => $in_date,
            'exp_date'    => $exp_date
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->produkModel->errors());
        }

        return redirect()->to('/produk')->with('success', 'Produk berhasil ditambahkan');
    }

    // Form edit
    public function edit($id)
    {
        $data['produk']   = $this->produkModel->find($id);
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('produk/edit', $data);
    }

    // Update data produk
    public function update($id)
    {
        $in_date  = $this->request->getPost('in_date');
        $exp_date = $this->request->getPost('exp_date');

        if (strtotime($exp_date) < strtotime($in_date)) {
            return redirect()->back()->withInput()->with('errors', ['Tanggal Expired tidak boleh lebih awal dari Tanggal Masuk.']);
        }

        $produk = $this->produkModel->find($id);
        $file = $this->request->getFile('gambar');

        $newName = $produk['gambar'];
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/produk', $newName);
            if ($produk['gambar']) {
                @unlink('uploads/produk/' . $produk['gambar']);
            }
        }

        $slug = url_title($this->request->getPost('nama_produk'), '-', true);

        if (!$this->produkModel->update($id, [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'slug'        => $slug,
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'satuan'      => $this->request->getPost('satuan'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'gambar'      => $newName,
            'link_wa'     => $this->request->getPost('link_wa'),
            'in_date'     => $in_date,
            'exp_date'    => $exp_date
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->produkModel->errors());
        }

        return redirect()->to('/produk')->with('success', 'Produk berhasil diperbarui');
    }

    // Hapus produk
    public function delete($id)
    {
        $produk = $this->produkModel->find($id);
        if ($produk && $produk['gambar']) {
            @unlink('uploads/produk/' . $produk['gambar']);
        }

        $this->produkModel->delete($id);
        return redirect()->to('/produk')->with('success', 'Produk berhasil dihapus');
    }

    // Halaman detail katalog
    public function detail()
    {
        $query       = $this->request->getGet('q');
        $kategoriId  = $this->request->getGet('kategori');
        $seller      = $this->request->getGet('seller');

        $produkQuery = $this->produkModel->withKategori();

        if ($query) {
            $produkQuery->like('produk.nama_produk', $query);
        }

        if ($kategoriId) {
            $produkQuery->where('produk.kategori_id', $kategoriId);
        }

        if ($seller) {
            $produkQuery->where('produk.produk_by', $seller);
        }

        // Tampilkan hanya produk yang belum expired dan stok > 0
        $produkQuery->where('produk.exp_date >=', date('Y-m-d'));
        $produkQuery->where('produk.stok >', 0);

        $data = [
            'keyword'           => $query,
            'selected_kategori' => $kategoriId,
            'selected_seller'   => $seller,
            'produk'            => $produkQuery->findAll(),
            'kategori'          => $this->kategoriModel->findAll(),
            'list_seller'       => $this->produkModel->list_seller(),
        ];

        return view('produk/detailProduk', $data);
    }
}
