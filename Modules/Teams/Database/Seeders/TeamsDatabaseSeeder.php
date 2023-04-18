<?php

namespace Modules\Teams\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class TeamsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds. 
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Teams';
        if ($groupname) {
            $group = PermissionGroups::where('name', $groupname)->count();
            if ($group == 0) {
                PermissionGroups::create([
                    'name' => ucwords($groupname)
                ]);
            }
        }
        $permissions = [
            'teams.index',
            'teams.create',
            'teams.edit',
            'teams.destroy',
            'teams.status',
        ];

        $display = [
            'view_teams',
            'add_teams',
            'edit_teams',
            'remove_teams',
            'update_status_teams',
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
