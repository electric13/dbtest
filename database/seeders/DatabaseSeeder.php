<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MaterialsTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(OrderLineTableSeeder::class);
        $this->call(ItemGroupsSeeder::class);
        $this->call(ItemSeeder::class);
        //\App\Models\Contact::factory(10)->create();
        //\\App\Models\Material::factory(1)->create();
        //\App\Models\Material::factory(1)->create();
        // \App\Models\User::factory(10)->create();
    }
}
