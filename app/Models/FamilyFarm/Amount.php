<?php

namespace App\Models\FamilyFarm;

use App\Models\App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'bill_number',
        'amount',
        'unit_price',
        'family_farm_supply_id',
        'user_id'
    ];

    protected $appends = ['user'];
    public function getUserAttribute() {
        return $this->attributes['user'] = $this->user()->get()->first();
    }

    // korisnik
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
