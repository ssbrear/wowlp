<?php

namespace App\Http\Controllers;

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
        ]);

        DB::transaction(function () use ($request) {
            $character = Character::create($request->all());
            $characterRealm = CharacterRealm::create([
                "character_name" => $request->get("name"),
                "realm_name" => $request->get("realm"),
                "character-name_realm-name" => $request->get("name")."_".$request->get("realm"),
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
    public function show($id)
    {
        //
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
}
