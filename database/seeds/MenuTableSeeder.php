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
            'setting' => [
                'singular_name' => 'setting',
                'plural_name' => 'settings',
                'icon' => 'fa-gear',
                'title' => 'Settings',
                'menu_type' => 0,
                'position' => 0,
                'roles' => 1
            ],
            'menu' => [
                'singular_name' => 'menu',
                'plural_name' => 'menu',
                'icon' => 'fa-list',
                'title' => 'Menu',
                'menu_type' => 0,
                'position' => 5,
                'roles' => 1
            ],
            'role' => [
                'singular_name' => 'role',
                'plural_name' => 'roles',
                'icon' => 'fa-lock',
                'title' => 'Roles',
                'menu_type' => 1,
                'position' => 4,
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
            'client' => [
                'singular_name' => 'client',
                'plural_name' => 'clients',
                'icon' => 'fa-database',
                'title' => 'Clients',
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
                'position' => 1,
                'roles' => 1
            ],
            'shipping' => [
                'singular_name' => 'shipping',
                'plural_name' => 'shippings',
                'icon' => 'fa-truck',
                'title' => 'Shippings',
                'menu_type' => 1,
                'position' => 6,
                'roles' => 1
            ],
        ];

        foreach ($menu as $entity => $data) {
            factory(\App\Models\Menu::class)->create($data);
        }
    }
}
