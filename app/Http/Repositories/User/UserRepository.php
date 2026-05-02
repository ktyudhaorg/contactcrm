<?php

namespace App\Http\Repositories\User;

use App\Http\Repositories\BaseRepository;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRepository extends BaseRepository
{
    public function __construct(
        protected User $user,
        protected Role $role,
        protected Permission $permission
    ) {
        parent::__construct($user);
    }

    public function findUserAuth(string $value)
    {
        return $this->user::where('email', $value)->first();
    }

    public function createWithRole(array $data)
    {
        $role = $data['role'];

        $user = $this->user::create($data);
        if ($role) $user->assignRole($role);

        return $user;
    }

    public function updateOrCreate(array $username, array  $data)
    {
        return $this->user::updateOrCreate($username, $data);
    }

    public function updateWithRole(string $id, array $data)
    {
        $role = $data['role'];

        $user = $this->user::findOrFail($id);
        $user->update($data);

        if ($role) $user->syncRoles([$role]);

        return $user;
    }

    /** MANAGEMENT ROLE PERMISSION */

    public function getAllRoles()
    {
        return $this->role->all();
    }

    public function getAllPermissions()
    {
        return $this->permission->all();
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
