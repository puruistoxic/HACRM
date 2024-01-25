<?php

namespace App\Models\Tenant\Customer;

use App\Models\Pos\Settings\Country;
use App\Models\Tenant\Rules\CustomerContactPersonRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPerson extends TenantModel
{
    use HasFactory, CustomerContactPersonRules;

    protected $fillable = [
        'customer_id', 'supplier_id', 'name', 'phonenumber', 'email', 'designation'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'supplier_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
