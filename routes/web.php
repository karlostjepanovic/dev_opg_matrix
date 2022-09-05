<?php

use App\Http\Controllers\App\CultureController;
use App\Http\Controllers\App\FamilyFarmController;
use App\Http\Controllers\App\SupplyController;
use App\Http\Controllers\App\SupplyCultureController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FamilyFarm\AmountController;
use App\Http\Controllers\FamilyFarm\CadastralParcelController;
use App\Http\Controllers\FamilyFarm\EmployeeController;
use App\Http\Controllers\FamilyFarm\FamilyFarmCultureController;
use App\Http\Controllers\FamilyFarm\FamilyFarmReportsController;
use App\Http\Controllers\FamilyFarm\FamilyFarmSupplyController;
use App\Http\Controllers\FamilyFarm\Matrix\MatrixReportsController;
use App\Http\Controllers\FamilyFarm\Matrix\NoteController;
use App\Http\Controllers\FamilyFarm\Matrix\OperationController;
use App\Http\Controllers\FamilyFarm\Matrix\ProcessController;
use App\Http\Controllers\FamilyFarm\MatrixController;
use App\Http\Controllers\Token\TokenController;
use App\Models\App\Culture;
use App\Models\App\FamilyFarm;
use App\Models\App\Supply;
use App\Models\App\User;
use App\Models\FamilyFarm\FamilyFarmSupply;
use App\Models\FamilyFarm\Matrix;
use App\Models\FamilyFarm\Matrix\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * Za OPG-ove treba napraviti da se modu dodavati sredstva i unositi zalihe koje su nabavljene.
 * Kod dodavanja procesa za operaciju napraviti da se dinamički mogu odabrati zalihe koje su korištene
 * */

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/new-family-farm', function (){
    return view('new_family_farm');
});
Route::post('/new-family-farm', function (Request $request){
    $request->validate([
        'name'  => 'required',
        'email' => 'nullable|required_without_all:phone,""|email',
        'phone' => 'nullable|required_without_all:email,""',
    ]);
    $details = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ];
    \Illuminate\Support\Facades\Mail::to('karlo.stjepanovic3@gmail.com')->send(new \App\Mail\NewFamilyFarmMail($details));
    if(\Illuminate\Support\Facades\Mail::failures()){
        return back()->with('error', 'Dogodila se greška.');
    }
    return back()->with('success', 'Upit je uspješno poslan! Očekujte odgovor u najkraćem mogućem roku.');
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('{any}', function () {
        return view('vue');
    })->where('any', '.*');

    Route::post('/logout', [AuthController::class, 'logout']);

    /* KORISNIČKE POSTAVKE */
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/change-password', [UserController::class, 'changePassword']);

    /* GLOBALNI ENTITETI  */
    // PRIJAVLJENI KORISNIK
    Route::post('/logged-user', function () {
        return Auth::user();
    });
    Route::post('/search-user/username', function (Request $request){
        $request->validate([
            'username' => 'required',
        ]);
        return User::where('username', '=', $request->username)->get()->first();
    });

    // OPG
    Route::post('/set-family-farm/{id}', [FamilyFarmController::class, 'setFamilyFarm']);
    Route::post('/family-farm', function () {
        return FamilyFarm::where('id', '=', session('familyFarm') ? session('familyFarm')['id'] : null)->get()->first();
    });
    Route::post('/get-available-family-farms', [FamilyFarmController::class, 'getAvailableFamilyFarms']);

    // MATRICA
    Route::post('/set-matrix/{id}', [MatrixController::class, 'setMatrix']);
    Route::post('/matrix', function () {
        return Matrix::where('id', '=', session('matrix') ? session('matrix')['id'] : null)->get()->first();
    });
    Route::post('/get-available-matrices', [MatrixController::class, 'getAvailableMatrices']);

    /* JEDNOKRATNE LOZINKE */
    Route::group(["prefix" => "otp"], function() {
        Route::post('verify', [TokenController::class, 'verify']);
        Route::post('generate', [TokenController::class, 'generate']);
        Route::post('check', [TokenController::class, 'check']);
    });

    /* ADMINISTRACIJA SUSTAVA */
    Route::group(["prefix" => "admin"], function() {
        // OPG-OVI
        Route::post('get-family-farms', function () {
            return FamilyFarm::orderBy('name')->get()->toArray();
        });
        Route::post('create-family-farm', [FamilyFarmController::class, 'createFamilyFarm']);
        Route::post('edit-family-farm/{id}', [FamilyFarmController::class, 'editFamilyFarm']);
        Route::post('delete-family-farm/{id}', [FamilyFarmController::class, 'deleteFamilyFarm']);

        // KORISNICI
        Route::post('get-users', function () {
            return User::orderBy('lastname')->orderBy('firstname')->orderBy('username')->get()->toArray();
        });
        Route::post('create-user', [UserController::class, 'createUser']);
        Route::post('edit-user/{id}', [UserController::class, 'editUser']);
        Route::post('delete-user/{id}', [UserController::class, 'deleteUser']);
        Route::post('reset-password/{id}', [UserController::class, 'resetPassword']);
        Route::post('set-token/{id}', [UserController::class, 'setToken']);
        Route::post('remove-token/{id}', [UserController::class, 'removeToken']);

        // KULTURE
        Route::post('get-cultures', function () {
            return Culture::orderBy('name')->get()->toArray();
        });
        Route::post('get-available-cultures', [CultureController::class, 'getAvailableCultures']);
        Route::post('create-culture', [CultureController::class, 'createCulture']);
        Route::post('edit-culture/{id}', [CultureController::class, 'editCulture']);
        Route::post('delete-culture/{id}', [CultureController::class, 'deleteCulture']);

        // SREDSTVA
        Route::post('get-supplies', function () {
            return Supply::orderBy('name')->with('cultures', function($query){
                return $query->orderBy('name');
            })->get()->toArray();
        });
        Route::post('get-available-supplies', [SupplyController::class, 'getAvailableSupplies']);
        Route::post('create-supply', [SupplyController::class, 'createSupply']);
        Route::post('supply/{id}/add-culture', [SupplyCultureController::class, 'createSupplyCulture']);
        Route::post('supply/remove-culture/{id}', [SupplyCultureController::class, 'deleteSupplyCulture']);
        Route::post('edit-supply/{id}', [SupplyController::class, 'editSupply']);
        Route::post('delete-supply/{id}', [SupplyController::class, 'deleteSupply']);
    });

    /* OPG */
    Route::group(["prefix" => "family-farm"], function() {
        // MATRICE
        Route::post('create-matrix', [MatrixController::class, 'createMatrix']);
        Route::post('lock-matrix', [MatrixController::class, 'lockMatrix']);
        Route::post('delete-matrix', [MatrixController::class, 'deleteMatrix']);
        Route::group(["prefix" => "matrix"], function() {
            // OPERACIJE
            Route::post('get-operations', function () {
                return Matrix::find(session('matrix')['id'])
                    ->operations()
                    ->orderBy('ordinal_number', 'desc')
                    ->with('processes', function ($query){
                        return $query->with('processAmounts', function ($query){
                            return $query->with('amount', function ($query){
                                return $query->with('familyFarmSupply', function ($query){
                                    return $query->with('supply');
                                });
                            });
                        });
                    })
                    ->get()->toArray();
            });
            Route::post('create-operation', [OperationController::class, 'createOperation']);
            Route::post('edit-operation/{id}', [OperationController::class, 'editOperation']);
            Route::post('end-operation/{id}', [OperationController::class, 'endOperation']);
            Route::post('delete-operation/{id}', [OperationController::class, 'deleteOperation']);
            Route::post('move-operation/{id}/{direction}', [OperationController::class, 'moveOperation']);
            Route::group(["prefix" => "operation"], function() {
                Route::post('{id}/show', function ($id) {
                    return Operation::where('id', $id)->where('matrix_id', session('matrix')['id'])->get()->first();
                });
                Route::post('{id}/get-available-supplies', [ProcessController::class, 'getAvailableSupplies']);
                Route::post('{id}/create-process', [ProcessController::class, 'createProcess']);
                Route::post('delete-process/{id}', [ProcessController::class, 'deleteProcess']);
            });

            // BILJEŠKE
            Route::post('get-notes', function () {
                return Matrix::find(session('matrix')['id'])->notes()->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get()->toArray();
            });
            Route::post('create-note', [NoteController::class, 'createNote']);
            Route::post('edit-note/{id}', [NoteController::class, 'editNote']);
            Route::post('delete-note/{id}', [NoteController::class, 'deleteNote']);

            // IZVJEŠTAJI
            Route::group(["prefix" => "reports"], function() {
                Route::post('get-income', [MatrixReportsController::class, 'getIncome']);
            });
        });

        // DJELATNICI
        Route::post('get-employees', function () {
            return FamilyFarm::find(session('familyFarm')['id'])->employees()->orderBy('lastname')->orderBy('firstname')->orderBy('username')->get()->toArray();
        });
        Route::post('add-employee', [EmployeeController::class, 'addEmployee']);
        Route::post('edit-employee/{id}', [EmployeeController::class, 'editEmployee']);
        Route::post('remove-employee/{id}', [EmployeeController::class, 'removeEmployee']);

        // KATASTARSKE ČESTICE
        Route::post('get-cadastral-parcels', function () {
            return FamilyFarm::find(session('familyFarm')['id'])->cadastralParcels()->orderBy('number')->get()->toArray();
        });
        Route::post('create-cadastral-parcel', [CadastralParcelController::class, 'createCadastralParcel']);
        Route::post('edit-cadastral-parcel/{id}', [CadastralParcelController::class, 'editCadastralParcel']);
        Route::post('delete-cadastral-parcel/{id}', [CadastralParcelController::class, 'deleteCadastralParcel']);

        // KULTURE
        Route::post('get-cultures', function () {
            return FamilyFarm::find(session('familyFarm')['id'])->cultures()->orderBy('name')->get()->toArray();
        });
        Route::post('add-culture', [FamilyFarmCultureController::class, 'addCulture']);
        Route::post('remove-culture/{id}', [FamilyFarmCultureController::class, 'removeCulture']);

        // SREDSTVA I ZALIHE
        Route::post('get-supplies', function () {
            return FamilyFarm::find(session('familyFarm')['id'])->familyFarmSupplies()->get()->toArray();
        });
        Route::post('add-supply', [FamilyFarmSupplyController::class, 'addSupply']);
        Route::post('remove-supply/{id}', [FamilyFarmSupplyController::class, 'removeSupply']);
        Route::group(["prefix" => "supply"], function() {
            Route::post('{id}/show', function ($id) {
                return FamilyFarmSupply::where('id', $id)->where('family_farm_id', session('familyFarm')['id'])->with('supply', 'amounts')->get()->first();
            });
            Route::post('{id}/get-amounts', function ($id) {
                return FamilyFarmSupply::find($id)->amounts()->orderBy('created_at', 'desc')->get()->toArray();
            });
            Route::post('{id}/create-amount', [AmountController::class, 'createAmount']);
            Route::post('edit-amount/{id}', [AmountController::class, 'editAmount']);
            Route::post('delete-amount/{id}', [AmountController::class, 'deleteAmount']);
        });

        // IZVJEŠTAJI
        Route::group(["prefix" => "reports"], function() {
            Route::post('generate', [FamilyFarmReportsController::class, 'generate']);
        });
    });
});
