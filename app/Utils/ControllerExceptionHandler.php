<?php

namespace App\Utils;

use App\Http\Exceptions\ClientException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ControllerExceptionHandler
{
    public static function handle(Exception $error)
    {
        $body = [
            'status' => false,
            'error' => $error->getMessage()
        ];

        if ($error instanceof ClientException) {
            return response()->json($body, Response::HTTP_BAD_REQUEST); // 400
        }

        return response()->json($body, Response::HTTP_INTERNAL_SERVER_ERROR); // 500
    }
}
