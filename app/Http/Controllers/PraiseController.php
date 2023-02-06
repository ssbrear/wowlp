<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PraiseController extends Controller
{
    /**
     * Use authorization code to get access token for OAuth 2.0 Authorization
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function battlenetAuth($code) {
        $postFields = [
            'redirect_uri' => 'http://localhost:8000/api/battlenet/callback',
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

            return $token;
        } finally {
            curl_close($curl_handle);
        }
    }
}
