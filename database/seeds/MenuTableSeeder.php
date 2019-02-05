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
            ]
        ];

        foreach ($menu as $entity => $data) {
            factory(\App\Models\Menu::class)->create($data);
        }
    }
}
