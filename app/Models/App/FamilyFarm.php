<?php

namespace App\Models\App;

use App\Models\FamilyFarm\CadastralParcel;
use App\Models\FamilyFarm\FamilyFarmSupply;
use App\Models\FamilyFarm\Matrix;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class FamilyFarm extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'place',
        'oib',
        'email',
        'phone',
        'website',
        'active',
        'owner_id'
    ];

    protected $appends = ['owner'];
    public function getOwnerAttribute() {
        return $this->owner()->get()->first();
    }

    // opg pripada vlasniku
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    // matrice
    public function matrices()
    {
        return $this->hasManyThrough(Matrix::class, CadastralParcel::class);
    }

    // djelatnici
    public function employees()
    {
        return $this->belongsToMany(
            User::class,
            'employees',
            'family_farm_id',
            'user_id')
            ->withPivot(Schema::getColumnListing('employees'));
    }

    // katastarske čestice
    public function cadastralParcels()
    {
        return $this->hasMany(CadastralParcel::class);
    }

    // kulture
    public function cultures()
    {
        return $this->belongsToMany(
            Culture::class,
            'family_farm_cultures',
            'family_farm_id',
            'culture_id')
            ->withPivot(Schema::getColumnListing('family_farm_cultures'));
    }

    // sredstva
    public function supplies()
    {
        return $this->hasMany(FamilyFarmSupply::class);
        /*return $this->belongsToMany(
            Supply::class,
            'family_farm_supplies',
            'family_farm_id',
            'supply_id')
            ->withPivot(Schema::getColumnListing('family_farm_supplies'));*/
    }
}
