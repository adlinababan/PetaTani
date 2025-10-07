<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'nama_kategori',
        'slug',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    // Opsional: Validasi
    protected $validationRules = [
        'nama_kategori' => 'required|min_length[3]',
        'slug' => 'required|alpha_dash|is_unique[kategori.slug,id,{id}]',
    ];

    public function getKategori($slug = false)
    {
        if ($slug === false) {
            return $this->orderBy('nama_kategori', 'ASC')->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }
	
	public function list_seller()
	{
		$db = db_connect();
		
		$query   = $db->query("
			SELECT *
			FROM users
			WHERE group_code = 'SLR'
			ORDER BY name ASC
		");
		
		return $query->getResult();
	}
}
