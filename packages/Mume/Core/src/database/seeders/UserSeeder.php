<?php

namespace Mume\Core\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mume\Core\Common\CommonConst;
use Mume\Core\Dao\SDB;
use Mume\Core\Models\Role;
use Mume\Core\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SDB::table('users')->insert([
            [
                'username'        => 'admin',
                'email'           => 'admin@admin.com',
                'name'            => 'Administrator',
                'password'        => Hash::make('123456'),
                'remember_token'  => Str::random(10),
                'gender'          => 1,
                'avatar'          => null,
                'is_active'       => CommonConst::IS_ACTIVE,
                'role_id'         => Role::ADMIN_ROLE,
                'created_by_id'   => User::ADMIN_ID,
                'created_at'      => now(),
            ],
        ]);
    }
}
