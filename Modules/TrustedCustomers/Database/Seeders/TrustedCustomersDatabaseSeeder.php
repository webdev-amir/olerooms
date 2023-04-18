<?php

namespace Modules\TrustedCustomers\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class TrustedCustomersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'TrustedCustomers';
        if($groupname){
            $group = PermissionGroups::where('name',$groupname)->count();
            if($group == 0)
            {
                PermissionGroups::create([
                    'name' => ucwords($groupname)
                ]); 
            }
        }
        $permissions = [
           'trustedcustomers.index',
           'trustedcustomers.create',
           'trustedcustomers.edit',
           'trustedcustomers.destroy',
           'trustedcustomers.status',
        ];

        $display = [
           'view_trustedcustomers',
           'add_trustedcustomers',
           'edit_trustedcustomers',
           'remove_trustedcustomers',
           'activate_deactivate_trustedcustomers',
        ];

        foreach ($permissions as $k => $permission) {
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            Permission::permissionCreate([
                'guard_name'=>'admin',
                'name' => $permission,
                'group_name' => $groupname,
                'display_name' => $display[$k]
            ]);
        }
    }
}
