<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'satuan',
        'kategori_id',
        'gambar',
        'link_wa',
        'in_date',
        'exp_date', 
        'created_at',
        'updated_at',
		'produk_by'
    ];

    protected $useTimestamps = true;

    // Opsional: validasi data
    protected $validationRules = [
    'nama_produk' => 'required|min_length[3]',
    'harga'       => 'required|numeric',
    'stok'        => 'required|integer',
    'kategori_id' => 'required|integer',
    'in_date'     => 'required|valid_date[Y-m-d]',
    'exp_date'    => 'required|valid_date[Y-m-d]'
];

    public function getProduk($slug = false)
    {
        if ($slug === false) {
            return $this->orderBy('created_at', 'DESC')->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }

    public function withKategori($userid = NULL, $groupcode = NULL, $srcseller = NULL)
    {
		$where = (!empty($srcseller)) ? " AND produk_by = '".$srcseller."'" : "";
		
		if(!empty($userid) && !empty($groupcode))
		{
			if($groupcode == 'ADM')
			{
				return $this->select('produk.*, kategori.nama_kategori, users.name AS nama_penjual')
				->join('kategori', 'kategori.id = produk.kategori_id')
				->join('users', "users.id = produk.produk_by $where");
			}
			else
			{
				return $this->select('produk.*, kategori.nama_kategori, users.name AS nama_penjual')
				->join('kategori', 'kategori.id = produk.kategori_id')
				->join('users', "users.id = produk.produk_by AND produk.produk_by = '".$userid."' $where");
			}
		}
		else
		{
			return $this->select('produk.*, kategori.nama_kategori, users.name AS nama_penjual')
			->join('kategori', 'kategori.id = produk.kategori_id')
			->join('users', "users.id = produk.produk_by");
		}
    }
	
	public function info($userid, $groupcode)
	{
		$db = db_connect();
		
		if($groupcode == 'ADM')
		{
			$query   = $db->query("
				SELECT *
				FROM
				(
					(
						SELECT COUNT(*) AS info1
						FROM produk
					) AS info1,
					(
						SELECT COUNT(*) AS info2
						FROM kategori
					) AS info2,
					(
						SELECT COALESCE(SUM(amount), 0) AS info3
						FROM orders a
						JOIN produk b ON b.id = a.product_id
						WHERE b.produk_by <> ''
						AND a.status = 'paid'
					) AS info3,
					(
						SELECT COALESCE(SUM(amount), 0) AS info4
						FROM orders a
						JOIN produk b ON b.id = a.product_id
						WHERE b.produk_by <> ''
						AND a.status <> 'paid'
					) AS info4
				)
			");
		}
		else
		{
			$query   = $db->query("
				SELECT *
				FROM
				(
					(
						SELECT COUNT(*) AS info1
						FROM produk
						WHERE produk_by = '".$userid."'
					) AS info1,
					(
						SELECT COUNT(*) AS info2
						FROM kategori
					) AS info2,
					(
						SELECT COALESCE(SUM(amount), 0) AS info3
						FROM orders a
						JOIN produk b ON b.id = a.product_id
						WHERE b.produk_by = '".$userid."'
						AND a.status = 'paid'
					) AS info3,
					(
						SELECT COALESCE(SUM(amount), 0) AS info4
						FROM orders a
						JOIN produk b ON b.id = a.product_id
						WHERE b.produk_by <> ''
						AND a.status <> 'paid'
						AND b.produk_by = '".$userid."'
					) AS info4
				)
			");
		}
		
		return $query->getResult();
	}
	
	public function list_seller()
	{
		$db = db_connect();
		
		$query   = $db->query("
			SELECT *
			FROM users
			WHERE kode_group = 'SLR'
			ORDER BY name ASC
		");
		
		return $query->getResult();
	}
}
