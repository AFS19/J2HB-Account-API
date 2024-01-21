<?php

namespace App\Helpers;

class UnauthorizedHelper
{
  public static function handleUnauthorized($message, $statusCode)
  {
    return response()->json(['errors' => ["message" => $message, $statusCode]]);
  }
}
