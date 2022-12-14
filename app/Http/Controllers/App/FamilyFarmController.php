<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FamilyFarmController extends Controller
{
    /**
     * Dohvaćanje dostupnih OPG-ova
     * @return array
     */
    public function getAvailableFamilyFarms(): array
    {
        $user = Auth::user();
        return FamilyFarm::with('owner')
            ->where('active', '=', true)
            ->where(function($query) use ($user) {
                return $query->where('owner_id', '=', $user->id)
                    ->orWhereIn('id', array_column($user->familyFarms()->get()->toArray(), 'id'));
            })
            ->orderBy('name')->get()->toArray();
    }

    /**
     * Kreiranje OPG-a
     * @param Request $request
     * @return JsonResponse
     */
    public function createFamilyFarm(Request $request): JsonResponse
    {
        $request->validate([
            'name'          => 'required',
            'address'       => 'required',
            'place'         => 'required',
            'email'         => 'nullable|email',
            'phone'         => 'nullable',
            'website'       => 'nullable',
            'oib'           => 'required|numeric|digits:11',
            'owner'         => 'required'
        ]);
        try {
            FamilyFarm::create([
                'name' => $request->name,
                'address' => $request->address,
                'place' => $request->place,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'oib' => $request->oib,
                'owner_id' => $request->owner
            ]);
        }catch (QueryException $e){
            if($e->getCode() === "23000"){
                return response()->json([
                    'message' => 'OPG s ovim OIB-om već postoji.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Novi OPG je uspješno kreiran.'
        ], 200);
    }

    /**
     * Uređivanje OPG-a
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function editFamilyFarm($id, Request $request): JsonResponse
    {
        $request->validate([
            'name'          => 'required',
            'address'       => 'required',
            'place'         => 'required',
            'email'         => 'nullable|email',
            'phone'         => 'nullable',
            'website'       => 'nullable',
            'oib'           => 'required|numeric|digits:11',
            'active'        => 'required|boolean',
            'owner'         => 'required'
        ]);
        try {
            $family_farm = FamilyFarm::find($id);
            $family_farm->update([
                'name' => $request->name,
                'address' => $request->address,
                'place' => $request->place,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'oib' => $request->oib,
                'active' => $request->active,
                'owner_id' => $request->owner
            ]);
        }catch (QueryException $e){
            if($e->getCode() === "23000"){
                return response()->json([
                    'message' => 'OPG s ovim OIB-om već postoji.'
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
     * Brisanje OPG-a
     * @param $id
     * @return JsonResponse
     */
    public function deleteFamilyFarm($id): JsonResponse
    {
        try {
            FamilyFarm::destroy($id);
        }catch (QueryException $e){
            if($e->getCode() === "23000"){
                return response()->json([
                    'message' => 'Nije moguće obrisati odabrani OPG.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'OPG je uspješno obrisan!'
        ], 200);
    }

    /**
     * Odabir OPG-a i postavljanje njegovog ID-a u sesiju
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function setFamilyFarm($id)
    {
        session()->forget('familyFarm');
        session()->forget('matrix');
        $family_farm = FamilyFarm::with('owner')->find($id);
        $user = Auth::user();
        if($family_farm && ($family_farm->active || $user->admin_role) && (in_array($id, array_column((array)$this->getAvailableFamilyFarms(), 'id')) || $user->admin_role)){
            session()->put('familyFarm', $family_farm);
            return response($family_farm);
        }else{
            return response(false, 422);
        }
    }
}
