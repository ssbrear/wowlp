<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Realm;
use Illuminate\Http\Request;

class RealmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $updatedAt = Realm::findOr(1, ['updated_at'], function() {
            return null;
        });
        $now = explode("-", Carbon::now()->toDateTimeString(), 2);
        if ($updatedAt === null || $now[0] !== "2023") {
            $api_client = new \BlizzardApi\Wow\GameData\Realm();
            $res = $api_client->index();
            $formattedData = [];
            forEach($res->realms as $realm) {
                array_push($formattedData, ["id"=>$realm->id, "name"=>$realm->name->en_US, "slug"=>$realm->slug]);
            }
            Realm::upsert($formattedData, ['id'], ['name', 'slug']);
        }
        return Realm::all();
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
            "name"=>"required|unique",
            "slug"=>"required|unique",
        ]);
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
