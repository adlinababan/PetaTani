<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DeleteProdukSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('produk')->truncate();
    }
}
