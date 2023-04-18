<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Roles\Entities\Role;


class DefaultRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminrole = Role::where('slug','admin')->first();
        if(!$adminrole){
            Role::create([
                'name'=>'admin',
                'guard_name'=>'admin',
                'display_name' => 'admin',
                'description' => 'admin',
            ]);
         }
        $userRole = Role::where('slug','user')->first();
        if(!$userRole){
            Role::create([
                'name'=>'user',
                'guard_name'=>'web',
                'display_name' => 'user',
                'description' => 'user',
            ]);
        }
    }
}
