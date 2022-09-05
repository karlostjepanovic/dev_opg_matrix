<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Supply extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'manufacturer',
        'culture_id',
        'operation_type',
        'description',
        'active',
        'measure_unit'
    ];

    public function cultures()
    {
        return $this->belongsToMany(
            Culture::class,
            'supply_cultures',
            'supply_id',
            'culture_id')
            ->withPivot(Schema::getColumnListing('supply_cultures'));
    }

    public function familyFarms()
    {
        return $this->belongsToMany(FamilyFarm::class, 'family_farm_supplies');
    }
}
