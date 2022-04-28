<?php

namespace App\Http\Controllers\FamilyFarm;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use App\Models\FamilyFarm\CadastralParcel;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CadastralParcelController extends Controller
{
    public function createCadastralParcel(Request $request)
    {
        $this->middleware('family_farm');
        $request->validate([
            'number'            => 'required',
            'arcode'            => 'required',
            'area'              => 'required|numeric|gt:0',
            'community'         => 'required',
        ]);
        try {
            CadastralParcel::create([
                'number' => $request->number,
                'arcode' => $request->arcode,
                'area' => $request->area,
                'community' => $request->community,
                'family_farm_id' => session('familyFarm')['id']
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Katastarska čestica s ovim arkôdom ili brojem u katastarskoj općini već postoji.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Nova katastarska čestica je uspješno spremljena.'
        ], 200);
    }

    public function editCadastralParcel($id, Request $request)
    {
        if(!FamilyFarm::find(session('familyFarm')['id'])->cadastralParcels->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        $request->validate([
            'number'            => 'required',
            'arcode'            => 'required',
            'area'              => 'required|numeric|gt:0',
            'community'         => 'required',
        ]);
        try {
            $cadastral_parcel = CadastralParcel::find($id);
            $cadastral_parcel->update([
                'number' => $request->number,
                'arcode' => $request->arcode,
                'area' => $request->area,
                'community' => $request->community,
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Katastarska čestica s ovim arkôdom ili brojem u katastarskoj općini već postoji.'
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

    public function deleteCadastralParcel($id): JsonResponse
    {
        if(!FamilyFarm::find(session('familyFarm')['id'])->cadastralParcels->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            CadastralParcel::destroy($id);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Nije moguće obrisati odabranu katastarsku česticu.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Katastarska čestica je uspješno obrisana!'
        ], 200);
    }
}
