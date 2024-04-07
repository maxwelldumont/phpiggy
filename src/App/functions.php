<?php

declare(strict_types=1);

function dd(mixed $value)
{
  echo "<pre>";
  print_r($value);
  echo "</pre>";
  die();
}

function escapeHtml(mixed $value): string
{
  return htmlspecialchars((string) $value);
}

function redirectTo($path)
{
  header("Location: $path");
  http_response_code(302); //code 302 represents a temporary redirect
  exit;
}
