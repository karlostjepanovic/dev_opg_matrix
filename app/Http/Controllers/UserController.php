<?php

namespace App\Http\Controllers;

use App\Models\App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create new user
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse
    {
        $request->validate([
            'firstname'     => 'required',
            'lastname'      => 'required',
            'username'      => 'required',
            'oib'           => 'required|numeric|digits:11',
            'email'         => 'nullable|email',
            'phone'         => 'nullable',
            'admin_role'    => 'bool'
        ]);
        try {
            User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'password' => Hash::make("1234"),
                'oib' => $request->oib,
                'email' => $request->email,
                'phone' => $request->phone,
                'admin_role' => $request->admin_role
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Korisnik s ovim korisničkim imenom ili OIB-om već postoji.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Novi korisnik je uspješno spremljen.'
        ], 200);
    }

    /**
     * Edit user
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function editUser($id, Request $request): JsonResponse
    {
        $request->validate([
            'firstname'     => 'required',
            'lastname'      => 'required',
            'username'      => 'required',
            'oib'           => 'required|numeric|digits:11',
            'email'         => 'nullable|email',
            'phone'         => 'nullable',
            'admin_role'    => 'bool'
        ]);
        try {
            $user = User::find($id);
            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'oib' => $request->oib,
                'email' => $request->email,
                'phone' => $request->phone,
                'admin_role' => $request->admin_role
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Korisnik s ovim korisničkim imenom ili OIB-om već postoji.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Promjene su uspješno spremljene.'
        ], 200);
    }

    /**
     * Reset users's password
     * @param $id
     * @return JsonResponse
     */
    public function resetPassword($id): JsonResponse
    {
        $user = User::find($id);
        $user->update([
            'password' => Hash::make("1234")
        ]);
        return response()->json([
            'success' => 'Korisniku je lozinka uspješno resetirana.'
        ]);
    }

    /**
     * Delete user
     * @param $id
     * @return JsonResponse
     */
    public function deleteUser($id): JsonResponse
    {
        if(Auth::id() == $id){
            return response()->json([
                'message' => 'Ne možete obrisati vlastiti korisnički račun.'
            ], 422);
        }
        try {
            User::destroy($id);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Nije moguće obrisati odabranog korisnika.'
            ], 422);
        }
        return response()->json([
            'success' => 'Korisnik je uspješno obrisan.'
        ]);
    }
}
