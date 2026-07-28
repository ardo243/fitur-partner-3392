<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
            
        // 2. Insert Kategori Event
        \App\Models\Category::firstOrCreate(
            ['slug' => 'seminar-it'],
            ['name' => 'Seminar IT']
        );

        \App\Models\Category::firstOrCreate(
            ['slug' => 'entertaiment'],
            ['name' => 'Entertaiment']
        );
    }
}
