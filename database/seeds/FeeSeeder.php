<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fees')->insert([
           ['class_id'=>1, 'program_id'=>1, 'fee'=>2200000],
           ['class_id'=>1, 'program_id'=>2, 'fee'=>5500000],
           ['class_id'=>1, 'program_id'=>3, 'fee'=>6500000],
           ['class_id'=>1, 'program_id'=>4, 'fee'=>8000000],
           ['class_id'=>2, 'program_id'=>1, 'fee'=>1800000],
           ['class_id'=>2, 'program_id'=>2, 'fee'=>4500000],
           ['class_id'=>2, 'program_id'=>3, 'fee'=>5500000],
           ['class_id'=>2, 'program_id'=>4, 'fee'=>8000000],
           ['class_id'=>3, 'program_id'=>1, 'fee'=>3500000],
           ['class_id'=>3, 'program_id'=>3, 'fee'=>7000000]
        ]);
    }
}
