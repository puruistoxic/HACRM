<?php

namespace App\Models\Tenant\Customer;

use App\Models\Pos\Settings\Country;
use App\Models\Tenant\Rules\CustomerContactPersonRules;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Customer\CustomerGroup;
use App\Models\Tenant\Customer\Activity;
use App\Models\Tenant\Customer\StatusLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends TenantModel
{
    use HasFactory, CustomerContactPersonRules;

    protected $fillable = [
        'customer_group_id', 'customer_id', 'supplier_id', 'name', 'phonenumber', 'email', 'designation', 'city', 'country_id', 'zip_code', 'area', 'details', 'state'
    ];

    //'country_id',
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLogs::class, 'contact_person_id', 'id');
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'supplier_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contactPersonActivities()
    {
        return $this->hasMany(Activity::class, 'contact_person_id');
    }
}
