<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
	
	protected $allowedFields    = [
        'name',
        'email',
        'kode_group',
		'password'
    ];
	
	public function data($userid)
	{
		$db = db_connect();
		
		$query   = $db->query("
			SELECT *
			FROM users
			WHERE id = '".$userid."'
		");
		
		return $query->getResult();
	}
}
