<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            [
                'productid' => 1,
                'min_qty' => 10,
                'max_qty' => 20,
                'price' => 250,
            ],
            [
                'productid' => 1,
                'min_qty' => 30,
                'max_qty' => 40,
                'price' => 350,
            ],
            [
                'productid' => 1,
                'min_qty' => 50,
                'max_qty' => 60,
                'price' => 450,
            ],
            [
                'productid' => 2,
                'min_qty' => 150,
                'max_qty' => 300,
                'price' => 15000,
            ],
        ];

        DB::table('product_prices')->insert($data);
    }
}
