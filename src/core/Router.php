<?php
namespace App\Core;

class Router
{
    protected $routes = [];
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function addRoute($methods, string $path, string $controller, string $action, array $middlewares = [])
    {
        // Convertir métodos a array si es necesario
        $methods = is_array($methods) ? $methods : [$methods];
        
        foreach ($methods as $method) {
            $this->routes[] = [
                'method' => strtoupper($method),
                'path' => $path,
                'controller' => $controller,
                'action' => $action,
                'middlewares' => $middlewares
            ];
        }
    }

    public function dispatch(string $requestUri, string $requestMethod)
    {
        $uri = $this->getCleanUri($requestUri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertToRegex($route['path']);
            
            if (preg_match($pattern, $uri, $matches)) {
                $params = $this->filterParams($matches);
                
                if ($this->handleMiddlewares($route['middlewares'], $params)) {
                    return $this->callControllerAction(
                        $route['controller'],
                        $route['action'],
                        $params
                    );
                }
                return; // Middleware detuvo la ejecución
            }
        }

        // Ruta no encontrada
        $this->handleNotFound();
    }

    protected function getCleanUri(string $uri): string
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        return trim($uri, '/');
    }

    protected function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $path);
        return '@^' . $pattern . '$@i';
    }

    protected function filterParams(array $matches): array
    {
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    protected function handleMiddlewares(array $middlewares, array $params): bool
    {
        foreach ($middlewares as $key => $value) {
            if (is_int($key)) {
                $middlewareName = $value;
                $options = [];
            } else {
                $middlewareName = $key;
                $options = $value;
            }

            $middlewareClass = "App\\Middleware\\" . ucfirst($middlewareName) . 'Middleware';
            
            if (!class_exists($middlewareClass)) {
                throw new \RuntimeException("Middleware {$middlewareClass} no encontrado");
            }

            $middleware = new $middlewareClass();
            if (!$middleware->handle(array_merge($params, $options))) {
                return false;
            }
        }
        return true;
    }

    protected function callControllerAction(string $controllerClass, string $action, array $params)
    {
        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controlador {$controllerClass} no encontrado");
        }

        $controller = new $controllerClass($this->db);
        
        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Método {$action} no existe en {$controllerClass}");
        }

        return $controller->$action($params);
    }

    protected function handleNotFound()
    {
        http_response_code(404);
        if (class_exists('App\Controllers\ErrorController')) {
            $controller = new \App\Controllers\ErrorController($this->db);
            $controller->notFound();
        } else {
            echo 'Página no encontrada';
        }
        exit;
    }
}