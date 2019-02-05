<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UsersRolesPermissionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = new Role();
        $adminRole->name         = Role::ADMIN;
        $adminRole->display_name = 'User Administrator';
        $adminRole->description  = 'User is allowed to manage and edit other users';
        $adminRole->save();

        $clientRole = new Role();
        $clientRole->name         = Role::CLIENT;
        $clientRole->display_name = 'Client';
        $clientRole->description  = 'User that looks for dishes';
        $clientRole->save();



        $password = Hash::make('12345');

        $admin = User::create([
            'first_name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);

        $admin->attachRole($adminRole);


        for ($i = 0; $i < 5; $i++) {
            $client = factory(User::class)->create();

            $client->attachRole($clientRole);
        }
    }
}
