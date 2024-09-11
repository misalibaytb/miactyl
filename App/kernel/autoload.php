<?php

use App\Base\MysqliInstance;
use App\Base\PDOInstance;

if (version_compare(phpversion(), '8.1.0', '<')) {
    die("VoltPHP requires PHP 8.1 or higher.");
}
if (!defined('ROOT')) {
    define('ROOT', __DIR__ . '/../../');
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


error_reporting(E_ALL);
header("Server: VoltPHP");
header("X-Powered-By: VoltPHP");
spl_autoload_register(function ($class) {

    // and prepend the base directory
    $file = ROOT . str_replace('\\', '/', $class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});
require_once ROOT . '/App/Base/db.php';
require_once ROOT . '/App/Base/router.php';
require_once ROOT . '/App/Base/assets.php';
require_once ROOT . '/App/Base/jwt.php';
if (!file_exists(ROOT . "/.env")) {
    trigger_error("No.env file found. Creating empty... Please fill it.", E_WARNING);
}

$env = parse_ini_file(ROOT . '/.env');
function runTroughtFolder($dir)
{
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == "." || $file == "..") {
            continue;
        }
        if (is_dir($dir . "/" . $file)) {
            runTroughtFolder($dir . "/" . $file);
        } else {
            require_once $dir . "/" . $file;
        }
    }
}

define('db', new PDOInstance(file_get_contents(ROOT . "/App/Schemas/db.sql"), ["host" => $env["DB_HOST"] . ":" . $env["DB_PORT"], "username" => $env["DB_USER"], "password" => $env["DB_PASSWORD"], "database" => $env["DB_NAME"]]));

define('env', $env);
// get every error and warning and save it into db
// set_error_handler(function ($errno, $errstr, $errfile, $errline) {
//     db->unsafeQuery("INSERT INTO errors (message, file, line) VALUES ('" . $errstr . "', '" . $errfile . "', '" . $errline . "')");
//     return false;
// });
// // get every exception and save it into db and still return same exception
// set_exception_handler(function ($e) {
//     db->unsafeQuery("INSERT INTO errors (message, stack, file, line) VALUES ('" . $e->getMessage() . "', '" . $e->getTraceAsString() . "', '" . $e->getFile() . "', '" . $e->getLine() . "')");
//     return $e;
// });
$logmode = true;
// get every data php sent to client and save it into db
register_shutdown_function(function () use ($logmode) {
    if ($logmode) {
        db->unsafeQuery("INSERT INTO logs (method, path, headers, body, response) VALUES ('" . $_SERVER['REQUEST_METHOD'] . "', '" . $_SERVER['REQUEST_URI'] . "', '" . json_encode(getallheaders()) . "', '" . file_get_contents('php://input') . "', '" . ob_get_contents() . "')");
    }
});
// if (getenv('APP_ENV') === "development") {
try {
    runTroughtFolder(ROOT . '/routers');
    Router::handleRequest();
} catch (Exception $e) {
    echo $e->getMessage();
    require_once ROOT . '/resources/views/errors/500.php';
}
// } else {
//     try {
//         runTroughtFolder(ROOT . '/App/Methods');
//         runTroughtFolder(ROOT . '/App/Providers');
//         runTroughtFolder(ROOT . '/App/Http');
//         runTroughtFolder(ROOT . '/routers');
//     } catch (Exception $e) {
//         echo $e->getMessage();
//         require_once ROOT . '/resources/views/errors/500.php';
//     }
// }