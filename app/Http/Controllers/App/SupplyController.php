<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\Supply;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function getAvailableSupplies()
    {
        return Supply::where('active', true)->orderBy('name')->get()->toArray();
    }

    public function createSupply(Request $request): JsonResponse
    {
        $request->validate([
            'name'              => 'required',
            'manufacturer'      => 'required',
            'operation_type'    => 'required',
            'measure_unit'      => 'required',
            'description'       => 'nullable',
            'cultures'          => 'required|array',
        ]);
        try {
            $supply = new Supply();
            $supply->name = $request->name;
            $supply->manufacturer = $request->manufacturer;
            $supply->operation_type = $request->operation_type;
            $supply->measure_unit = $request->measure_unit;
            $supply->description = $request->description;
            $supply->save();
            foreach ((array)($request->cultures) as $culture){
                $supplyCulture = new SupplyCultureController();
                $supplyCulture->createSupplyCulture($supply->id, new Request([
                    'culture' => $culture['culture'][0]['id'],
                ]));
            }
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Sredstvo s ovim nazivom, proizvođačem i procesom već postoji.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'.$e->getMessage()
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Novo sredstvo je uspješno spremljeno.'
        ], 200);
    }

    public function editSupply($id, Request $request): JsonResponse
    {
        $request->validate([
            'name'              => 'required',
            'manufacturer'      => 'required',
            'operation_type'    => 'required',
            'measure_unit'      => 'required',
            'description'       => 'nullable',
            'active'            => 'required|boolean',
        ]);
        try {
            $supply = Supply::find($id);
            $supply->update([
                'name' => $request->name,
                'manufacturer' => $request->manufacturer,
                'operation_type' => $request->operation_type,
                'measure_unit' => $request->measure_unit,
                'description' => $request->description,
                'active' => $request->active,
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Sredstvo s ovim nazivom, proizvođačem i procesom već postoji.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Promjene su uspješno spremljene.'
        ], 200);
    }

    public function deleteSupply($id): JsonResponse
    {
        try {
            Supply::destroy($id);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Nije moguće obrisati odabrano sredstvo'
            ], 422);
        }
        return response()->json([
            'success' => 'Sredstvo je uspješno obrisano!'
        ], 200);
    }
}
