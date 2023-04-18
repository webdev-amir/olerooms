<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DefaultSeederTableSeeder::class);
        $this->call(DefaultRolesTableSeeder::class);
        $this->call(CreateAdminUserSeederTableSeeder::class);
      
    }
}
