<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{
  private array $globalTemplateData = [];

  public function __construct(private string $basePath)
  {
  }

  public function render(string $template, array $data = array())
  {
    extract($data, EXTR_SKIP); //stores array values in variables with key names (eg: ["animal" => 'dog'] -> $animal = 'dog')

    extract($this->globalTemplateData, EXTR_SKIP);

    ob_start(); //start output buffer
    include $this->resolve($template);

    $output = ob_get_contents(); //store page content in variable

    ob_end_clean(); //stops output buffering, removes contents from the output buffer

    return $output;
  }

  public function resolve(string $path)
  {
    return "{$this->basePath}/{$path}";
  }

  public function addGlobal(string $key, mixed $value)
  {
    $this->globalTemplateData[$key] = $value;
  }
}
