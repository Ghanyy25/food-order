<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 akun Admin
        Category::create([
            'name' => 'Makanan',
            'slug' => 'makanan',
        ]);
        Category::create([
            'name' => 'Minuman',
            'slug' => 'minuman',
        ]);
        Category::create([
            'name' => 'Cemilan',
            'slug' => 'cemilan',
        ]);
        Category::create([
            'name' => 'Dessert',
            'slug' => 'dessert',
        ]);
    }
}
