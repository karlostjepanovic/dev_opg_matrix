<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\Culture;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CultureController extends Controller
{
    /**
     * Popis dostupnih kultura
     * @return mixed
     */
    public function getAvailableCultures()
    {
        return Culture::where('active', true)->orderBy('name')->get()->toArray();
    }

    /**
     * Kreiranje kulture
     * @param Request $request
     * @return JsonResponse
     */
    public function createCulture(Request $request): JsonResponse
    {
        $request->validate([
            'name'    => 'required',
        ]);
        try {
            Culture::create([
                'name' => $request->name,
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Kultura s ovim nazivom već postoji.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Nova kultura je uspješno spremljena.'
        ], 200);
    }

    /**
     * Uređivanje kulture
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function editCulture($id, Request $request): JsonResponse
    {
        $request->validate([
            'name'      => 'required',
            'active'    => 'required|boolean',
        ]);
        try {
            $culture = Culture::find($id);
            $culture->update([
                'name' => $request->name,
                'active' => $request->active,
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Kultura s ovim nazivom već postoji.'
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

    /**
     * Brisanje kulture
     * @param $id
     * @return JsonResponse
     */
    public function deleteCulture($id): JsonResponse
    {
        try {
            Culture::destroy($id);
        }catch (QueryException $e){
            if($e->getCode() === "23000"){
                return response()->json([
                    'message' => 'Nije moguće obrisati odabranu kulturu'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Kultura je uspješno obrisana!'
        ], 200);
    }
}
