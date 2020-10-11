<?php

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
        //
        $data = [
            [
                'name' => 'Baju Renang',
                'code' => 'BRG001',
                'weight' => 500,
                'description' => 'Baju Renang nyaman',
            ],
            [
                'name' => 'Celana Renang',
                'code' => 'BRG002',
                'weight' => 500,
                'description' => 'Celana Renang nyaman',
            ],
            [
                'name' => 'Yonex - Badminton Racket',
                'code' => 'BRG003',
                'weight' => 800,
                'description' => 'Kualitas oke',
            ],
        ];

        DB::table('products')->insert($data);
    }
}
