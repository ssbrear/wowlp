<?php

namespace App\Http\Controllers;

use App\Models\Battletag;
use Illuminate\Http\Request;

class AccessTokenController extends Controller
{
    public function getAccessToken($code) {
        $postFields = [
            'redirect_uri' => 'http://localhost:8000/redirect',
            'grant_type' => 'authorization_code',
            'code' => $code,
            'scope' => 'openid',
        ];
        $curl_handle = curl_init();
        try {
            curl_setopt($curl_handle, CURLOPT_URL, 'https://oauth.battle.net/token');
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
                'Content-Type: multipart/form-data',
                'Authorization: Basic '.base64_encode($_ENV["CLIENT_ID"] . ':' . $_ENV["CLIENT_SECRET"])
            ]);
            
            $response = curl_exec($curl_handle);
            $status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

            if ($status !== 200) {
                dd($response);
            }

            $access_token = json_decode($response)->access_token;

            return $this->getUserInfo($access_token);
        } finally {
            curl_close($curl_handle);
        }
    }
    public function getUserInfo($access_token) {
        $curl_handle = curl_init();
        try {
            curl_setopt($curl_handle, CURLOPT_URL, 'https://oauth.battle.net/userinfo?access_token='.$access_token);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl_handle);
            $status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

            if ($status !== 200) {
                throw new \Exception;
            }
            
            $battletag = json_decode($response)->battletag;

            $battletagQuery = Battletag::where("battletag", $battletag);
            if (!$battletagQuery->exists()) {
                $newBattletag = BattleTag::create(["battletag" => $battletag]);
                $newBattletag->save();
            } else {
                $battletagQuery->first()->update();
            }
            return redirect()->route('welcome', ["battletag" => $battletag]);
            
        } finally {
            curl_close($curl_handle);
        }
    }
}
