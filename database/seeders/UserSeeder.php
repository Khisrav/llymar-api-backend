<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()->count(5)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'company' => 'Gazprom',
            'phone' => '79876543210',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Глозштейн Юрий Манукянц',
            'email' => 'test@llymar.ru',
            'company' => 'Gazprom',
            'phone' => '79876543211',
            'password' => bcrypt('password'),
        ]);
    }
}
