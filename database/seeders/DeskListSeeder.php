<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeskListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 2; $i++){
            DB::table('desk_lists')->insert([
                'name' => 'Preparing',
                'desk_id' => $i,
            ]);

            DB::table('desk_lists')->insert([
                'name' => 'In Work',
                'desk_id' => $i,
            ]);

            DB::table('desk_lists')->insert([
                'name' => 'Completed',
                'desk_id' => $i,
            ]);
        }
    }
}
