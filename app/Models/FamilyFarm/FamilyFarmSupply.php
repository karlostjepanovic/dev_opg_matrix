<?php

namespace App\Models\FamilyFarm;

use App\Models\App\Supply;
use App\Models\FamilyFarm\Matrix\Process;
use App\Models\FamilyFarm\Matrix\ProcessAmount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyFarmSupply extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'supply_id',
        'family_farm_id'
    ];

    protected $appends = ['supply', 'available_amounts', 'used_amounts'];
    public function getSupplyAttribute()
    {
        return $this->supply()->get()->first();
    }
    public function getAmountsAttribute(): array
    {
        //return $this->amounts()->get()->toArray();
    }
    public function getUsedAmountsAttribute(): int
    {
        return $this->usedAmounts()->sum('used_amount');
    }
    public function getAvailableAmountsAttribute(): int
    {
        return $this->amounts()->sum('amount') - $this->used_amounts;
    }

    // sredstvo
    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    // zalihe
    public function amounts()
    {
        return $this->hasMany(Amount::class)->orderBy('created_at', 'desc');
    }

    // korišteno u procesima
    public function usedAmounts()
    {
        return $this->hasManyThrough(ProcessAmount::class, Amount::class);
    }
}
