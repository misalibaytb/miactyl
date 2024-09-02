<?php

namespace App\Base;

class fetch
{
    public static function get($url, $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array_merge($headers, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output, true);
    }
    public static function post($url, $data, $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array_merge($headers, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output, true);
    }
    public static function put($url, $data, $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array_merge($headers, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output, true);
    }
    public static function delete($url, $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array_merge($headers, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output, true);
    }
}
