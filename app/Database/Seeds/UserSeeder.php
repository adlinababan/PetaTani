<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run() 
        { 
        $data = [ 
        'name'     => 'Admin', 
        'email'    => 'admin@admin.com', 
        'password' => password_hash('admin123', PASSWORD_DEFAULT), 
        ]; 
        // Simpan ke tabel 'users' 
        $this->db->table('users')->insert($data);  
} 
} 
