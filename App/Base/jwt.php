<?php

namespace App\Base;

class JWT
{
    public static function encode($data)
    {
        $options = array_merge([
            "iat" => time(),
            "exp" => 30 * 24 * 60, // 30 days
            "jti" => bin2hex(random_bytes(32))
        ], $data);
        $options["exp"] = time() + $options["exp"] * 60;
        $header = base64_encode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));
        $payload = base64_encode(json_encode($options));
        $signature = hash_hmac('sha256', $header . $payload, env['APP_KEY']);
        return $header . "." . $payload . "." . $signature;
    }
    public static function verify($token)
    {
        $token = explode(".", $token);
        $header = base64_decode($token[0]);
        $payload = base64_decode($token[1]);
        $signature = hash_hmac('sha256', base64_encode($header) . base64_encode($payload), env['APP_KEY']);
        if ($signature != $token[2]) {
            return [
                'error' => 'Invalid JWT token!'
            ];
        }
        $payload = json_decode($payload, true);
        if (!isset($payload['exp'])) {
            return [
                'error' => 'Invalid JWT token!'
            ];
        }
        if ($payload['exp'] < time()) {
            return [
                'error' => 'Invalid JWT token!'
            ];
        }
        return $payload;
    }
}
