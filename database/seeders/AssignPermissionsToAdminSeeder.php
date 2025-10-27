<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class AssignPermissionsToAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::find(1);

        if (! $role) {
            $this->command->info('Role with id=1 not found. Aborting.');
            return;
        }

        $permissionIds = Permission::pluck('id')->toArray();

        if (empty($permissionIds)) {
            $this->command->info('No permissions found to assign.');
            return;
        }

        // Reemplaza los permisos actuales del rol por todos los permisos.
        $role->permissions()->syncWithoutDetaching($permissionIds);

        $this->command->info('Assigned ' . count($permissionIds) . ' permissions to role id 1.');
    }
}
