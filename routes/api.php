<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/me', [ApiController::class, 'me']);
Route::middleware('auth:sanctum')->post('auth/logout', [ApiController::class, 'logout']);

//task
Route::middleware('auth:sanctum')->group( function () {

    Route::post('/store', [TasksController::class, 'store']);
    Route::get('tasks', [TasksController::class, 'tasksByUser']);
    Route::delete('delete/{id}', [TasksController::class, 'destroy']);

    Route::put('update/{id}', [TasksController::class, 'update']);
    Route::get('complete/{id}', [TasksController::class, 'complete']);


    //Route::put('/{id}', [ApiController::class, 'update']);

});
