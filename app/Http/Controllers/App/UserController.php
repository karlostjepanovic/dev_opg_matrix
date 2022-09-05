<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Mail\UserPasswordEmail;
use App\Models\App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use function response;

class UserController extends Controller
{
    /**
     * Kreiranje korisnika
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse
    {
        $request->validate([
            'firstname'     => 'required',
            'lastname'      => 'required',
            'username'      => 'required',
            'email'         => 'required|email',
            'phone'         => 'nullable',
        ]);
        try {
            $password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),1, 6);
            User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'password' => Hash::make($password),
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            $details = [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'password' => $password,
            ];
            Mail::to($request->email)->send(new UserPasswordEmail($details));
            if(Mail::failures()){
                return response()->json([
                    'message' => 'Unesena adresa e-pošte je nevažeća.'
                ], 422);
            }
        }catch (QueryException $e){
            if($e->getCode() === "23000"){
                return response()->json([
                    'message' => 'Korisnik s ovim korisničkim imenom ili adresom e-pošte već postoji.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Novi korisnik je uspješno spremljen.'
        ], 200);
    }

    /**
     * Uređivanje korisnika
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
            'email'         => 'required|email',
            'phone'         => 'nullable',
        ]);
        try {
            $user = User::find($id);
            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }catch (QueryException $e){
            if($e->getCode() === "23000"){
                return response()->json([
                    'message' => 'Korisnik s ovim korisničkim imenom ili adresom e-pošte već postoji.'
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
     * Postavljanje tokena
     * @param $id
     * @param Request $request
     * @return Application|ResponseFactory|Response|JsonResponse
     */
    public function setToken($id, Request $request)
    {
        $request->validate([
            'code'  => 'required',
        ]);
        try {
            $user = User::find($id);
            $user->update([
                'otp_token' => $request->code,
                'admin_role' => true
            ]);
            return response()->json([
                'success' => 'Radnja je uspješno obavljena.'
            ]);
        }catch (QueryException $e){
            return response(false, 422);
        }
    }

    /**
     * Uklanjanje tokena
     * @param $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function removeToken($id)
    {
        if(Auth::id() == $id){
            return response()->json([
                'message' => 'Ne možete ukloniti token za vlastiti korisnički račun.'
            ], 422);
        }
        try {
            $user = User::find($id);
            $user->update([
                'otp_token' => null,
                'last_otp' => null,
                'admin_role' => false
            ]);
            return response()->json([
                'success' => 'Radnja je uspješno obavljena.'
            ]);
        }catch (QueryException $e){
            return response(false, 422);
        }
    }

    /**
     * Resetiranje lozinke
     * @param $id
     * @return JsonResponse
     */
    public function resetPassword($id): JsonResponse
    {
        $password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),1, 6);
        $user = User::find($id);
        $user->update([
            'password' => Hash::make($password)
        ]);
        $details = [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'username' => $user->username,
            'password' => $password,
        ];
        Mail::to($user->email)->send(new UserPasswordEmail($details));
        if(Mail::failures()){
            return response()->json([
                'message' => 'Unesena adresa e-pošte je nevažeća.'
            ], 422);
        }
        return response()->json([
            'success' => 'Korisniku je lozinka uspješno resetirana.'
        ]);
    }

    /**
     * Promjena lozinke
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password'   => 'required|password',
            'new_password'       => 'required|min:6',
            'repeat_password'    => 'required|same:new_password'
        ]);

        $user = User::find(Auth::id());
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        return response()->json([
            'success' => 'Lozinka je uspješno promijenjena.'
        ], 200);
    }

    /**
     * Uređivanje profila
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'phone'                         => 'nullable',
            'font_size'                     => 'required',
        ]);
        try {
            $user = User::find(Auth::id());
            $user->update([
                'phone' => $request->phone,
                'font_size' => $request->font_size,
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Promjene su uspješno spremljene.'
        ]);
    }

    /**
     * Brisanje korisnika
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
            if($e->getCode() === "23000"){
                return response()->json([
                    'message' => 'Nije moguće obrisati odabranog korisnika.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Korisnik je uspješno obrisan.'
        ]);
    }
}
