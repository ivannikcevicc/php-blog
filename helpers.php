<?php

use App\Services\CSRF;
use Core\View;

if (!function_exists('partial')) {
  function partial(string $template, array $data = []): string
  {
    return View::partial($template, $data);
  }
}

if (!function_exists('csrf_token')) {
  function csrf_token(): string
  {
    $tokenField = CSRF::TOKEN_FIELD_NAME;
    $token = CSRF::getToken();
    return <<<TAG
    <input type="hidden" name="$tokenField" value="$token"/>
    TAG;
  }
}
