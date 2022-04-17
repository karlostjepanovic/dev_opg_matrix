<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'manufacturer',
        'culture_id',
        'operation_type',
        'description',
        'active',
        'measure_unit'
    ];

}
