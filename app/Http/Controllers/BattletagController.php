<?php

namespace App\Http\Controllers;

use App\Models\Battletag;

class BattletagController extends Controller
{
    public function show($battletagStr, $battletagNum) {
        $battletag = $battletagStr."#".$battletagNum;
        $battletagQuery = Battletag::where("battletag", $battletag);
        if (!$battletagQuery->exists()) {
            return null;
        } else {
            return $battletagQuery->first()->battletag;
        }
    }
}