<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RealmController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharacterRealmController;
use BlizzardApi\Enumerators\Region;
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

Route::get("/realms", [RealmController::class, 'index'])->name("realms.index");
Route::get('/characters', [CharacterController::class, 'index'])->name("characters.index");
Route::post('/characters', [CharacterController::class, 'store'])->name("characters.store");
Route::get('/realms/{realm_name}/characters/{character_name}', [CharacterController::class, 'show'])->name("characters.show");


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
