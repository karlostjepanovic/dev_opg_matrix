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
     * Createing culture
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
     * Editing culture
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
     * Deleting culture
     * @param $id
     * @return JsonResponse
     */
    public function deleteCulture($id): JsonResponse
    {
        try {
            Culture::destroy($id);
        }catch (QueryException $e){
            // TODO: ispitati koji je kôd za onemogućavanje brisanja
            if($e->getCode() === ""){
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
