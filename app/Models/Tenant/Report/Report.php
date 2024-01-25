<?php

namespace App\Models\Tenant\Report;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $fillable = [
        'report_id',
        'report_name',
        'report_link',
        // Add other fields as needed
    ];
}
