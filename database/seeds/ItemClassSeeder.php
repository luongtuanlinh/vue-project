<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_class')->insert([
           ['name'  => 'B1'],
           ['name'  => 'B2'],
           ['name'  => 'C'],
        ]);
    }
}
