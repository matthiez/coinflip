<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RollsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rolls')->insert([
            'roll' => 1,
            'hash' => '13382870d3a720ab8ffaa8d57272883ad707d61e59d0b3db30502bdd3eb17759',
            'time' => time()
        ]);
    }
}
