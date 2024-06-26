<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;




// Test server connection
Route::get('task',function(){
return 'test';
});

//============================================ Tasks Endpoints =========================================================

// GET /tasks :
Route::get('/tasks', [TaskController::class, 'index']);


// GET /tasks/{id}:
Route::get('/tasks/{id}', [TaskController::class, 'show']);


// POST /tasks/store:

// PUT /tasks/update:
Route::Put('/tasks/update/{id}', [TaskController::class, 'update']);

// Soft delete task
Route::delete('/tasks/delete/{id}', [TaskController::class, 'destroy']);


// Récupérer les tâches supprimées
Route::get('/task/deleted', [TaskController::class, 'getDeletedTasks']);

//============================================ Auth Endpoints =========================================================
// creer un compte
Route::post('/register',[UserController::class,'register']);

// s'authentifier
Route::post('/login',[UserController::class,'login']);

// LOGout

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');






//============================================ Authorizations =========================================================
Route::middleware('auth:sanctum')->group(function(){

    //Retourner L'utilisateur actuellement connecte
    Route::get('/user', function (Request $request) {
    return $request->user();
                });

    // POST /tasks/store:
    Route::post('/tasks/create', [TaskController::class, 'store']);

             
});



