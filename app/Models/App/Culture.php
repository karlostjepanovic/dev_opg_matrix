<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'active',
    ];
}
