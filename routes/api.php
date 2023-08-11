<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('stories', [\App\Http\Controllers\Api\StoryController::class, 'index']);
Route::get('stories/{id}', [\App\Http\Controllers\Api\StoryController::class, 'show']);
Route::post('stories', [\App\Http\Controllers\Api\StoryController::class, 'store']);
Route::put('stories/{id}', [\App\Http\Controllers\Api\StoryController::class, 'update']);
Route::delete('stories/{id}', [\App\Http\Controllers\Api\StoryController::class, 'destroy']);


Route::get('texts', [\App\Http\Controllers\Api\TextController::class, 'index']);
Route::post('texts', [\App\Http\Controllers\Api\TextController::class, 'store']);
Route::get('texts/{id}', [\App\Http\Controllers\Api\TextController::class, 'show']);
Route::put('texts/{id}', [\App\Http\Controllers\Api\TextController::class, 'update']);
Route::delete('texts/{id}', [\App\Http\Controllers\Api\TextController::class, 'destroy']);


