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
        'email',
        'phone',
        'otp_token',
        'admin_role',
        'font_size',
        'last_login',
        'last_otp',
    ];

    protected $appends = ['full_name', 'family_farm_admin_role'];
    public function getFullNameAttribute() {
        return $this->firstname." ".$this->lastname;
    }
    public function getFamilyFarmAdminRoleAttribute() {
        $familyFarm = $this->familyFarms()->where('family_farm_id', '=', session('familyFarm') ? session('familyFarm')['id'] : null)->first();
        return $familyFarm ? $familyFarm['pivot']['admin_role'] : [];
    }

    // korisnik je djelatnik u opg-ovima
    public function familyFarms()
    {
        return $this->belongsToMany(
            FamilyFarm::class,
            'employees',
            'user_id',
            'family_farm_id')
            ->withPivot(Schema::getColumnListing('employees'));
    }
}
