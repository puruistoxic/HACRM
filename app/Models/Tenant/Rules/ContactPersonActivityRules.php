<?php

namespace App\Models\Tenant\Rules;


trait ContactPersonActivityRules
{
    public function createdRules()
    {
        return [
            'title' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
