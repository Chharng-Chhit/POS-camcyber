<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        |-------------------------------------------------------------------------------
        | Add Product Type First because relationship from products_type to products 1:M
        |-------------------------------------------------------------------------------
        */
        DB::table('products_type')->insert(
            [
                ['name' => 'Snack'],
                ['name' => 'Food-Meat'],
                ['name' => 'Beverage'],
                ['name' => 'Beauty']
            ]
        );

        /*
        |-------------------------------------------------------------------------------
        | Add 20 Products
        |-------------------------------------------------------------------------------
        */
        DB::table('product')->insert(
            [
                [
                    'code' => 'B001',
                    'type_id' => '4',
                    'name' => 'Boscia',
                    'unit_price' => 10000,
                    'image' => 'static/Products/Beauty/boscia.jpg',
                    'created_at' => now(),
                    'updated_at' => now()

                ],
                [
                    'code' => 'B002',
                    'type_id' => '4',
                    'name' => 'Deep Cleaning Oil',
                    'unit_price' => 15000,
                    'image' => 'static/Products/Beauty/deep-cleansing-oil.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'B003',
                    'type_id' => '4',
                    'name' => 'LipGloss',
                    'unit_price' => 25000,
                    'image' => 'static/Products/Beauty/lipgloss.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'B004',
                    'type_id' => '4',
                    'name' => 'Dark Pot',
                    'unit_price' => 18000,
                    'image' => 'static/Products/Beauty/dark-spot.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'B005',
                    'type_id' => '4',
                    'name' => 'Mauli',
                    'unit_price' => 23000,
                    'image' => 'static/Products/Beauty/mauli.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S001',
                    'type_id' => '1',
                    'name' => 'Schoko',
                    'unit_price' => 3000,
                    'image' => 'static/Products/Snack/milch-schoko.png',
                    'created_at' => now(),
                    'updated_at' => now()

                ],
                [
                    'code' => 'S002',
                    'type_id' => '1',
                    'name' => 'Cheez-it',
                    'unit_price' => 5000,
                    'image' => 'static/Products/Snack/cheezit.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S003',
                    'type_id' => '1',
                    'name' => 'Chikki',
                    'unit_price' => 2000,
                    'image' => 'static/Products/Snack/chikki.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S004',
                    'type_id' => '1',
                    'name' => 'Snickers',
                    'unit_price' => 4000,
                    'image' => 'static/Products/Snack/snickers.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S005',
                    'type_id' => '1',
                    'name' => 'Protein',
                    'unit_price' => 5000,
                    'image' => 'static/Products/Snack/protein-snack.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'M001',
                    'type_id' => '2',
                    'name' => 'Crab',
                    'unit_price' => 10000,
                    'image' => 'static/Products/Food-Meat/crab.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'M002',
                    'type_id' => '2',
                    'name' => 'Jerky Beef',
                    'unit_price' => 15000,
                    'image' => 'static/Products/Food-Meat/jerky-beef.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'M003',
                    'type_id' => '2',
                    'name' => 'Clam',
                    'unit_price' => 25000,
                    'image' => 'static/Products/Food-Meat/clam.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'M004',
                    'type_id' => '2',
                    'name' => 'Chicken meat',
                    'unit_price' => 15000,
                    'image' => 'static/Products/Food-Meat/chicken-meat.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'M005',
                    'type_id' => '2',
                    'name' => 'Beef',
                    'unit_price' => 23000,
                    'image' => 'static/Products/Food-Meat/beef.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'Be001',
                    'type_id' => '3',
                    'name' => 'G',
                    'unit_price' => 8000,
                    'image' => 'static/Products/Beverage/g.jpg',
                    'created_at' => now(),
                    'updated_at' => now()

                ],
                [
                    'code' => 'Be002',
                    'type_id' => '3',
                    'name' => 'IZE',
                    'unit_price' => 1500,
                    'image' => 'static/Products/Beverage/ize.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'Be003',
                    'type_id' => '3',
                    'name' => 'Creamsoda',
                    'unit_price' => 12000,
                    'image' => 'static/Products/Beverage/creamsoda.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'Be004',
                    'type_id' => '3',
                    'name' => 'EGB',
                    'unit_price' => 6000,
                    'image' => 'static/Products/Beverage/egb.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'Be005',
                    'type_id' => '3',
                    'name' => 'Heineken',
                    'unit_price' => 8000,
                    'image' => 'static/Products/Beverage/heineken.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]
        );
    }
}
