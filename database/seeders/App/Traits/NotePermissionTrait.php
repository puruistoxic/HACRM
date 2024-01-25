<?php

namespace Database\Seeders\App\Traits;

trait NotePermissionTrait
{
    public function notes($type, $group = null): array
    {
        return [
            [
                'name' => 'view_notes',
                'type_id' => $type,
                'group_name' => $group ?? 'notes'
            ],
            [
                'name' => 'create_notes',
                'type_id' => $type,
                'group_name' => $group ?? 'notes'
            ],
            [
                'name' => 'update_notes',
                'type_id' => $type,
                'group_name' => $group ?? 'notes'
            ],
            [
                'name' => 'delete_notes',
                'type_id' => $type,
                'group_name' => $group ?? 'notes'
            ],
        ];
    }
}