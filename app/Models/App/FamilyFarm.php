<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyFarm extends Model
{
    public $timestamps = true;

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
}
