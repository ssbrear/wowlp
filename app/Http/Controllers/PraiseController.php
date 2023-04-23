<?php

namespace App\Http\Controllers;

use App\Models\Praise;
use App\Models\Character;
use Illuminate\Http\Request;

class PraiseController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return Praise::where("character_id", $id)->get()->toArray();
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
            'praiser_id'=>'required',
            'type'=>'required',
        ]);
        $character = Character::where("character_name_realm_name", $request->get("character_name_realm_name"))->firstOrFail();
        $praiseAttrs = [
            "praiser_id"=>$request->get("praiser_id"),
            "character_id"=>$character->id,
            "type"=>$request->get("type"),
        ];
        $praise = Praise::create($praiseAttrs);
        $praise->save();
        return $praise;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'praiser_id'=>'required',
            'character_name_realm_name'=>'required',
        ]);
        $character = Character::where("character_name_realm_name", $request->get("character_name_realm_name"))->firstOrFail();
        $praise = $character->praise()->where("praiser_id", $request->get("praiser_id"))->firstOrFail();
        $praise->delete();
        return response()->json([
            'status'=>'200',
            'message'=>'Praise successfully removed',
        ]);
    }
}
