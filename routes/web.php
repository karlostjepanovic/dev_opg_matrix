<?php

use App\Http\Controllers\App\CultureController;
use App\Http\Controllers\App\FamilyFarmController;
use App\Http\Controllers\App\SupplyController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FamilyFarm\EmployeeController;
use App\Http\Controllers\Token\TokenController;
use App\Models\App\Culture;
use App\Models\App\FamilyFarm;
use App\Models\App\Supply;
use App\Models\App\User;
use App\Models\FamilyFarm\Employee;
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

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
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
    Route::post('/search-user/oib', function (Request $request){
        $request->validate([
            'oib' => 'required',
        ]);
        return User::where('oib', '=', $request->oib)->get()->first();
    });

    // OPG
    Route::post('/set-family-farm/{id}', [FamilyFarmController::class, 'setFamilyFarm']);
    Route::post('/family-farm', function () {
        return FamilyFarm::where('id', '=', session('familyFarm')['id'])->with('owner')->get()->first();
    });
    Route::post('/get-available-family-farms', [FamilyFarmController::class, 'getAvailableFamilyFarms']);

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
            return FamilyFarm::orderBy('name')->with('owner')->get()->toArray();
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
        Route::post('create-culture', [CultureController::class, 'createCulture']);
        Route::post('edit-culture/{id}', [CultureController::class, 'editCulture']);
        Route::post('delete-culture/{id}', [CultureController::class, 'deleteCulture']);

        // SREDSTVA
        Route::post('get-supplies', function () {
            return Supply::orderBy('name')->get()->toArray();
        });
        Route::post('create-supply', [SupplyController::class, 'createSupply']);
        Route::post('supply/{id}/add-culture', [SupplyController::class, 'addCulture']);
        Route::post('supply/{id}/remove-culture', [SupplyController::class, 'removeCulture']);
        Route::post('edit-supply/{id}', [SupplyController::class, 'editSupply']);
        Route::post('delete-supply/{id}', [SupplyController::class, 'deleteSupply']);
    });

    /* OPG */
    Route::group(["prefix" => "family-farm"], function() {
        // DJELATNICI
        Route::post('get-employees', function () {
            return FamilyFarm::find(session('familyFarm')['id'])->employees()->orderBy('lastname')->orderBy('firstname')->orderBy('username')->get()->toArray();
        });
        Route::post('add-employee', [EmployeeController::class, 'createEmployee']);
        Route::post('edit-employee/{id}', [EmployeeController::class, 'editEmployee']);
        Route::post('remove-employee/{id}', [EmployeeController::class, 'removeEmployee']);
    });
});
