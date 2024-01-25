<?php


namespace App\Filters\Core\traits;


trait FirstSearchFilterTrait
{
    public function search($search = null)
    {
        $this->builder
            ->where('first_name', 'LIKE', "%{$search}%");
    }
}
