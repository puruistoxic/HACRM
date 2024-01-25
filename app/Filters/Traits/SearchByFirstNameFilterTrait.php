<?php

namespace App\Filters\Traits;

trait SearchByFirstNameFilterTrait
{
    public function searchByName($value = null)
    {
        $this->builder
            ->where('first_name', 'LIKE', "%{$value}%");
    }


}
