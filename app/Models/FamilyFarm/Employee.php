<?php

namespace App\Models\FamilyFarm;

use App\Models\App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'family_farm_id',
        'user_id',
        'admin_role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
