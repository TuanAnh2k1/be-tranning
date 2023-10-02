<?php

namespace Mume\Core\database\seeders;

use Illuminate\Database\Seeder;
use Mume\Core\Dao\SDB;
use Mume\Core\Models\Role;
use Mume\Core\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SDB::table('roles')->insert([
            [
                'id'              => Role::ADMIN_ROLE,
                'name'            => 'Administrator',
                'description'     => 'Administrator',
                'created_by_id'   => User::ADMIN_ID,
                'created_at'      => now(),
            ],
            [
                'id'              => Role::MANAGER_ROLE,
                'name'            => 'Manager',
                'description'     => 'Manager',
                'created_by_id'   => User::ADMIN_ID,
                'created_at'      => now(),
            ],
            [
                'id'              => Role::STAFF_ROLE,
                'name'            => 'Staff',
                'description'     => 'Staff',
                'created_by_id'   => User::ADMIN_ID,
                'created_at'      => now(),
            ],
            [
                'id'              => Role::GUESS_ROLE,
                'name'            => 'Guess',
                'description'     => 'Guess',
                'created_by_id'   => User::ADMIN_ID,
                'created_at'      => now(),
            ],
        ]);
    }
}
