<?php

declare(strict_types=1);

namespace Framework;

class Router
{
  private array $routes = array();
  private array $middlewares = array();

  public function add(string $method, string $path, array $controller)
  {
    $path = $this->normalizePath($path);

    $regexPath = preg_replace('#{[^/]+}#', '([^/]+)', $path);

    $this->routes[] = [
      'path' => $path,
      'method' => strtoupper($method),
      'controller' => $controller, //stores class name and method name
      'middlewares' => [],
      'regexPath' => $regexPath
    ];
  }

  private function normalizePath(string $path): string
  {
    $path = trim($path, "/");
    $path = "/{$path}/";
    $path = preg_replace('#[/]{2,}#', '/', $path);

    return $path;
  }

  public function dispatch(string $path, string $method, Container $container = null)
  {
    $path = $this->normalizePath($path);
    $method = strtoupper($_POST['_METHOD'] ?? $method);

    //use loop because routes may be overwritten
    foreach ($this->routes as $route) {
      if (
        !preg_match("#^{$route['regexPath']}$#", $path, $paramValues) ||
        $route['method'] !== $method
      ) {
        continue;
      }

      array_shift($paramValues);

      preg_match_all('#{([^/]+)}#', $route['path'], $paramKeys);
      $params = array_combine($paramKeys[1], $paramValues);

      // dd($params);

      [$class, $function] = $route['controller'];

      //check if a container exists if so resolve any dependancies for the class
      $controllerInstance = $this->resolveClass($container, $class);

      $action = fn () => $controllerInstance->$function($params);

      $allMiddleware = [...$route['middlewares'], ...$this->middlewares];

      //execute middleware
      foreach ($allMiddleware as $middleware) {
        $middlewareInstance = $this->resolveClass($container, $middleware);
        $action = fn () => $middlewareInstance->process($action);
      }

      $action();

      return;
    }
  }

  public function addMiddleware(string $middleware)
  {
    $this->middlewares[] = $middleware;
  }

  public function addRouteMiddleware(string $middleware)
  {
    $lastRouteKey = array_key_last($this->routes);
    $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
  }

  public function resolveClass(Container $container, string $class)
  {
    return $container ? $container->resolve($class) : new $class;
  }
}
