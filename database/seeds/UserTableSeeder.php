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
        DB::table('users')->delete();
        DB::table('users')->insert([
            'name'     => 'Mahmoud Amer',
            'username' => 'guest',
            'email'    => 'mahmoud.amer.m@gmail.com',
            'password' => Hash::make('111111'),
        ]);
    }
}

