<?php

use Illuminate\Support\Facades\Route;
use BlizzardApi\Enumerators\Region;

BlizzardApi\Configuration::$apiKey = $_ENV["CLIENT_ID"];
BlizzardApi\Configuration::$apiSecret = $_ENV["CLIENT_SECRET"];
BlizzardApi\Configuration::$region = Region::US;

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

Route::get('/', function () {
    $api_client = new \BlizzardApi\Wow\GameData\Realm();
    $res = $api_client->index();
    \Log::info(print_r($res->realms, true));
    return view('welcome', compact('res'));
});
