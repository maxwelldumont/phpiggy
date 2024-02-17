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
