<?php

namespace App\Models\Tenant\Customer;

use App\Models\Tenant\Rules\ContactPersonPaymentRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends TenantModel
{
    use HasFactory, ContactPersonPaymentRules;

    protected $fillable = [
        'user_id', 'contact_person_id', 'title', 'description', 'source', 'date', 'net_gbp',
        'gross_gbp',
        'payment_status'
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
