<?php

namespace App\Base;

class Pterodactyl
{
    public static $api_key;
    public static $api_url;
    public static function env($api_key, $api_url)
    {
        self::$api_key = $api_key;
        self::$api_url = $api_url;
    }
    public static function getUser($user_id = null)
    {
        $res = fetch::get(self::$api_url . '/api/application/users?filter[email]=' . $user_id, [
            'Authorization: Bearer ' . self::$api_key
        ]);
        return $res;
    }
    public static function createUserIfNotExists($email, $username, $first_name, $last_name, $password)
    {
        $exists = self::getUser($email);
        // if $exists['data'] length is 0, then user does not exists and its array
        if (!isset($exists)) {
            return null;
        }
        if (count($exists['data']) > 0) {
            return $exists['data'][0];
        }

        $res = fetch::post(self::$api_url . '/api/application/users', [
            'email' => $email,
            'username' => $username,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'password' => $password,
            'root_admin' => false
        ], [
            'Authorization: Bearer ' . self::$api_key
        ]);
        if ($res['status'] === 'error') {
            return $res;
        }
        return $res;
    }
}
