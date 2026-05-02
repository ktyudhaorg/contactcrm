<?php

namespace App\Http\Repositories\User;

use App\Http\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository
{
    public function __construct(protected Permission $permission)
    {
        parent::__construct($permission);
    }

    public function getAllPermissions(): array
    {
        return $this->permission->pluck('name')->toArray();
    }

    public function createMany(array $names, string $guard)
    {
        $data = collect($names)->map(fn($name) => [
            'name' => $name,
            'guard_name' => $guard,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        return $this->permission->insert($data);
    }
}
