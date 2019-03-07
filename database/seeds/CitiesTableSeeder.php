<?php

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = require(dirname(__FILE__) . '/../data/cities.php');

        foreach ($cities as $city) {
            factory(City::class)->create([
                'kartina_id' => $city['kartina_id'],
                'name' => $city['name'],
                'country_id' => Country::where('kartina_id', $city['kartina_country_id'])->first()->id
            ]);
        }
    }
}
