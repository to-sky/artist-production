<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = require(dirname(__FILE__) . '/../data/roles.php');

        foreach ($roles as $role) {
            factory(\App\Models\Role::class)->create([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description']
            ]);
        }
    }
}
