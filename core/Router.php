<?php

namespace Core;


class Router
{
  protected array $routes = [];

  public function add(string $method, string $uri, string $controller): void
  {
    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
    ];
  }

  public function NotFound(): void
  {
    http_response_code(404);
    echo "404 Not Found";
    exit;
  }

  public function dispatch(string $uri, string $method): string
  {
    $route = $this->findRoute($uri, $method);
    if (!$route) {
      return $this->NotFound();
    }
    [$controller, $action] = explode('@', $route['controller']);
    return $this->callAction($controller, $action, $route['params']);
  }
  protected function findRoute(string $uri, string $method): ?array
  {
    foreach ($this->routes as $route) {
      $params = $this->matchRoute($route['uri'], $uri);
      if ($params !== null && $route['method'] === $method) {
        return [...$route, 'params' => $params];
      }
    };
  }
  protected function matchRoute(string $routeUri, string $requestUri): ?array
  {
    $routeSegments = explode('/', trim($routeUri, '/'));
    $requestSegments = explode('/', trim($requestUri, '/'));

    if (count($routeSegments) !== count($requestSegments)) {
      return null;
    }

    $params = [];
    foreach ($routeSegments as $i => $routeSegment) {
      if (str_starts_with($routeSegment, '{') && str_ends_with($routeSegment, '}')) {
        $params[trim($routeSegment, '{}')] = $requestSegments[$i];
      } else {
        if ($routeSegment !== $requestSegments[$i]) {
          return null;
        }
      }
    };

    return $params;
  }

  protected function callAction(string $controller, string $action, array $params): string
  {
    $controllerClass = "App\\Controllers\\$controller";
    return (new $controllerClass)->$action(...$params);
  }
}
