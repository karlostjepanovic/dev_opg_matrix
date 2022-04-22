<?php

namespace App\Http\Controllers\FamilyFarm\Matrix;

use App\Http\Controllers\Controller;
use App\Models\FamilyFarm\Matrix\Operation;
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
            'tracking'          => 'required',
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
}
