<?php

use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = [
            'menu' => [
                'singular_name' => 'menu',
                'plural_name' => 'menu',
                'icon' => 'fa-list',
                'title' => 'Menu',
                'menu_type' => 0,
                'position' => 1,
                'roles' => 1
            ],
            'role' => [
                'singular_name' => 'role',
                'plural_name' => 'roles',
                'icon' => 'fa-lock',
                'title' => 'Roles',
                'menu_type' => 1,
                'position' => 2,
                'roles' => 1
            ],
            'user' => [
                'singular_name' => 'user',
                'plural_name' => 'users',
                'icon' => 'fa-users',
                'title' => 'Users',
                'menu_type' => 1,
                'position' => 3,
                'roles' => 1
            ],
            'country' => [
                'singular_name' => 'country',
                'plural_name' => 'countries',
                'icon' => 'fa-globe',
                'title' => 'Countries',
                'menu_type' => 1,
                'position' => 4,
                'roles' => 1
            ],
            'city' => [
                'singular_name' => 'city',
                'plural_name' => 'cities',
                'icon' => 'fa-location-arrow',
                'title' => 'Cities',
                'menu_type' => 1,
                'position' => 5,
                'roles' => 1
            ],
            'building' => [
                'singular_name' => 'building',
                'plural_name' => 'buildings',
                'icon' => 'fa-building',
                'title' => 'Buildings',
                'menu_type' => 1,
                'position' => 1,
                'roles' => 1
            ],
            'hall' => [
                'singular_name' => 'hall',
                'plural_name' => 'halls',
                'icon' => 'fa-columns',
                'title' => 'Halls',
                'menu_type' => 1,
                'position' => 2,
                'roles' => 1
            ],
            'event' => [
                'singular_name' => 'event',
                'plural_name' => 'events',
                'icon' => 'fa-calendar',
                'title' => 'Events',
                'menu_type' => 1,
                'position' => 3,
                'roles' => 1
            ],
            'client' => [
                'singular_name' => 'client',
                'plural_name' => 'clients',
                'icon' => 'fa-database',
                'title' => 'Clients',
                'menu_type' => 1,
                'position' => 8,
                'roles' => 1
            ]
        ];

        foreach ($menu as $entity => $data) {
            factory(\App\Models\Menu::class)->create($data);
        }
    }
}
