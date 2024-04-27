<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Item::factory()->count(5)->create();
        
        Item::factory()->create([
            'name' => 'Ручка',
            'img' => '',
            'vendor_code' => 23,
            'price' => 39,
            'unit' => 'шт.'
        ]);
        
        Item::factory()->create([
            'name' => 'Замок',
            'img' => '',
            'vendor_code' => 24,
            'price' => 39,
            'unit' => 'шт.'
        ]);
        
        Item::factory()->create([
            'name' => 'Магнит',
            'img' => '',
            'vendor_code' => 25,
            'price' => 39,
            'unit' => 'шт.'
        ]);
    }
}
