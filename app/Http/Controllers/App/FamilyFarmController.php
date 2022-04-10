<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use Illuminate\Support\Facades\Auth;

class FamilyFarmController extends Controller
{
    public function getAvailableFamilyFarms()
    {
        $user = Auth::user();
        return FamilyFarm::with('owner')
                ->where('owner_id', '=', $user->id)
                //->orWhereIn('id', array_column($user->familyFarms()->get()->toArray(), 'id'))
                ->orderBy('name')->get()->toArray();
    }
}
