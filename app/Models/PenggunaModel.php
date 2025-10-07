<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
	protected $table            = 'users';
    protected $primaryKey       = 'id';
	
	protected $allowedFields    = [
        'name',
        'email',
        'kode_group',
		'password'
    ];
	
    public function data()
	{
		$db = db_connect();
		
		$query   = $db->query("
			SELECT 
				a.*,
				b.nama_group
			FROM users a
			JOIN groups b ON b.kode_group = a.kode_group
			WHERE a.kode_group <> 'ADM'
			ORDER BY a.name ASC
		");
		
		return $query->getResult();
	}
	
	public function list_group()
	{
		$db = db_connect();
		
		$query   = $db->query("
			SELECT *
			FROM groups
			ORDER BY nama_group ASC
		");
		
		return $query->getResult();
	}
}
