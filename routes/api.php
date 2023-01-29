<?php

use App\Http\Controllers\CharacterController;

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

Route::get('/characters', [CharacterController::class, 'index'])->name("characters.get");;
Route::post('/characters', [CharacterController::class, 'store'])->name("characters.post");

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
