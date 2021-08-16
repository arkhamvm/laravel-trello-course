<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 24; $i++){
            DB::table('cards')->insert([
                'desk_list_id' => rand(1, 6),
                'name' => 'Card ' . $i,
            ]);
        }
    }
}
