<?php

use App\Models\shared\Role;
use App\User;
use Illuminate\Database\Seeder;

class SetQuyenAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::where('username', 'admin')->first();

        $admin->roles()->detach($adminRole);
        $admin->roles()->attach($adminRole);
        
    }
}
