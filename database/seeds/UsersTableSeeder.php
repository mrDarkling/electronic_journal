<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Model\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Model\User::firstOrCreate(
            ['email' => 'admin@local'],
            [
                'username'  => 'admin',
                'password'  => \Illuminate\Support\Facades\Hash::make('admin123456'),
                'email_verified_at' => now(),
                'roles'     => User::ROLE_ADMIN . ',' . User::ROLE_USER,
                'api_token' => Str::random(80),
            ]
        );
    }
}
