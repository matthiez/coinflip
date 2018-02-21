<?php

use Illuminate\Database\Seeder;
use Database\seeds\ContactsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(HashTableSeeder::class);
        $this->call(RollsTableSeeder::class);
    }
}
