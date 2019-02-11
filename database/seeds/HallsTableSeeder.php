<?php

use App\Models\Hall;
use Illuminate\Database\Seeder;

class HallsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Hall::class, 100)->create();
    }
}
