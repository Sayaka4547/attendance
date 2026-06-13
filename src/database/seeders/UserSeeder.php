<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一般ユーザー1
        User::create([
            'name'              => 'ユーザー1',
            'email'             => 'user1@example.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'role'              => 'user',
        ]);

        // 一般ユーザー2
        User::create([
            'name'              => 'ユーザー2',
            'email'             => 'user2@example.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'role'              => 'user',
        ]);

        // 管理者
        User::create([
            'name'              => 'ユーザー3',
            'email'             => 'user3@example.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'role'              => 'admin',
        ]);
    }
}
