<?php

use Illuminate\Http\Request;

use BlizzardApi\Enumerators\Region;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RealmController;
use App\Http\Controllers\CharacterController;
\BlizzardApi\Configuration::$apiKey = $_ENV["CLIENT_ID"];
\BlizzardApi\Configuration::$apiSecret = $_ENV["CLIENT_SECRET"];
\BlizzardApi\Configuration::$region = Region::US;
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

Route::get("/realms", [RealmController::class, 'index']);
Route::get('/characters', [CharacterController::class, 'index']);
Route::post('/characters', [CharacterController::class, 'store']);
Route::get('/realms/{realm_name}/characters/{character_name}', [CharacterController::class, 'show']);

Route::get('/battlenet/callback', function() {
    return view('welcome');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
