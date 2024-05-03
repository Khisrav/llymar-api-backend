<?php

namespace Database\Seeders;

use App\Models\VendorCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VendorCode::factory()->count(14)->create();
        // VendorCode::factory()->create([
        //     'vendor_code' => 1,
        //     'name' => 'Профиль опорный - нижний на 3 стекла',
        //     'img' => 'https://random.imagecdn.app/300/150',
        //     'price' => 800,
        //     'unit' => 'м.п.'
        // ]);
        // VendorCode::factory()->create([
        //     'vendor_code' => 2,
        //     'name' => 'Профиль опорный - нижний на 3 стекла',
        //     'img' => 'https://random.imagecdn.app/300/150',
        //     'price' => 800,
        //     'unit' => 'м.п.'
        // ]);
    }
}
