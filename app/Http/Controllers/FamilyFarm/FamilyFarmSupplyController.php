<?php

namespace App\Http\Controllers\FamilyFarm;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use App\Models\FamilyFarm\FamilyFarmSupply;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamilyFarmSupplyController extends Controller
{
    public function addSupply(Request $request): JsonResponse
    {
        $request->validate([
            'supply'    => 'required'
        ]);
        try {
            FamilyFarmSupply::create([
                'supply_id' => $request->supply,
                'family_farm_id' => session('familyFarm')['id']
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Odabrano sredstvo je već dodano u OPG.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Sredstvo je uspješno dodano.'
        ], 200);
    }

    public function removeSupply($id): JsonResponse
    {
        try {
            $supply = FamilyFarmSupply::where('id', '=', $id)->where('family_farm_id', '=', session('familyFarm')['id']);
            $supply->delete();
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Nije moguće obrisati odabrano sredstvo.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Sredstvo je uspješno obrisano!'
        ], 200);
    }
}
