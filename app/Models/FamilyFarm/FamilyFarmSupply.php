<?php

namespace App\Models\FamilyFarm;

use App\Models\App\Supply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyFarmSupply extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'supply_id',
        'family_farm_id'
    ];

    protected $appends = ['supply', 'amounts', 'available_amounts'];
    public function getSupplyAttribute()
    {
        return $this->attributes['supply'] = $this->supply()->get()->first();
    }
    public function getAmountsAttribute(): array
    {
        return $this->attributes['amounts'] = $this->amounts()->get()->toArray();
    }
    public function getAvailableAmountsAttribute(): int
    {
        // TODO: oduzeti s utrošenom kako bi se dobila dostupna zaliha
        return $this->attributes['available_amounts'] = $this->amounts()->sum('amount');
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    // zalihe
    public function amounts()
    {
        return $this->hasMany(Amount::class)->orderBy('created_at', 'desc');
    }
}
