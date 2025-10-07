<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesReportModel extends Model
{
    public function data($srcstart, $srcend, $srcstatus, $srcseller, $userid, $groupcode) 
	{
		$db = db_connect();
		
		$where1 = (!empty($srcstart)) ? " AND SUBSTRING(a.created_at, 1, 10) BETWEEN '".$srcstart."' AND  '".$srcend."' " : "";
		$where2 = (!empty($srcstatus) && $srcstatus !== 'all') ? " AND a.status = '".$srcstatus."'" : "";
		$where3 = (!empty($srcseller)) ? " AND b.produk_by = '".$srcseller."'" : "";
		
		if($groupcode == 'ADM')
		{
			$query   = $db->query("
				SELECT 
					a.*,
					b.nama_produk,
					b.harga,
					b.satuan,
					c.name AS nama_penjual
				FROM orders a
				JOIN produk b ON b.id = a.product_id
				JOIN users c ON c.id = b.produk_by
				WHERE a.id <> ''
				$where1
				$where2
				$where3
				ORDER BY a.id ASC
			");
		}
		else
		{
			$query   = $db->query("
				SELECT 
					a.*,
					b.nama_produk,
					b.harga,
					b.satuan,
					c.name AS nama_penjual
				FROM orders a
				JOIN produk b ON b.id = a.product_id
				JOIN users c ON c.id = b.produk_by
				WHERE a.id <> ''
				AND b.produk_by = '".$userid."'
				$where1
				$where2
				$where3
				ORDER BY a.id ASC
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
