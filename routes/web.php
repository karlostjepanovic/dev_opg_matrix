<?php

use App\Http\Controllers\App\FamilyFarmController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Token\TokenController;
use App\Models\App\FamilyFarm;
use App\Models\App\User;
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

    // OPG
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
        /*Route::post('get-cultures', [CultureController::class, 'getCultures']);
        Route::post('get-all-cultures', [CultureController::class, 'getAllCultures']);
        Route::post('create-culture', [CultureController::class, 'createCultureAction']);
        Route::post('edit-culture/{id}', [CultureController::class, 'editCultureAction']);
        Route::post('delete-culture/{id}', [CultureController::class, 'deleteCultureAction']);

        // SREDSTVA
        Route::post('get-supplies', [SupplyController::class, 'getSupplies']);
        Route::post('get-all-supplies', [SupplyController::class, 'getAllSupplies']);
        Route::post('create-supply', [SupplyController::class, 'createSupplyAction']);
        Route::post('supply/{id}/add-culture', [SupplyController::class, 'addCultureAction']);
        Route::post('supply/{id}/remove-culture', [SupplyController::class, 'removeCultureAction']);
        Route::post('edit-supply/{id}', [SupplyController::class, 'editSupplyAction']);
        Route::post('delete-supply/{id}', [SupplyController::class, 'deleteSupplyAction']);*/
    });
});
