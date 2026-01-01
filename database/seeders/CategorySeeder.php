<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insere as categorias padrão no banco de dados
        DB::table('categories')->insert([
            ['name' => 'Cocktails'],
            ['name' => 'Wines / Vini'],
            ['name' => 'Beers / Birre'],
            ['name' => 'Spirits / Distillati'],
            ['name' => 'Snacks / Aperitivi'],
            ['name' => 'Soft Drinks'],
        ]);
    }
}
