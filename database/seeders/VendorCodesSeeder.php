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
        // VendorCode::factory()->count(14)->create();
        VendorCode::factory()->create([
            'vendor_code' => 1,
            'name' => 'Профиль опорный - нижний на 3 стекла',
            'img' => 'https://random.imagecdn.app/300/150',
            'price' => 800,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 2,
            'name' => 'Профиль опорный - верхний на 3 стекла',
            'img' => 'https://random.imagecdn.app/300/150',
            'price' => 900,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 3,
            'name' => 'Профиль опорный - нижний на 2 стекла',
            'img' => 'https://random.imagecdn.app/300/150',
            'price' => 700,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 4,
            'name' => 'Профиль опорный - верхний на 2 стекла',
            'img' => 'https://random.imagecdn.app/300/150',
            'price' => 800,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 5,
            'name' => 'Профиль створочный',
            'img' => 'https://random.imagecdn.app/300/150',
            'price' => 700,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 6,
            'name' => 'Профиль пристенный',
            'img' => 'https://random.imagecdn.app/300/150',
            'price' => 300,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 8,
            'name' => 'Алюминиевый межстворочный профиль',
            'img' => 'https://llymar.ru/assets/L8.jpg',
            'price' => 90,
            'unit' => 'шт.',
            'type' => 'aluminium'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 9,
            'name' => 'Алюминиевый стекольно-торцевой профиль',
            'img' => 'https://llymar.ru/assets/L9.jpg',
            'price' => 90,
            'unit' => 'шт.',
            'type' => 'aluminium'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 10,
            'name' => 'Поликарбонатный межстворочный профиль',
            'img' => 'https://random.imagecdn.app/301/148',
            'price' => 90,
            'unit' => 'шт.',
            'type' => 'polycarbonate'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 11,
            'name' => 'Поликарбонатный стекольно-торцевой профиль',
            'img' => 'https://random.imagecdn.app/301/148',
            'price' => 90,
            'unit' => 'шт.',
            'type' => 'polycarbonate'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 12,
            'name' => 'Фетр щеточный 7*6',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 25,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 13,
            'name' => 'Фетр щеточный 4*8',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 35,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 14,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 15,
            'name' => 'Заглушка П1',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 16,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 17,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 18,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 19,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 20,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 21,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 22,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 39,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 26,
            'name' => 'Фетр щеточный 7*10',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 123,
            'unit' => 'м.п.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 2000,
            'name' => 'Глухое остекление',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 7700,
            'unit' => 'шт.'
        ]);
        VendorCode::factory()->create([
            'vendor_code' => 2001,
            'name' => 'Треугольник',
            'img' => 'https://random.imagecdn.app/299/149',
            'price' => 7700,
            'unit' => 'шт.'
        ]);
    }
}
