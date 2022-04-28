<?php

namespace App\Http\Controllers\FamilyFarm\Matrix;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use App\Models\FamilyFarm\Matrix\Operation;
use Illuminate\Http\JsonResponse;

class MatrixReportsController extends Controller
{
    public function getIncome(): JsonResponse
    {
        if(!FamilyFarm::find(session('familyFarm')['id'])->matrices->contains(session('matrix')['id'])){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json(Operation::where('matrix_id', session('matrix')['id'])->where('ended', true)->get()->toArray());
    }
}
