<?php

use App\Models\shared\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('role_user')->truncate();
        // User::truncate();
        // DB::table('users')->delete();
        $adminRole = Role::where('name', 'Administrator')->first();
        $leaderRole = Role::where('name', 'leader')->first();
        $userRole = Role::where('name', 'User')->first();

        $list = User::where('username','admin')->get();
        if(!$list || $list->count()==0){
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'company_id' => '6000',
                'gender' => '1',
                'password' => Hash::make('admin123')
            ]);
            $admin->roles()->detach($adminRole);
            $admin->roles()->attach($adminRole);
        }

        $list = User::where('username','leader')->get();
        if(!$list || $list->count()==0){
            $leader = User::create([
                'name' => 'Leader User',
                'company_id' => '6000',
                'username' => 'leader',
                'email' => 'leader@leader.com',
                'gender' => '1',
                'password' => Hash::make('admin123')
            ]);
            $leader->roles()->detach($adminRole);
            $leader->roles()->attach($adminRole);
        }
        $list = User::where('username','user')->get();
        if(!$list || $list->count()==0){
            $user = User::create([
                'name' => 'Generic User',
                'username' => 'user',
                'email' => 'user@user.com',
                'company_id' => '6000',
                'gender' => '1',
                'password' => Hash::make('admin123')
            ]);
            $user->roles()->detach($adminRole);
            $user->roles()->attach($adminRole);
        } 
       
       
    }
}
