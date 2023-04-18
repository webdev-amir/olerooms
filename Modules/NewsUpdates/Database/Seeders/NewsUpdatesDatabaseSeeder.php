<?php

namespace Modules\NewsUpdates\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class NewsUpdatesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds. 
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'NewsUpdates';
        if ($groupname) {
            $group = PermissionGroups::where('name', $groupname)->count();
            if ($group == 0) {
                PermissionGroups::create([
                    'name' => ucwords($groupname)
                ]);
            }
        }
        $permissions = [
            'newsupdates.index',
            'newsupdates.create',
            'newsupdates.edit',
            'newsupdates.destroy',
            'newsupdates.status',
        ];

        $display = [
            'view_newsupdates',
            'add_newsupdates',
            'edit_newsupdates',
            'remove_newsupdates',
            'update_status_newsupdates',
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

