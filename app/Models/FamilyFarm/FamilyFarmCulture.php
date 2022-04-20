<?php

namespace App\Models\FamilyFarm;

use App\Models\App\Culture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyFarmCulture extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'family_farm_id',
        'culture_id',
    ];

    public function culture()
    {
        return $this->belongsTo(Culture::class, 'culture_id');
    }
}
