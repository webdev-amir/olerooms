<?php

namespace Modules\Amenities\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class AmenitiesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds. 
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Amenities';
        if ($groupname) {
            $group = PermissionGroups::where('name', $groupname)->count();
            if ($group == 0) {
                PermissionGroups::create([
                    'name' => ucwords($groupname)
                ]);
            }
        }
        $permissions = [
            'amenities.index',
            'amenities.create',
            'amenities.edit',
            'amenities.destroy',
            'amenities.status',
        ];

        $display = [
            'view_amenities',
            'add_amenities',
            'edit_amenities',
            'remove_amenities',
            'update_status_amenities',
        ];

        foreach ($permissions as $k => $permission) {
            Permission::permissionCreate([
                'guard_name' => 'admin',
                'name' => $permission,
                'group_name' => $groupname,
                'display_name' => $display[$k]
            ]);
        }
    }
}
