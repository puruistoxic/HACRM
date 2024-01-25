<?php

namespace Database\Seeders\App\Traits;

trait ContactPersonPermissionTrait
{
    public function contactpersons($type, $group = null): array
    {
        return [
            [
                'name' => 'view_contactperson',
                'type_id' => $type,
                'group_name' => $group ?? 'contactpersons'
            ],
            [
                'name' => 'create_contactperson',
                'type_id' => $type,
                'group_name' => $group ?? 'contactpersons'
            ],
            [
                'name' => 'update_contactperson',
                'type_id' => $type,
                'group_name' => $group ?? 'contactpersons'
            ],
            [
                'name' => 'delete_contactperson',
                'type_id' => $type,
                'group_name' => $group ?? 'contactpersons'
            ],
        ];
    }
}