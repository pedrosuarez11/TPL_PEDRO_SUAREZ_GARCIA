<?php

require_once "./config/config.php";

function base64url_encode($data){
    return rtrim(strtr(base64_encode($data), "+/", "-_"),"=");
}

class AuthApiHelper{

    public static function getAuthHeaders(){
        $headers = "";
        if(isset($_SERVER["HTTP_AUTHORIZATION"])){
            $headers = $_SERVER["HTTP_AUTHORIZATION"];
        }
        if(isset($_SERVER["REDIRECT_HTTP_AUTHORIZATION"])){
            $headers = $_SERVER["REDIRECT_HTTP_AUTHORIZATION"];
        }

        return $headers;
    }

    private static function sign($header, $payload){
        return base64url_encode(hash_hmac("SHA256","$header.$payload", JWT_KEY, true));
    }

    public static function createToken($payload){
        //header.payload.signature
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        $header = base64url_encode(json_encode($header));
        $payload = base64url_encode(json_encode($payload));

        $signature = AuthApiHelper::sign($header, $payload);

        $token = "$header.$payload.$signature";
        return $token;
    }

    public static function verify(){

        //"Authorization: Bearer token";
        $auth = AuthApiHelper::getAuthHeaders();

        if(empty($auth)){
            return false;
        }
        
        //"Authorization: Bearer token";
        $auth = explode(" ", $auth); // ["Bearer", "token"]

        // Basic
        if ($auth[0] != "Bearer"){
            return false;
        };
        
        if (!isset($auth[1]) || empty($auth[1])){
            return false;
        };
        

        $token = $auth[1]; // "header.payload.signature"
        $token = explode(".", $token); // [header, payload, signature]

        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        $new_signature = AuthApiHelper::sign($header, $payload);
        
        if($new_signature != $signature){
            return false;
        }

        return json_decode(base64_decode($payload));
    }

    public static function getCurrentUser(){
        return AuthApiHelper::verify();
    }


}