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
    public function getAvailableMatrices(): array
    {
        $familyFarm = FamilyFarm::find(session('familyFarm') ? session('familyFarm')['id'] : null);
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
            'name'              => 'required',
            'culture'           => 'required',
            'cadastral_parcel'  => 'required',
            'tracking_type'     => 'required',
        ]);
        if(!FamilyFarm::find(session('familyFarm')['id'])->cadastralParcels->contains($request->cadastral_parcel)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            Matrix::create([
                'name' => $request->name,
                'culture_id' => $request->culture,
                'cadastral_parcel_id' => $request->cadastral_parcel,
                'tracking_type' => $request->tracking_type,
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

    public function lockMatrix(): JsonResponse
    {
        if(!FamilyFarm::find(session('familyFarm')['id'])->matrices->contains(session('matrix')['id'])){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            $matrix = Matrix::find(session('matrix')['id']);
            $matrix->update([
                'locked' => !$matrix->locked
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => $matrix->locked ? 'Matrica je uspješno zaključana.' : 'Matrica je uspješno otključana'
        ], 200);
    }

    public function deleteMatrix(): JsonResponse
    {
        if(!FamilyFarm::find(session('familyFarm')['id'])->matrices->contains(session('matrix')['id'])){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            Matrix::destroy(session('matrix')['id']);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Nije moguće obrisati odabranu matricu'
            ], 422);
        }
        return response()->json([
            'success' => 'Matrica je uspješno obrisana!'
        ], 200);
    }
}
