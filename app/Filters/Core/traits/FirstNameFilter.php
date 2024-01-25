<?php


namespace App\Filters\Core\traits;


trait FirstNameFilter
{
    public function name($name = null)
    {
        $this->whereClause('first_name', "%{$name}%", "LIKE");
    }
}
