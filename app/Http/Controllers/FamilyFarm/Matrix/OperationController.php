<?php

namespace App\Http\Controllers\FamilyFarm\Matrix;

use App\Http\Controllers\Controller;
use App\Models\FamilyFarm\Matrix;
use App\Models\FamilyFarm\Matrix\Operation;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    public function createOperation(Request $request): JsonResponse
    {
        $request->validate([
            'operation_type'    => 'required',
            'unit_price'        => 'required|numeric|min:0|not_in:0',
            'tracking'          => 'required|numeric|min:0|not_in:0',
        ]);

        $ordinal_number = Operation::where('matrix_id', '=', session('matrix')['id'])->max('ordinal_number');
        $ordinal_number = $ordinal_number != 0 ? $ordinal_number + 1 : 1;
        try {
            Operation::create([
                'operation_type' => $request->operation_type,
                'unit_price' => $request->unit_price,
                'tracking' => $request->tracking,
                'ordinal_number' => $ordinal_number,
                'matrix_id' => session('matrix')['id'],
                'user_id' => Auth::id()
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Operacija je uspješno spremljena.'
        ], 200);
    }

    public function moveOperation($id, $direction)
    {
        if(!Matrix::find(session('matrix')['id'])->operations->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        if($direction === 'up'){
            try {
                $operation = Operation::find($id);
                $operation_order = $operation->ordinal_number;
                $replace_operation = Operation::where('ordinal_number', '>', $operation->ordinal_number)->orderBy('ordinal_number')->first();
                $replace_operation_order = $replace_operation->ordinal_number;
            }catch (QueryException $e){
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }elseif ($direction === 'down'){
            try {
                $operation = Operation::find($id);
                $operation_order = $operation->ordinal_number;
                $replace_operation = Operation::where('ordinal_number', '<', $operation->ordinal_number)->orderBy('ordinal_number', 'desc')->first();
                $replace_operation_order = $replace_operation->ordinal_number;
            }catch (Exception $e){
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }else{
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            $operation->update([
                'ordinal_number' => $replace_operation_order
            ]);
            $replace_operation->update([
                'ordinal_number' => $operation_order
            ]);
        }catch (QueryException $e){
            return response($e)->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Redoslijed je uspješno promjenjen.'
        ], 200);
    }

    public function editOperation($id, Request $request): JsonResponse
    {
        $request->validate([
            'unit_price'        => 'required|numeric|min:0|not_in:0',
            'tracking'          => 'required|numeric|min:0|not_in:0',
        ]);
        if(!Matrix::find(session('matrix')['id'])->operations->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            $operation = Operation::find($id);
            $operation->update([
                'unit_price' => $request->unit_price,
                'tracking' => $request->tracking,
                'user_id' => Auth::id()
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

    public function deleteOperation($id): JsonResponse
    {
        if(!Matrix::find(session('matrix')['id'])->operations->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            Operation::destroy($id);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Nije moguće obrisati odabranu operaciju jer postoje evidentirani procesi.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Operacija je uspješno obrisana!'
        ], 200);
    }
}
