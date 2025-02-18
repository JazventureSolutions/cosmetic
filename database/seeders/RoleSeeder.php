<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Assign the admin role to the first user
        $firstUser = User::where('email','cardiff@circumcisionclinic.net')->first();
        if ($firstUser) {
            $firstUser->roles()->attach($admin);
        }
    }
}
