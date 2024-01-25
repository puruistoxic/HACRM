<?php

namespace Database\Seeders\App\Traits;

trait ActivityPermissionTrait
{
    public function activities($type, $group = null): array
    {
        return [
            [
                'name' => 'view_activity',
                'type_id' => $type,
                'group_name' => $group ?? 'activities'
            ],
            [
                'name' => 'create_activity',
                'type_id' => $type,
                'group_name' => $group ?? 'activities'
            ],
            [
                'name' => 'update_activity',
                'type_id' => $type,
                'group_name' => $group ?? 'activities'
            ],
            [
                'name' => 'delete_activity',
                'type_id' => $type,
                'group_name' => $group ?? 'activities'
            ],
        ];
    }
}