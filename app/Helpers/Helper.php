<?php

namespace App\Helpers;


class Helper
{
    static function handleExceptions($th)
    {
        $statusCode = ($th->getCode() < 100 || $th->getCode() > 500) ? 500 : (int) $th->getCode();
        return response()->json(["errors" => ["message" => $th->getMessage()]], $statusCode);
    }

    static function handleValidationErrors($validator)
    {
        return response()->json(["errors" => [$validator->errors()]], 422);
    }

    static function handleCreatedSuccessfully($message, $element)
    {
        return response()->json(["data" => ["message" => $message, "id" => $element->id]], 201);
    }

    static function handleNotFound($message)
    {
        return response()->json(["errors" => ["message" => $message]], 404);
    }

    static function handleSuccessMessage($message)
    {
        return response()->json(["data" => ["message" => $message]], 200);
    }

    static function handleErrorMessage($message, $code)
    {
        return response()->json(["errors" => ["message" => $message]], $code);
    }



}
