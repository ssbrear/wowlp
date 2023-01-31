<?php

namespace App\Http\Controllers;

use App\Models\Realm;
use App\Models\Character;
use App\Models\CharacterRealm;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'race'=>'required',
            'class'=>'required',
            'headshot'=>'required',
        ]);

        DB::transaction(function () use ($request) {
            $character = Character::create($request->all());
            $characterRealm = CharacterRealm::create([
                "character_name"             => $request->get("name"),
                "realm_name"                 => $request->get("realm"),
                "character-name_realm-name"  => $request->get("name")."_".$request->get("realm"),
            ]);
            $character->characterRealm()->save($characterRealm);
            return $character;
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($realm_name, $character_name)
    {
        $character = Character::where("name", $character_name)->where("realm", $realm_name)->first();
        if ($character === null) {
            $characterAttrs = $this->queryBlizzard($realm_name, $character_name);

            DB::transaction(function() use ($characterAttrs) {
                $character = Character::create($characterAttrs);
                $characterRealm = CharacterRealm::create([
                    "character_name"             => $character->name,
                    "realm_name"                 => $character->realm,
                    "character-name_realm-name"  => $character->name."_".$character->realm,
                ]);
                $character->characterRealm()->save($characterRealm);
            });

            return $characterAttrs;
        } else {
            return $character;
        }
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
