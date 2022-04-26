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
            'amount'        => 'required|numeric|integer|min:0|not_in:0',
            'unit_price'    => 'required|numeric|min:0|not_in:0',
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
        // TODO: kad se napravi da se zaliha evidentira uz proces onda napraviti provjeru da nova količina ne smije biti manja o dtrenutno potrošene količine
        $request->validate([
            'bill_number'   => 'required',
            'amount'        => 'required|numeric|integer|min:0|not_in:0',
            'unit_price'    => 'required|numeric|min:0|not_in:0',
        ]);
        try {
            $amount = Amount::find($id);
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
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Zaliha je uspješno obrisana!'
        ], 200);
    }
}
