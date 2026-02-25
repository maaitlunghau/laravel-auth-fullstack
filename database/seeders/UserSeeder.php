<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'maaitlunghau',
            'email' => 'trunghau@mstsoftware.vn',
            'password' => 'admin@123',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'role' => 'user',
            'status' => 'pending',
            'email_verified_at' => null,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password',
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => 'password',
            'role' => 'user',
            'status' => 'pending',
            'email_verified_at' => null
        ]);

        User::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'password' => 'password',
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
