<?php

namespace Core;

use ErrorException;

class View
{
  public static function render(string $template, array $data = [], string $layout = null): string
  {
    $content = static::renderTemplate(
      $template,
      $data
    );
    return static::renderLayout($layout, $data, $content);
  }

  protected static function renderTemplate(string $template, array $data): string
  {
    extract($data);

    $path = dirname(__DIR__) . "/app/Views/$template.php";

    if (!file_exists($path)) {
      throw new ErrorException("Error: Template file not found: $path");
    }

    ob_start();
    require $path;
    return ob_get_clean();
  }

  protected static function renderLayout(?string $template, array $data, string $content): string
  {
    if (null === $template) {
      return $content;
    }

    extract([...$data, 'content' => $content]);

    $path = dirname(__DIR__) . "/app/Views/$template.php";

    if (!file_exists($path)) {
      throw new ErrorException("Error: Layout file not found: $path");
    }

    ob_start();
    require $path;
    return ob_get_clean();
  }
}
