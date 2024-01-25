<?php

namespace App\Models\Tenant\Customer;

use App\Models\Tenant\Rules\ContactPersonActivityRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends TenantModel
{
    use HasFactory, ContactPersonActivityRules;

    protected $fillable = [
        'user_id', 'user_name', 'contact_person_id', 'activityId', 'title', 'description', 'start_time', 'end_time', 'participantsString', 'collaboratorsString', 'activity_status'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class, 'contact_person_id');
    }
}
