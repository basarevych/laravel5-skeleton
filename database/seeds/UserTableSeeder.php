<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'      => "Admin",
            'email'     => "admin@example.com",
            'password'  => bcrypt('passwd'),
            'is_active' => true,
            'is_admin'  => true,
        ]);

        for ($i = 0; $i < 100; $i++) {
            DB::table('users')->insert([
                'name'      => "User" . ($i + 1),
                'email'     => "user" . ($i + 1) . "@example.com",
                'password'  => bcrypt('passwd'),
                'is_active' => true,
                'is_admin'  => false,
            ]);
        }
    }
}
