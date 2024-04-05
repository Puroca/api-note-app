<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// ROUTES NON PROTEGEES
Route::group([], function(){
    // ROUTES D'AUTHENTIFICATION
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);

});

// ROUTES PROTEGEES
Route::middleware('auth:sanctum')->group(function(){
    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);

    // USER
    Route::post('/user/update', [AuthController::class, 'update']);
    Route::get('/user', [AuthController::class, 'get']);

    // NOTES ROUTES
    Route::post('/note/create', [NoteController::class, 'create']);
    Route::post('/note/update/{id}', [NoteController::class, 'update']);
    Route::get('/note/get', [NoteController::class, "getAll"]);
    Route::get('/note/get/{id}', [NoteController::class, "getOne"]);
    Route::delete('/note/delete/{id}', [NoteController::class, 'delete']);
    
});
