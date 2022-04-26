<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyCulture extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'culture_id',
        'supply_id',
    ];
}
