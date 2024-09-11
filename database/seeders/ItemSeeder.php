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
        $items = array(
            array('id' => '5','name' => 'Стекло закаленное 10мм М1 (ПРОЗРАЧНОЕ)','img' => '01J49EMK9KW5HX3WSY7ZC972EE.jpg','unit' => 'м2','price' => '5000','vendor_code' => '100','created_at' => '2024-08-02 11:47:10','updated_at' => '2024-08-02 14:53:44','discount' => '20','is_checkable' => '2'),
            array('id' => '6','name' => 'Стекло закаленное 10мм ОСВЕТЛЕННОЕ CRYSTALVISION (ПРОЗРАЧНОЕ)','img' => '01J49EPXGMG5ZZ4A1FKQZEJB6B.jpg','unit' => 'м2','price' => '11000','vendor_code' => '110','created_at' => '2024-08-02 11:48:15','updated_at' => '2024-08-02 11:51:42','discount' => '25','is_checkable' => '2'),
            array('id' => '7','name' => 'Стекло закаленное 10мм СЕРОЕ (Тонированное)','img' => '01J49EYFAE2QGVZQJJTRWJF3P5.jpg','unit' => 'м2','price' => '9000','vendor_code' => '120','created_at' => '2024-08-02 11:52:34','updated_at' => '2024-08-02 11:52:34','discount' => '25','is_checkable' => '2'),
            array('id' => '9','name' => 'Стекло закаленное 10мм СЕРОЕ (Тонированное)			','img' => '01J49F55MZQJJBVRZEWGHGH7TD.jpg','unit' => 'м2','price' => '9500','vendor_code' => '130','created_at' => '2024-08-02 11:56:13','updated_at' => '2024-08-02 11:56:13','discount' => '25','is_checkable' => '2'),
            array('id' => '10','name' => 'Стекло закаленное 10мм Матовое','img' => '01J49MADDY80B28GF0PR426ZYY.jpg','unit' => 'м2','price' => '9500','vendor_code' => '140','created_at' => '2024-08-02 13:26:28','updated_at' => '2024-08-02 13:26:28','discount' => '25','is_checkable' => '2'),
            array('id' => '11','name' => 'Изготовление створок с алюминиевым М/С профилем ','img' => '01J49MPB4WDJNRKDSTVMERJ6HF.png','unit' => 'м2','price' => '1515','vendor_code' => '200','created_at' => '2024-08-02 13:32:59','updated_at' => '2024-08-02 14:36:49','discount' => '1','is_checkable' => '1'),
            array('id' => '12','name' => 'Изготовление створок с М/С профилем из поликарбоната','img' => '01J49N0ASJTHQ92JV78JRY18N5.png','unit' => 'м2','price' => '1212','vendor_code' => '210','created_at' => '2024-08-02 13:38:26','updated_at' => '2024-08-02 13:38:26','discount' => '1','is_checkable' => '1'),
            array('id' => '13','name' => 'Покраска','img' => '01J49N9HW8WJD6HWP10H19YJT3.jpg','unit' => 'створ.','price' => '2500','vendor_code' => '220','created_at' => '2024-08-02 13:43:28','updated_at' => '2024-08-02 13:43:28','discount' => '1','is_checkable' => '1'),
            array('id' => '14','name' => 'Распил','img' => '01J49NFBY2CSSTQ06SEBRW6TFD.png','unit' => 'створ.','price' => '1313','vendor_code' => '230','created_at' => '2024-08-02 13:46:39','updated_at' => '2024-08-02 13:46:39','discount' => '1','is_checkable' => '1'),
            array('id' => '15','name' => 'Монтаж','img' => '01J49NMSRVH3B2PXDE4JYSP7PJ.png','unit' => 'м2','price' => '3000','vendor_code' => '240','created_at' => '2024-08-02 13:49:37','updated_at' => '2024-08-02 14:36:26','discount' => '1','is_checkable' => '1'),
            array('id' => '16','name' => 'Ручка 1000х20х20 МР 900','img' => '01J49P72R6AJNBB89V1FHYYGM9.jpg','unit' => 'шт.','price' => '7000','vendor_code' => '300','created_at' => '2024-08-02 13:59:36','updated_at' => '2024-08-02 13:59:36','discount' => '52','is_checkable' => '0'),
            array('id' => '21','name' => 'Ручка 320х20х20 МР 220','img' => '01J49Q3Y5RCHMA89JQ0EPQSWVX.jpg','unit' => 'шт.','price' => '5400','vendor_code' => '310','created_at' => '2024-08-02 14:15:22','updated_at' => '2024-08-02 14:24:49','discount' => '53','is_checkable' => '0'),
            array('id' => '22','name' => 'Ручка 800*32 МР 625','img' => '01J49QG3M9A1A71D7217PQJD69.jpg','unit' => 'шт.','price' => '4000','vendor_code' => '320','created_at' => '2024-08-02 14:22:00','updated_at' => '2024-08-02 14:22:00','discount' => '53','is_checkable' => '0'),
            array('id' => '23','name' => 'Ручка 1000*32 МР 625			','img' => '01J49QM7DBFJGHHG05DZQ20SKP.jpg','unit' => 'шт.','price' => '4800','vendor_code' => '330','created_at' => '2024-08-02 14:24:15','updated_at' => '2024-08-02 14:24:15','discount' => '53','is_checkable' => '0'),
            array('id' => '24','name' => 'Доставка','img' => '01J5DCENC235K9ZWDPNW0TZWM7.jpg','unit' => 'шт.','price' => '0','vendor_code' => '1234','created_at' => '2024-08-13 06:02:36','updated_at' => '2024-08-16 17:19:40','discount' => '0','is_checkable' => '0')
        );
        
        foreach ($items as $item) {
            Item::create([
                'name' => $item['name'],
                'img' => $item['img'],
                'vendor_code' => $item['vendor_code'],
                'price' => $item['price'],
                'unit' => $item['unit'],
                'discount' => $item['discount'],
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at'],
                'is_checkable' => $item['is_checkable']
            ]);
        }
        
    }
}
