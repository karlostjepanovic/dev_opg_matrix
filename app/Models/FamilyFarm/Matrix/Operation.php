<?php

namespace App\Models\FamilyFarm\Matrix;

use App\Models\App\User;
use App\Models\FamilyFarm\Matrix;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'ordinal_number',
        'operation_type',
        'unit_price',
        'tracking',
        'matrix_id',
        'user_id'
    ];

    protected $appends = ['matrix', 'user'];
    public function getMatrixAttribute() {
        return $this->attributes['matrix'] = $this->matrix()->get()->first();
    }
    public function getUserAttribute() {
        return $this->attributes['user'] = $this->user()->get()->first();
    }

    // matrica
    public function matrix()
    {
        return $this->belongsTo(Matrix::class);
    }

    // korisnik
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
