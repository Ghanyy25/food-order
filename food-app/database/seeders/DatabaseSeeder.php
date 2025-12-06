<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 akun Admin
        User::create([
            'name' => 'Makanan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),

        ]);
    }
}
