<?php

namespace App\Models\App;

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

    // opg pripada vlasniku
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
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
}
