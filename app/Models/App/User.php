<?php

namespace App\Models\App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;
use function session;

class User extends Authenticatable
{
    public $timestamps = false;

    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'password',
        'oib',
        'email',
        'phone',
        'otp_token',
        'admin_role',
        'font_size',
        'last_login',
        'last_otp',
    ];

    protected $appends = ['full_name'];
    public function getFullNameAttribute() {
        return $this->attributes['full_name'] = $this->firstname." ".$this->lastname;
    }
}
