<?php

use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
           'name' => 'ROLE_SUPERADMIN',
           'description' => 'This is super user role'
        ]);
    }
}
