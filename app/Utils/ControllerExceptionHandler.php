<?php

namespace App\Utils;

use App\Exceptions\EntityNotFoundException;
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

        if ($error instanceof EntityNotFoundException) {
            return response()->json($body, Response::HTTP_NOT_FOUND); // 404
        }

        return response()->json($body, Response::HTTP_INTERNAL_SERVER_ERROR); // 500
    }
}
