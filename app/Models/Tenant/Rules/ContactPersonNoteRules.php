<?php

namespace App\Models\Tenant\Rules;


trait ContactPersonNoteRules
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
