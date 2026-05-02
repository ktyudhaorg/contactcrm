<?php

namespace App\Http\Repositories\User;

use App\Http\Repositories\BaseRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository
{
    public function __construct(
        protected Role $role
    ) {
        parent::__construct($role);
    }

    public function getPermissionsByRole(string $roleName): array
    {
        return $this->role
            ->where('name', $roleName)
            ->firstOrFail()
            ->permissions
            ->pluck('name')
            ->toArray();
    }

    public function givePermissionsToRole(string $roleName, array $permissions): Role
    {
        $role = $this->role->where('name', $roleName)->firstOrFail();
        $role->syncPermissions($permissions);

        return $role;
    }
}
