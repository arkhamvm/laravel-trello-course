<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 48; $i++){
            DB::table('tasks')->insert([
                'card_id' => rand(1, 24),
                'name' => 'Task ' . $i,
            ]);
        }
    }
}
