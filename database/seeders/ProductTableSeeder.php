<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Product::count()) { $this->addProducts(); }
    }


    public function addProducts(){
        $pdescrs = [
            ['id' => 1, 'product' => 'Профлист С21', 'actual' => true],
            ['id' => 4, 'product' => 'Профлист С8', 'actual' => true],
            ['id' => 2, 'product' => 'Гладкий лист', 'actual' => true],
            ['id' => 5, 'product' => 'Металлочерепица', 'actual' => true],
            ['id' => 10,'product' => 'Добор', 'actual' => true],
        ];
        foreach ($pdescrs as $pdesc) {
            $prd = Product::create($pdesc);
        }
    }
}
