<?php

namespace App\Models\Tenant\Customer;

use App\Models\Core\File\File;
use App\Models\Tenant\Rules\ContactPersonNoteRules;
use App\Models\Tenant\TenantModel;
use App\Models\Core\Traits\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;


class Note extends TenantModel
{
    use HasFactory, ContactPersonNoteRules, CreatedByRelationship;

    protected $fillable = [
        'user_id', 'contact_person_id', 'title', 'description'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class, 'contact_person_id');
    }

    // public function attachments()
    // {
    //     return $this->morphMany(File::class, 'fileable')->whereType('note');

    // }

    public function attachments() : MorphMany
    {
        // return $this->morphMany(File::class, 'fileable')->whereType('note');
        return $this->morphMany(File::class, 'fileable');
    }
}
