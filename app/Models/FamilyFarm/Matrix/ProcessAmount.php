<?php

namespace App\Models\FamilyFarm\Matrix;

use App\Models\FamilyFarm\Amount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessAmount extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'used_amount',
        'amount_id',
        'process_id',
    ];

    /*protected $appends = ['amount'];
    public function getAmountAttribute() {
        return $this->amount()->get()->first();
    }*/

    // zaliha
    public function amount()
    {
        return $this->belongsTo(Amount::class);
    }

    // proces
    public function process()
    {
        return $this->belongsTo(Process::class);
    }
}
