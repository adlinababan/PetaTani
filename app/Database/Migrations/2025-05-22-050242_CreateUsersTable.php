<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up() 
{ 
        $this->forge->addField([ 
        'id'       => [ 
            'type'           => 'INT', 
            'auto_increment' => true, 
            'unsigned'       => true, 
        ], 
        'email'    => [ 
            'type'       => 'VARCHAR', 
            'constraint' => 100, 
            'null'       => false, 
            'unique'     => true, 
        ], 
        'password' => [ 
            'type'       => 'VARCHAR', 
            'constraint' => 255, 
            'null'       => false, 
        ], 
        'name'     => [ 
            'type'       => 'VARCHAR', 
            'constraint' => 100, 
            'null'       => false, 
        ], 
        ]); 
        $this->forge->addKey('id', true); // Primary Key 
        $this->forge->createTable('users'); 
} 
public function down() 
{ 
        $this->forge->dropTable('users'); 
        } 
} 