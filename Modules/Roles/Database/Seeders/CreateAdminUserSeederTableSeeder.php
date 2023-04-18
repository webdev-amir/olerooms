<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isExist = User::where('email','testapp@yopmail.com')->first();
        if(!$isExist){
            $user = User::create([
                'name' => 'Nirbhay Dhaked', 
                'email' => 'testapp@yopmail.com',
                'password' => bcrypt('12345678')
            ]);

            $role = Role::where('slug','admin')->first();

            $permissions = Permission::pluck('id','id')->all();

            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
        }
    }
}
