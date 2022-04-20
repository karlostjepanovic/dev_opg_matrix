<?php

namespace App\Http\Controllers\FamilyFarm;

use App\Http\Controllers\Controller;
use App\Models\App\Culture;
use App\Models\FamilyFarm\FamilyFarmCulture;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamilyFarmCultureController extends Controller
{
    public function addCulture(Request $request): JsonResponse
    {
        $request->validate([
            'culture'   => 'required',
        ]);
        $culture = Culture::where('name', '=', $request->culture)->get()->first();
        if($culture){
            try {
                FamilyFarmCulture::create([
                    'family_farm_id' => session('familyFarm')['id'],
                    'culture_id' => $culture->id
                ]);
            }catch (QueryException $e){
                if($e->getCode() == "23000"){
                    return response()->json([
                        'message' => 'Odabrana kultura je već dodana na popis.'
                    ], 422);
                }else{
                    return response()->json([
                        'message' => 'Dogodila se greška.'
                    ], 422);
                }
            }
            return response()->json([
                'success' => 'Kultura je uspješno dodana.'
            ]);
        }else{
            return response()->json([
                'message' => 'Kultura s ovim nazivom ne postoji.'
            ], 422);
        }
    }
    public function removeCulture($id): JsonResponse
    {
        try {
            $culture = FamilyFarmCulture::where('id', '=', $id)->where('family_farm_id', '=', session('familyFarm')['id']);
            $culture->delete();
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Kultura je uspješno uklonjena.'
        ]);
    }
}
