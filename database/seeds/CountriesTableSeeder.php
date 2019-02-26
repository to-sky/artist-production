<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = require(dirname(__FILE__) . '/../data/countries.php');

        foreach ($countries as $country) {
            factory(\App\Models\Country::class)->create([
                'kartina_id' => $country['kartina_id'],
                'name' => $country['name']
            ]);
        }
    }
}
