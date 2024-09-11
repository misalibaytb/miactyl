<?php

namespace App\Http\Controllers\Auth;

use App\Base\JWT;
use App\Base\Pterodactyl;
use Exception;

Pterodactyl::env(env['PTERODACTYL_API_KEY'], env['PTERODACTYL_API_URL']);


class AuthController
{
    private static $scopes = 'identify email guilds';
    private static function checkOauth2($code)
    {
        $searchParams = [
            'client_id' => env['DISCORD_CLIENT_ID'],
            'client_secret' => env['DISCORD_CLIENT_SECRET'],
            'code' => $code,
            'redirect_uri' => env['WEBSITE'] . '/callback',
            'grant_type' => 'authorization_code',
            'scope' => self::$scopes
        ];
        $searchParams = http_build_query($searchParams);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://discord.com/api/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $searchParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $logins = curl_exec($ch);
        curl_close($ch);
        if (isset(json_decode($logins, true)['error'])) {
            return [
                "error" => "Invalid code!"
            ];
        }
        # get user info
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://discord.com/api/users/@me');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
            'Authorization: Bearer ' . json_decode($logins, true)['access_token']
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return [
            "user" => json_decode($server_output, true),
            "logins" => json_decode($logins, true)
        ];
    }
    public static function generateOauth2Url()
    {
        $searchParams = [
            'client_id' => env['DISCORD_CLIENT_ID'],
            'redirect_uri' => env['WEBSITE'] . '/callback',
            'response_type' => 'code',
            'scope' => self::$scopes
        ];
        return 'https://discord.com/api/oauth2/authorize?' . http_build_query($searchParams);
    }
    public static function login()
    {
        $grav_default = env["WEBSITE"] . "/favicon.png";
        $grav_size = 160;
        $data = $_POST;
        if (!$data) {
            return [
                "error" => "No data provided!"
            ];
        }
        if (!$data['code']) {
            return [
                "error" => "No code provided!"
            ];
        }
        $user = self::checkOauth2($data['code']);
        if (isset($user['error'])) {
            return [
                "error" => "Invalid code!"
            ];
        }
        if ($user['user']['verified'] == false) {
            return [
                "error" => "You must verify your email!"
            ];
        }
        if (isset($user['error'])) {
            return [
                "error" => "Invalid code!"
            ];
        }
        $pterodactyl = Pterodactyl::createUserIfNotExists($user['user']['email'], $user['user']['global_name'], $user['user']['username'], $user['user']['username'], bin2hex(random_bytes(16)));
        if (!isset($pterodactyl['attributes'])) {
            return [
                "error" => "Error creating user!"
            ];
        }
        db->unsafeQuery("INSERT INTO users (username, email, avatar, access_token, refresh_token, pterodactyl) VALUES ('" . $user['user']['global_name'] . "', '" . $user['user']['email'] . "', '" . "https://www.gravatar.com/avatar/" . hash("sha256", strtolower(trim($user['user']['email']))) . "?d=" . urlencode($grav_default) . "&s=" . $grav_size . "', '" . $user['logins']['access_token'] . "', '" . $user['logins']['refresh_token'] . "', '" . $pterodactyl['attributes']['id'] . "') ON DUPLICATE KEY UPDATE username='" . $user['user']['global_name'] . "', email='" . $user['user']['email'] . "', avatar='" . "https://www.gravatar.com/avatar/" . hash("sha256", strtolower(trim($user['user']['email']))) . "?d=" . urlencode($grav_default) . "&s=" . $grav_size . "', access_token='" . $user['logins']['access_token'] . "', refresh_token='" . $user['logins']['refresh_token'] . "', pterodactyl='" . $pterodactyl['attributes']['id'] . "'");
        $res = db->unsafeQuery("SELECT * FROM users WHERE email='" . $user['user']['email'] . "'");
        if (!$res) {
            return [
                "error" => "Error creating user!"
            ];
        }
        $token = JWT::encode([
            "username" => $user['user']['global_name'],
            "email" => $user['user']['email'],
            "avatar" => "https://www.gravatar.com/avatar/" . hash("sha256", strtolower(trim($user['user']['email']))) . "?d=" . urlencode($grav_default) . "&s=" . $grav_size,
            "id" => $res[0]['id'],
            "pterodactyl" => $pterodactyl['attributes']['id']
        ]);
        // name, value, expire, path, domain, secure, httponly
        setcookie('Authorization', $token, time() + 60 * 60 * 24 * 30, '/', '', false, true);
        return [
            "success" => "You are logged in!"
        ];
    }
    public static function index()
    {
        $authorization = $_COOKIE['Authorization'] ?? null;
        if (!$authorization) {
            return [
                "error" => "No token provided!"
            ];
        }
        $token = JWT::verify($authorization);
        if (isset($token['error'])) {
            return [
                "error" => "Invalid token!"
            ];
        }
        $user = $token;
        $db = db->unsafeQuery("SELECT * FROM users WHERE id='" . $user['id'] . "'");
        $default = [
            "cpu" => db->unsafeQuery("SELECT * FROM config WHERE name='cpu'")[0]['value'],
            "ram" => db->unsafeQuery("SELECT * FROM config WHERE name='ram'")[0]['value'],
            "disk" => db->unsafeQuery("SELECT * FROM config WHERE name='disk'")[0]['value'],
            "slots" => db->unsafeQuery("SELECT * FROM config WHERE name='slots'")[0]['value'],
            "database" => db->unsafeQuery("SELECT * FROM config WHERE name='database'")[0]['value'],
            "ports" => db->unsafeQuery("SELECT * FROM config WHERE name='ports'")[0]['value'],
        ];
        if (!$db) {
            return [
                "error" => "User not found!"
            ];
        }
        $dbUser = $db[0];
        $dbUser['cpu'] = $dbUser['cpu'] + $default['cpu'];
        $dbUser['ram'] = $dbUser['ram'] + $default['ram'];
        $dbUser['disk'] = $dbUser['disk'] + $default['disk'];
        $dbUser['slots'] = $dbUser['slots'] + $default['slots'];
        $dbUser['database'] = $dbUser['database'] + $default['database'];
        $dbUser['ports'] = $dbUser['ports'] + $default['ports'];
        $user = array_merge($user, $dbUser);
        // sleep(2);
        return [
            "success" => "You are logged in!",
            "user" => $user
        ];
    }
    public static function logout()
    {
        setcookie('Authorization', '', time() - 3600, '/', '', false, true);
        return [
            "success" => "You are logged out!"
        ];
    }
}
