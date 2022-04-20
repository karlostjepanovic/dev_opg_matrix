<?php

namespace App\Models\FamilyFarm;

use App\Models\App\FamilyFarm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastralParcel extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'number',
        'arcode',
        'area',
        'community',
        'family_farm_id'
    ];

    protected $appends = ['full_name'];
    public function getFullNameAttribute() {
        return $this->attributes['full_name'] = $this->number." - ".$this->arcode;
    }

    // katastarska čestica pripada opg-u
    public function familyFarm()
    {
        return $this->belongsTo(FamilyFarm::class);
    }
}
