<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_kategori' => 'Sayuran',
                'slug'          => 'sayuran',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'nama_kategori' => 'Buah',
                'slug'          => 'buah',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'nama_kategori' => 'Rempah-rempah',
                'slug'          => 'rempah-rempah',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
        ];

        // Insert multiple rows
        $this->db->table('kategori')->insertBatch($data);
    }
}
