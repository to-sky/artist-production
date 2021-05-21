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
        $adminRole = Role::where('name', Role::ADMIN)->first();

        $bookkeeperRole = Role::where('name', Role::BOOKKEEPER)->first();

        $partnerRole = Role::where('name', Role::PARTNER)->first();


        $password = Hash::make('12345');

        $admin = User::create([
            'first_name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);

        $admin->attachRole($adminRole);


        for ($i = 0; $i < 5; $i++) {
            $bookkeeper = factory(User::class)->create();

            $bookkeeper->attachRole($bookkeeperRole);
        }

        for ($i = 0; $i < 5; $i++) {
            $partner = factory(User::class)->create();

            $partner->attachRole($partnerRole);
        }
    }
}
