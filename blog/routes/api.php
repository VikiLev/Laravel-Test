<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
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
//
Route::resource('tasks', TaskController::class);
//Route::resource('tasks', TaskController::class)->middleware('auth:api');
//Route::put('tasks/{task}/complete', [TaskController::class, 'markComplete'])->middleware('auth:api');
//
//Route::middleware(['auth:sanctum'])->group(function () {
//    Route::resource('tasks', TaskController::class);
//    Route::put('tasks/{task}/complete', [TaskController::class, 'markComplete']);
//});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



