<?php

namespace App\Models\Tenant\Customer;

use App\Models\Tenant\Customer\ContactPerson;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusLogs extends TenantModel
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'contact_person_id', 'status', 'is_deleted'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function contactPerson()
    {
        return $this->belongsTo(ContactPerson::class, 'contact_person_id', 'id');
    }
}
