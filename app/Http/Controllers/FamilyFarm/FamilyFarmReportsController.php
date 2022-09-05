<?php

namespace App\Http\Controllers\FamilyFarm;

use App\Http\Controllers\Controller;
use App\Models\FamilyFarm\Matrix\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyFarmReportsController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'date'  => 'required|date_format:d.m.Y.',
        ]);
        $date = date_format(date_create($request->date), 'Y-m-d');
        /*return Matrix::with(['operations' => function ($query) use ($request) {
                return $query->with('processes')->whereHas('processes', function ($query) use ($request) {
                    return $query->where('date', '=', $request->date)->where('user_id', '=', Auth::id());
                });
            }])
            ->whereHas('cadastralParcel', function ($query) use ($request) {
                return $query->where('family_farm_id', '=', session('family_farm')['id']);
            })
            ->get()->toArray();*/
        return Process::with(['operation' => function ($query) {
            return $query->with('matrix');
        }])
        ->with('processAmounts', function ($query){
            return $query->with('amount', function ($query){
                return $query->with('familyFarmSupply', function ($query){
                    return $query->with('supply');
                });
            });
        })
        ->whereHas('operation', function ($query) use ($request) {
            return $query->whereHas('matrix', function ($query) use ($request) {
                return $query->whereHas('cadastralParcel', function ($query) use ($request) {
                    return $query->where('family_farm_id', '=', session('familyFarm')['id']);
                });
            });
        })
        ->where('date', '=', $date)
        ->where('user_id', '=', Auth::id())
        ->get()->toArray();
    }
}
