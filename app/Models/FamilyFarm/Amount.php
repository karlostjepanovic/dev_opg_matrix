<?php

namespace App\Models\FamilyFarm;

use App\Models\App\Supply;
use App\Models\App\User;
use App\Models\FamilyFarm\Matrix\ProcessAmount;
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

    protected $appends = ['user', 'available_amounts', 'used_amounts'];
    public function getSupplyAttribute() {
        //return $this->supply()->get()->first();
    }
    public function getUserAttribute() {
        return $this->user()->get()->first();
    }
    public function getUsedAmountsAttribute(): int
    {
        return $this->usedAmounts()->sum('used_amount');
    }
    public function getAvailableAmountsAttribute(): int
    {
        return $this->amount - $this->used_amounts;
    }

    // sredstvo
    public function familyFarmSupply()
    {
        return $this->belongsTo(FamilyFarmSupply::class);
    }

    // korisnik
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // korištene zalihe
    public function usedAmounts()
    {
        return $this->hasMany(ProcessAmount::class);
    }
}
