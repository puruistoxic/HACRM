<?php

namespace App\Models\Core\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'login_ip','last_login_latitude','last_login_longitude','city','regionName','country','zip_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
