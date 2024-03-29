<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{
    protected $routes = [];

    public function add(string $method, string $url, string $action, string $controller): self
    {
        $this->routes[] = [
            'url' => $url,
            'controller' => $controller,
            'action' => $action,
            'method' => strtoupper($method),
            'middleware' => null,
        ];
        return $this;
    }

    public function get(string $url, string $controller, string $action): self
    {
        return $this->add('GET', $url, $action, $controller);
    }

    public function post(string $url, string $controller, string $action): self
    {
        return $this->add('POST', $url, $action, $controller);
    }

    public function put(string $url, string $controller, string $action): self
    {
        return $this->add('PUT', $url, $action, $controller);
    }

    public function delete(string $url, string $controller, string $action): self
    {
        return $this->add('DELETE', $url, $action, $controller);
    }

    public function patch(string $url, string $controller, string $action): self
    {
        return $this->add('PATCH', $url, $action, $controller);
    }

    public function only(string $key): void
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;
    }


    // TODO: integrate the middleware

    public function route(string $url, string $method)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $params = $this->matchRoute($url, $route);
                if ($params !== false) {
                    $controllerClassName = 'Classes\Controllers\\' . $route['controller'];
                    $controller = new $controllerClassName();
                    $action = $route['action'];

                    Middleware::resolve($route['middleware']);

                    $controller->handle($action, $params);
                    exit();
                }
            }
        }

        // Return 404 page if no route found
        $this->abort();
    }

    protected function abort(array $code = Response::NOT_FOUND): void
    {
        http_response_code($code['code']);
        view("error", $code);
        die();
    }

    protected function matchRoute(string $url, array $route)
    {
        // Split the route and URL into segments
        $routeSegments = explode('/', trim($route['url'], '/'));
        $urlSegments = explode('/', trim($url, '/'));

        // If segment counts are different, the route doesn't match
        if (count($routeSegments) !== count($urlSegments)) {
            return false;
        }

        $params = [];

        // Compare segments and extract route parameters
        foreach ($routeSegments as $index => $routeSegment) {
            if (strpos($routeSegment, '{') === 0 && strpos($routeSegment, '}') === strlen($routeSegment) - 1) {
                $paramName = trim($routeSegment, '{}');
                $params[$paramName] = $urlSegments[$index];
            } elseif ($routeSegment !== $urlSegments[$index]) {
                return false;
            }
        }

        return $params;
    }
}
