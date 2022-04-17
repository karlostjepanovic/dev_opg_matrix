<?php

namespace App\Http\Controllers\FamilyFarm;

use App\Http\Controllers\Controller;
use App\Models\App\User;
use App\Models\FamilyFarm\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function createEmployee(Request $request)
    {
        // $id je ID od korisnika (t: users)
        $request->validate([
            'user_id'       => 'required',
            'admin_role'    => 'required|bool',
        ]);
        try {
            Employee::create([
                'family_farm_id' => session('familyFarm')['id'],
                'user_id' => $request->user_id,
                'admin_role' => $request->admin_role
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Korisnik je već dodan na popis djelatnika.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Novi djelatnik je uspješno spremljen.'
        ], 200);
    }

    public function editEmployee($id, Request $request)
    {
        // $id je ID od korisnika (t: users)
        $request->validate([
            'user_id'       => 'required',
            'firstname'     => 'required',
            'lastname'      => 'required',
            'email'         => 'nullable|email',
            'phone'         => 'nullable',
            'admin_role'    => 'required|bool',
        ]);
        try {
            $user = User::find($request->user_id);
            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            $employee = Employee::find($id);
            $employee->update([
                'admin_role' => $request->admin_role,
            ]);
        }catch (QueryException $e){
            if($e->getCode() == "23000"){
                return response()->json([
                    'message' => 'Korisnik je već dodan na popis djelatnika.'
                ], 422);
            }else{
                return response()->json([
                    'message' => 'Dogodila se greška.'
                ], 422);
            }
        }
        return response()->json([
            'success' => 'Novi djelatnik je uspješno spremljen.'
        ], 200);
    }

    public function removeEmployee($id)
    {
        try {
            $employee = Employee::where('id', '=', $id)->where('family_farm_id', '=', session('familyFarm')['id']);
            $employee->delete();
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Djelatnik je uspješno uklonjen.'
        ]);
    }
}
