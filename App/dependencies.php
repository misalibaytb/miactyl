<?php
//Made by VoltPHP. Do not touch!
$dbext = "";
if (version_compare(phpversion(), '8.2.0', '<')) {
    die("VoltPHP requires PHP 8.2.0 or higher.");
}
if (!extension_loaded('mysqli')) {
    trigger_error('The MySQLi extension is not loaded. This might not be a bug if you use something else.', E_WARNING);
} else {
    $dbext = "mysqli";
}
if (!extension_loaded('PDO')) {
    trigger_error('The PDO extension is not loaded. This might not be a bug if you use something else.', E_WARNING);
} else {
    $dbext = "PDO";
}
if ($dbext === "") {
    die("No compatible database extension found. Please make sure you have the MySQLi or PDO extension installed.");
}
define('DB_EXTENSION', $dbext);
if (!extension_loaded("apcu")) {
    trigger_error('The APCu extension is not loaded. You will not be able to use CSRF!.', E_WARNING);
}
