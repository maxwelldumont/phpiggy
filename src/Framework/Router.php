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
    $this->routes[] = [
      'path' => $path,
      'method' => strtoupper($method),
      'controller' => $controller //stores class name and method name
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
    $method = strtoupper($method);

    //use loop because routes may be overwritten
    foreach ($this->routes as $route) {
      if (
        !preg_match("#^{$route['path']}$#", $path) ||
        $route['method'] !== $method
      ) {
        continue;
      }

      [$class, $function] = $route['controller'];

      //check if a container exists if so resolve any dependancies for the class
      $controllerInstance = $this->resolveClass($container, $class);

      $action = fn () => $controllerInstance->$function();

      //execute middleware
      foreach ($this->middlewares as $middleware) {
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

  public function resolveClass(Container $container, string $class)
  {
    return $container ? $container->resolve($class) : new $class;
  }
}
