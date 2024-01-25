<?php

namespace Database\Seeders\App\Traits;

trait PaymentPermissionTrait
{
    public function payments($type, $group = null): array
    {
        return [
            [
                'name' => 'view_payments',
                'type_id' => $type,
                'group_name' => $group ?? 'payments'
            ],
            [
                'name' => 'create_payments',
                'type_id' => $type,
                'group_name' => $group ?? 'payments'
            ],
            [
                'name' => 'update_payments',
                'type_id' => $type,
                'group_name' => $group ?? 'payments'
            ],
            [
                'name' => 'delete_payments',
                'type_id' => $type,
                'group_name' => $group ?? 'payments'
            ],
        ];
    }
}