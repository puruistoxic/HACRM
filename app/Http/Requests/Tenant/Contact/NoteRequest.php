<?php

namespace App\Http\Requests\Tenant\Contact;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\Customer\Note;

class NoteRequest extends TenantRequest
{
    public function rules(): array
    {
        return $this->initRules( new Note());
    }
}