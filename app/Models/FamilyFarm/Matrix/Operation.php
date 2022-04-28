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
        'ended',
        'matrix_id',
        'user_id'
    ];

    protected $appends = ['matrix', 'user', 'total_tracking'];
    public function getMatrixAttribute() {
        return $this->matrix()->get()->first();
    }
    public function getUserAttribute() {
        return $this->user()->get()->first();
    }
    public function getProcessesAttribute() {
        //return $this->processes()->get()->toArray();
    }
    public function getTotalTrackingAttribute() {
        return $this->processes()->sum('tracking_value');
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

    // procesi
    public function processes()
    {
        return $this->hasMany(Process::class)->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }
}
