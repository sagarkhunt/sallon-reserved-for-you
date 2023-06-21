<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Role();
        $admin->name = "admin";
        $admin->display_name = "Admin";
        $admin->description = "User is Admin ";
        $admin->save();

        $user = new Role();
        $user->name = "user";
        $user->display_name = "User";
        $user->description = "User ";
        $user->save();

        $service = new Role();
        $service->name = "service";
        $service->display_name = "Service Provider";
        $service->description = "Service Provider";
        $service->save();

        $employee = new Role();
        $employee->name = "employee";
        $employee->display_name = "Service Provider Employee";
        $employee->description = "Service Provider Employee";
        $employee->save();

        $createUser = new Permission();
        $createUser->name = "create-users";
        $createUser->display_name = "Create Users";
        $createUser->description = "Create New Users";
        $createUser->save();

        $editUser = new Permission();
        $editUser->name = "edit-users";
        $editUser->display_name = "Edit Users";
        $editUser->description = "Edit Users";
        $editUser->save();

        $deleteUser = new Permission();
        $deleteUser->name = "delete-users";
        $deleteUser->display_name = "Delete Users";
        $deleteUser->description = "Delete Users";
        $deleteUser->save();

        $user = new User();
        $user->first_name = 'Admin';
        $user->last_name = 'User';
        $user->email = 'admin@gmail.com';
        $user->password = \Hash::make('admin');
        $user->save();

        $admin->attachPermissions(array($createUser, $editUser, $deleteUser));
        $user->attachRole($admin);
    }
}
