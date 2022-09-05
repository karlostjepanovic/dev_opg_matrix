<?php

namespace App\Http\Controllers\FamilyFarm;

use App\Http\Controllers\Controller;
use App\Models\FamilyFarm\Amount;
use App\Models\FamilyFarm\FamilyFarmSupply;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmountController extends Controller
{
    public function createAmount($id, Request $request): JsonResponse
    {
        if(!FamilyFarmSupply::find($id)->where('family_farm_id', '=', session('familyFarm')['id'])){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        $request->validate([
            'bill_number'   => 'required',
            'amount'        => 'required|numeric|integer|gt:0',
            'unit_price'    => 'required|numeric|gt:0',
        ]);
        try {
            Amount::create([
                'bill_number' => $request->bill_number,
                'amount' => $request->amount,
                'unit_price' => $request->unit_price,
                'family_farm_supply_id' => $id,
                'user_id' => Auth::id(),
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Zaliha je uspješno spremljena.'
        ], 200);
    }

    public function editAmount($id, Request $request): JsonResponse
    {
        $amount = Amount::find($id);
        $request->validate([
            'bill_number'   => 'required',
            'amount'        => 'required|numeric|integer|gt:0',
            'unit_price'    => 'required|numeric|gt:0',
        ]);
        if($amount->used_amounts > $request->amount){
            return response()->json([
                'errors' => [
                    'amount' => [null],
                ],
                'message' => 'Količina ne može biti manja od one koja je već utrošena za ovu zalihu.'
            ], 422);
        }
        try {
            $amount->update([
                'bill_number' => $request->bill_number,
                'amount' => $request->amount,
                'unit_price' => $request->unit_price,
                'user_id' => Auth::id(),
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Promjene su uspješno spremljene.'
        ], 200);
    }

    public function deleteAmount($id): JsonResponse
    {
        try {
            Amount::destroy($id);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Nije moguće obrisati odabranu zalihu.'
            ], 422);
        }
        return response()->json([
            'success' => 'Zaliha je uspješno obrisana!'
        ], 200);
    }
}
