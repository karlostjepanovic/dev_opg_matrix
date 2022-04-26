<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\SupplyCulture;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplyCultureController extends Controller
{
    public function createSupplyCulture($id, Request $request): JsonResponse
    {
        $request->validate([
            'culture'          => 'required',
        ]);
        try {
            SupplyCulture::create([
                'culture_id' => $request->culture,
                'supply_id' => $id,
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Odabrana kultura je već dodana za sredstvo.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Kultura je uspješno dodana.'
        ], 200);
    }

    public function deleteSupplyCulture($id): JsonResponse
    {
        try {
            SupplyCulture::destroy($id);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Kultura je uspješno uklonjena!'
        ], 200);
    }
}
