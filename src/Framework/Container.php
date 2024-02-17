<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
  private array $definitions = [];

  private array $resolvedDependencies = [];

  public function addDefinitions(array $newDefinitions)
  {
    $this->definitions = array_merge($this->definitions, $newDefinitions);
  }

  public function resolve(string $className)
  {
    $reflectionClass = new ReflectionClass($className);

    //check if class is valid (eg: can be instantiated)

    if (!$reflectionClass->isInstantiable()) {
      throw new ContainerException("Class {$className} is not instantiable");
    }

    $constructor = $reflectionClass->getConstructor();

    if (!$constructor) {
      return new $className;
    }

    //get list of parameters from the controller construtor

    $params = $constructor->getParameters();

    if (count($params) === 0) {
      return new $className;
    }

    $dependencies = [];

    //validate parameters
    foreach ($params as $param) {
      $name = $param->getName();
      $type = $param->getType();

      if (!$type) {
        throw new ContainerException("Failed to resolve class {$className} because parameter \${$name} is missing a type hint.");
      }

      if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
        throw new ContainerException("Failed to resolve class {$className} due to unsupported parameter type.");
      }

      $dependencies[] = $this->getDependency($type->getName());
    }

    return $reflectionClass->newInstanceArgs($dependencies);
  }


  public function getDependency(string $id)
  {
    if (!array_key_exists($id, $this->definitions)) {
      throw new ContainerException("Class {$id} does not exist in container.");
    }

    //implement singleton pattern
    if (array_key_exists($id, $this->resolvedDependencies)) {
      return $this->resolvedDependencies[$id];
    }

    $factory = $this->definitions[$id];
    $dependency = $factory();

    $this->resolvedDependencies[$id] = $dependency;

    return $dependency;
  }
}
