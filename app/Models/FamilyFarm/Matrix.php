<?php

namespace App\Models\FamilyFarm;

use App\Models\App\Culture;
use App\Models\FamilyFarm\Matrix\Note;
use App\Models\FamilyFarm\Matrix\Operation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matrix extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'tracking_type',
        'locked',
        'culture_id',
        'cadastral_parcel_id'
    ];

    protected $appends = ['culture', 'cadastral_parcel'];
    public function getCultureAttribute() {
        return $this->attributes['culture'] = $this->culture()->get()->first();
    }
    public function getCadastralParcelAttribute() {
        return $this->attributes['cadastral_parcel'] = $this->cadastralParcel()->get()->first();
    }

    public function cadastralParcel()
    {
        return $this->belongsTo(CadastralParcel::class);
    }

    public function culture()
    {
        return $this->belongsTo(Culture::class);
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
