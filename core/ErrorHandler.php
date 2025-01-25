<?php

namespace Core;

class ErrorHandler
{
  public static function handleException(\Throwable $exception)
  {
    //1) Log the error

    if (php_sapi_name() === 'cli') {
      static::renderCliError($exception);
    } else {
      // static::renderErrorPage($exception);
    }
  }

  private static function renderCliError(\Throwable $exception): void
  {
    $isDebug = App::get('config')['app']['debug'] ?? false;

    if ($isDebug) {
      $errorMessage = static::formatErrorMessage(
        $exception,
        "\033[31m[%s] Error:\033[0m %s in %s on line %d\n" // Use double quotes here
      );
      $trace = $exception->getTraceAsString();
    } else {
      $errorMessage = "\033[31mAn unexpected error occurred. Please check error logs for details.\033[0m\n"; // Use double quotes here
      $trace = '';
    }


    fwrite(STDERR, $errorMessage);
    if ($trace) {
      fwrite(STDERR, "\nStack trace:\n$trace\n");
    }
    exit(1);
  }

  public static function handleError($level, $message, $file, $line)
  {
    $exception = new \ErrorException($message, 0, $level, $file, $line);
    static::handleException($exception);
  }

  private static function formatErrorMessage(\Throwable $exception, string $format): string
  {
    return sprintf(
      $format,
      date('Y-m-d H:i:s'),
      get_class($exception),
      $exception->getMessage(),
      $exception->getFile(),
      $exception->getLine(),
    );
  }
}
