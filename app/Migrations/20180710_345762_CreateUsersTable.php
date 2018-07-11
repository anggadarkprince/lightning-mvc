<?php

namespace App\Migrations;

use Core\Database\Builder;
use Core\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Builder::create('users', [
            'id' => ['type' => 'int', 'length' => 11, 'primary' => true, 'ai' => true],
            'name' => ['type' => 'int', 'length' => 11, 'null' => false],
            'username' => ['type' => 'varchar', 'length' => 50, 'unique' => true, 'null' => false],
            'password' => ['type' => 'varchar', 'length' => 50, 'null' => false],
            'is_deleted' => ['type' => 'boolean', 'default' => false],
            'created_at' => ['type' => 'timestamp', 'null' => false],
            'last_update' => ['type' => 'timestamp'],
        ]);
    }

    public function down()
    {
        Builder::drop('users');
    }

}