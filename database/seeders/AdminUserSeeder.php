<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       \App\Models\User::create([
    'name' => 'Admin Master',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'), // Palitan ito sa mas secure na password
    'role' => 1, // Admin Role
]);
    }
}
