<?php
error_reporting(E_ALL & ~E_WARNING); // Suppress warnings for a cleaner output
// For debugging purposes:
// ini_set('display_errors', 1); // Enable error display
// ini_set('display_startup_errors', 1); // Enable startup errors display
// error_reporting(E_ALL); // Show all errors, including warnings

class Route
{
    private $method;
    private $uri;

    public function __construct($method, $uri)
    {
        $this->method = $method;
        $this->uri = $uri;
    }
    /**
     * Assigns a prefix to the route, allowing middleware to identify and use it.
     * 
     * @param string $prefix The prefix to be assigned to the route
     * @return void
     */
    public function name($prefix)
    {
        Router::$prefixedRoutes[$prefix] = [
            'method' => $this->method,
            'uri' => $this->uri
        ];
    }
}
class FunctionClass
{
    private $name;
    private $function;

    /**
     * Sets the name for the function to be registered.
     * 
     * @param string $name The name to be assigned to the function
     * @return FunctionClass
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the callable function to be registered.
     * 
     * @param callable $function The function to be registered
     * @return FunctionClass
     */
    public function function($function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * Registers the function with the Router using the defined name.
     * 
     * @return void
     */
    public function register()
    {
        $funcWithParsedVariables = function (...$parsedVariables) {
            return call_user_func($this->function, ...$parsedVariables);
        };
        Router::$functions[$this->name] = $funcWithParsedVariables
            ?? function () {
                throw new Exception('Function not defined');
            };
    }
}

class Middleware
{
    private $funcs;
    private $inverted = false;
    private $parsedVariables;

    /**
     * Middleware constructor to initialize the middleware functions.
     * 
     * @param array $funcs An array of function prefixes that will be executed as middleware
     */
    public function __construct($funcs, $parsedVariables = [])
    {
        $this->funcs = $funcs;
        $this->parsedVariables = $parsedVariables;
    }

    /**
     * Inverts the middleware functions, executing the callback function if any middleware function fails.
     * 
     * @return Middleware
     */
    public function invert(): Middleware
    {
        $this->inverted = true;
        return $this;
    }

    /**
     * Executes the middleware functions in sequence and then runs the provided callback function.
     * 
     * @param callable $callbackfunc The function to be executed if all middleware functions succeed
     * @return void
     */
    public function group($callbackfunc)
    {
        foreach ($this->funcs as $func) {
            try {
                Router::invokeByPrefix($func, false);
            } catch (Exception $e) {
                if ($this->inverted) call_user_func($callbackfunc, ...$this->parsedVariables);
                return;
            }
        }
        if (!$this->inverted) call_user_func($callbackfunc);
    }
}

class Router
{
    private static $routes = [];
    public static $prefixedRoutes = [];
    public static $middleware = [];
    public static $functions = [];
    private static $handlingRequest = false;
    private static $fallback = null;

    /**
     * Registers a route with the specified HTTP method, URI, class, and function.
     * 
     * @param string $method The HTTP method for the route (e.g., GET, POST)
     * @param string $uri The URI pattern for the route
     * @param string $class The class containing the function to be executed
     * @param string $function The function to be executed
     * @return Route
     */
    private static function registerRoute($method, $uri, $class, $function, $parsedVariables): Route
    {
        self::$routes[$method][$uri] = compact('class', 'function', 'parsedVariables');
        return new Route($method, $uri);
    }

    /**
     * Registers a GET route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function GET($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('GET', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers a POST route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function POST($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('POST', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers a PUT route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function PUT($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('PUT', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers an OPTIONS route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function OPTIONS($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('OPTIONS', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers a DELETE route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function DELETE($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('DELETE', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers a HEAD route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function HEAD($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('HEAD', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers a PATCH route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function PATCH($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('PATCH', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers a CONNECT route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function CONNECT($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('CONNECT', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Registers a TRACE route.
     * 
     * @param string $uri The URI pattern for the route
     * @param mixed $class The class containing the function to be executed. Or a callable function.
     * @param string $function The function to be executed if previous parameter is a class.
     * @return Route
     */
    public static function TRACE($uri, $class, $function = null, $parsedVariables = []): Route
    {
        return self::registerRoute('TRACE', $uri, $class, $function, $parsedVariables);
    }

    /**
     * Sets a fallback route to be executed when no other routes match.
     * 
     * @param string $path The path for the fallback route
     * @param callable $function The function to be executed for the fallback route
     * @return void
     */
    public static function fallback($path, $function)
    {
        self::$fallback = compact('path', 'function');
    }

    /**
     * Creates a Middleware instance with the given function prefixes.
     * 
     * @param array $funcs An array of function prefixes to be used as middleware
     * @return Middleware
     */
    public static function middleware($funcs)
    {
        return new Middleware($funcs);
    }

    /**
     * Creates a FunctionClass instance for registering functions with the Router.
     * 
     * @return FunctionClass
     */
    public static function functionRegister()
    {
        return new FunctionClass();
    }

    /**
     * Handles the incoming request, executes the matching route, or falls back to the fallback route if no match is found.
     * To disable automatic request handling, set Router::$handleRequestOnExit to false.
     * 
     * @return void
     */
    public static function handleRequest()
    {
        if (self::$handlingRequest) {
            return;
        }
        self::$handlingRequest = true;
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $route = self::$routes[$method][$uri] ?? self::matchWildcardRoute($method, $uri);

        if ($route) {
            self::executeRoute($route);
        } elseif (self::$fallback) {
            call_user_func(self::$fallback['function'], self::$fallback['path']);
        } else {
            require_once ROOT . '/resources/views/errors/404.php'; // Load a 404 error page if no route or fallback is found
        }
    }

    /**
     * Matches a route with wildcard patterns to the incoming URI.
     * 
     * @param string $method The HTTP method of the route
     * @param string $uri The URI of the incoming request
     * @return array|null The matched route or null if no match is found
     */
    private static function matchWildcardRoute($method, $uri)
    {
        foreach (self::$routes[$method] as $routeUri => $route) {
            if (strpos($routeUri, '*') !== false) {
                $pattern = str_replace('*', '.*', $routeUri);
                if (preg_match("#^$pattern$#", $uri)) {
                    return $route;
                }
            }
        }
        return null;
    }

    /**
     * Executes the route by calling the specified class and function.
     * 
     * @param array $route The route containing class and function to be executed
     * @return void
     */
    private static function executeRoute($route)
    {
        try {
            header("server: Voltphp");
            header("X-Powered-By: Voltphp");
            // header('Content-Type: application/json');
            echo json_encode(self::invoke($route['class'], $route['function'], $route['parsedVariables']));
        } catch (Exception $e) {
            require_once ROOT . '/resources/views/errors/500.php'; // Load a 500 error page if an exception occurs
        }
    }

    /**
     * Instantiates the class and invokes the specified function.
     * 
     * @param string $class The class containing the function to be executed
     * @param string $function The function to be invoked
     * @return mixed The result of the function execution
     */
    private static function invoke($class, $function, $parsedVariables = [])
    {
        if (is_callable($class)) {
            return $class(...$parsedVariables);
        }
        if (class_exists($class)) {
            $obj = new $class();
            if (method_exists($obj, $function)) {
                return $obj->$function(...$parsedVariables);
            }
        }
        require_once ROOT . '/resources/views/errors/500.php'; // Load a 500 error page if class or method does not exist
    }

    /**
     * Invokes a function based on its prefix, checking for prefixed routes if needed.
     * 
     * @param string $prefix The prefix of the function to be executed
     * @return mixed The result of the function execution
     */
    public static function invokeByPrefix($prefix, $pageInvoked = true)
    {
        if (isset(self::$functions[$prefix])) {
            return call_user_func(self::$functions[$prefix]);
        }

        $prefixedRoute = self::$prefixedRoutes[$prefix] ?? null;
        if ($prefixedRoute) {
            $method = $prefixedRoute['method'];
            $uri = $prefixedRoute['uri'];
            $route = self::$routes[$method][$uri] ?? self::matchWildcardRoute($method, $uri);

            if ($route) {
                return self::invoke($route['class'], $route['function']);
            }
        }
        if ($pageInvoked) {
            require_once ROOT . '/resources/views/' . ($prefixedRoute ? 'errors/500.php' : 'errors/404.php'); // Load error pages as appropriate
        } else {
            throw new Exception('Function not found');
        }
    }
}
