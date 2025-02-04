<?php

use App\Http\Controllers\InventoryTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecializationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\UserController;
use App\Models\Inventory;

Route::controller(AuthController::class)->group(function (){

    Route::get('/roles'          , 'roles');
    Route::post('/login'         , 'login');
    Route::post('/register'      , 'register');

    Route::post('/sendOTP'       , 'sendOTP');
    Route::post('/verifyOTP'     , 'verifyOTP');
    Route::post('/newPassword'   , 'newPassword');
});


Route::group(['middleware' => ['auth:api']], function () {

    Route::controller(AuthController::class)->group(function(){
        Route::get('/profile'        , 'profile');
        Route::post('/profile/update', 'update_profile');

        Route::post('/logout'        , 'logout');
    });

    Route::controller(UserController::class)->group(function(){
        Route::get('/user/lists'         , 'index');
        Route::post('/user/create'       , 'create');
        Route::get('/user/{id}/edit'     , 'getById');
        Route::post('/user/{id}/update'  , 'update');
        Route::delete('/user/{id}/delete', 'delete');
        Route::get('/user/search'        , 'search');
    });

    Route::controller(PatientController::class)->group(function(){
        Route::get('/patient/lists'         , 'index');
        Route::post('/patient/create'       , 'create');
        Route::get('/patient/{id}/edit'     , 'getById');
        Route::post('/patient/{id}/update'  , 'update');
        Route::delete('/patient/{id}/delete', 'delete');
        Route::get('/patient/search'        , 'search');
    });
    Route::controller(RoleController::class)->group(function(){
        Route::get('/roles/lists'         , 'index');
        Route::post('/roles/create'       , 'store');
        Route::get('/roles/{id}/edit'     , 'show');
        Route::post('/roles/{id}/update'  , 'update');
        Route::delete('/roles/{id}/delete', 'destroy');
        Route::get('/roles/search'        , 'search');
    });
    Route::controller(SpecializationController::class)->group(function(){
        Route::get('/specialization/lists'         , 'index');
        Route::post('/specialization/create'       , 'store');
        Route::get('/specialization/{id}/edit'     , 'show');
        Route::post('/specialization/{id}/update'  , 'update');
        Route::delete('/specialization/{id}/delete', 'destroy');
        Route::get('/specialization/search'        , 'search');
    });
    Route::controller(InventoryTypeController::class)->group(function(){
        Route::get('/inventory-types/lists'         , 'index');
        Route::post('/inventory-types/create'       , 'store');
        Route::get('/inventory-types/{id}/edit'     , 'show');
        Route::post('/inventory-types/{id}/update'  , 'update');
        Route::delete('/inventory-types/{id}/delete', 'destroy');
        Route::get('/inventory-types/search'        , 'search');
    });
    Route::controller(DoctorController::class)->group(function () {
        Route::get('/doctors/lists', 'index');
        Route::post('/doctors/create', 'store');
        Route::get('/doctors/{id}/edit', 'show');
        Route::put('/doctors/{id}/update', 'update');
        Route::delete('/doctors/{id}/delete', 'destroy');
        Route::get('/doctors/search', 'search');
    });

    Route::controller(InventoryController::class)->group(function(){
        Route::get('/inventory-item/lists'         , 'index');
        Route::get('/inventory-item/lists-type'    , 'listByType');
        // Route::post('/inventory-item/create'       , 'create');
        // Route::get('/inventory-item/{id}/edit'     , 'getById');
        // Route::post('/inventory-item/{id}/update'  , 'update');
        // Route::delete('/inventory-item/{id}/delete', 'delete');
        // Route::get('/inventory-item/search'        , 'search');

    });

    Route::controller(MedicalRecordController::class)->group(function(){
        Route::get('/medical-record/lists'               , 'index');
        Route::get('/medical-record/lists-patient'       , 'listByPatient');
        Route::get('/medical-record/lists-patient/doctor', 'listPatientByDoctor');
        Route::post('/medical-record/create'             , 'create');
        Route::get('/medical-record/{id}/edit'     , 'getById');
        Route::post('/medical-record/{id}/update'  , 'update');
        Route::delete('/medical-record/{id}/delete', 'delete');
        Route::get('/medical-record/search'        , 'search');

    });

    Route::controller(PrintController::class)->group(function(){
        Route::get('/medical-record/{prescription_id}/print-prescription' , 'printPrescription');

    });

});


