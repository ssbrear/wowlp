<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessTokenController extends Controller
{
    public function getUserInfo(Request $request) {
        // Non-secure solution. Replace this with server-side caching/cookies
        return redirect()->route('welcome', ["access-token" => $request->get('access_token')]);
    }
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
                throw new \Exception;
            }

            $token = json_decode($response);
            $requestToken = new Request((array)$token);

            return $this->getUserInfo($requestToken);
        } finally {
            curl_close($curl_handle);
        }
    }
}
