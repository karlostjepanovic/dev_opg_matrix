<?php

namespace App\Models\FamilyFarm\Matrix;

use App\Models\App\User;
use App\Models\FamilyFarm\Matrix;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'date',
        'note',
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
