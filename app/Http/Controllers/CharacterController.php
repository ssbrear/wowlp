<?php

namespace App\Http\Controllers;

use App\Models\Realm;
use App\Models\Character;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use BlizzardApi\Enumerators\Region;
\BlizzardApi\Configuration::$apiKey = $_ENV["CLIENT_ID"];
\BlizzardApi\Configuration::$apiSecret = $_ENV["CLIENT_SECRET"];
\BlizzardApi\Configuration::$region = Region::US;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Character::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'realm'=>'required',
            'realm_slug'=>'required',
            'character_name_realm_name'=>'required|unique',
            'race'=>'required',
            'class'=>'required',
            'headshot'=>'required',
        ]);

        $characterAttrs = $request->all();
        $characterAttrs->name = ucwords(strtolower($characterAttrs->name));
        $character = Character::create($characterAttrs);
        $character->save();
        return $character;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $realm_name
     * @param  string  $character_name
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $realm_name, $character_name)
    {
        $character_name = ucwords(strtolower($character_name));
        $character = Character::where("name", $character_name)->where("realm", $realm_name)->with('praises')->first();
        if (!$character) {
            $characterAttrs = $this->queryBlizzard($realm_name, $character_name);
            $characterAttrs["character_name_realm_name"] = $character_name."_".$realm_name;
            $character = Character::create($characterAttrs);
            $character->save();
        }
        $response = $character->toArray();
        $response["praised"] = false;
        if ($request->exists("praiser_id")) {
            $praiseExists = $character->praises->where("praiser_id", $request->get("praiser_id"))->isEmpty();
            if (!$praiseExists) {
                $response["praised"] = true;
            }
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

        /**
     * Query Blizzard API for character.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function queryBlizzard($realm_name, $character_name)
    {
        $realm = Realm::where("name", $realm_name)->first();
        $charProfile = new \BlizzardApi\Wow\Profile\CharacterProfile();
        $avatarLink = $charProfile->media($realm->slug, $character_name)->assets[0]->value;
        $charData = $charProfile->get($realm->slug, $character_name);
        $characterAttrs = [
            "name"        =>  $charData->name,
            "realm"       =>  $charData->realm->name->en_US,
            "realm_slug"  =>  $charData->realm->slug,
            "race"        =>  $charData->race->name->en_US,
            "class"       =>  $charData->character_class->name->en_US,
            "guild"       =>  $charData->guild->name,
            "headshot"    =>  $avatarLink,
        ];
        return $characterAttrs;
    }
}
