<?php

namespace App\Models\FamilyFarm\Matrix;

use App\Models\App\User;
use App\Models\FamilyFarm\Amount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'date',
        'duration',
        'tracking_value',
        'weather',
        'note',
        'operation_id',
        'user_id'
    ];

    protected $appends = ['user'];
    public function getUserAttribute() {
        return $this->user()->get()->first();
    }
    public function getAmountsAttribute() {
        //return $this->amounts()->get()->toArray();
    }

    // korisnik
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // korištena sredstva
    public function processAmounts()
    {
        return $this->hasMany(ProcessAmount::class);
    }

    // operacija
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }
}
