<?php

namespace App\Http\Controllers\FamilyFarm;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use App\Models\FamilyFarm\CadastralParcel;
use App\Models\FamilyFarm\Matrix;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatrixController extends Controller
{
    public function getAvailableMatrices()
    {
        $familyFarm = FamilyFarm::find(session('familyFarm')['id']);
        if($familyFarm){
            return $familyFarm->matrices()->orderBy('name')->get()->toArray();
        }else{
            return [];
        }
    }

    public function setMatrix($id)
    {
        session()->forget('matrix');
        $matrix = Matrix::find($id);
        if($matrix && in_array($id, array_column($this->getAvailableMatrices(), 'id'))){
            session()->put('matrix', $matrix);
            return response($matrix);
        }else{
            return response(false, 422);
        }
    }

    public function createMatrix(Request $request): JsonResponse
    {
        $request->validate([
            'name'                  => 'required',
            'culture_id'            => 'required',
            'cadastral_parcel_id'   => 'required',
            'area'                  => 'required|numeric|min:0|not_in:0',
        ]);
        $cadastral_parcel = CadastralParcel::find($request->cadastral_parcel_id);
        if($cadastral_parcel->area < $request->area){
            return response()->json([
                'errors' => [
                    'cadastral_parcel_id' => [null],
                    'area' => [null],
                ],
                'message' => 'Površina za obradu ne može biti veća od površine odabrane katastarske čestice.'
            ], 422);
        }
        try {
            Matrix::create([
                'name' => $request->name,
                'culture_id' => $request->culture_id,
                'cadastral_parcel_id' => $request->cadastral_parcel_id,
                'area' => $request->area,
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Matrica s ovim nazivom, kulturom i katastarskom česticom već postoji.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Nova matrica je uspješno kreirana.'
        ], 200);
    }
}
