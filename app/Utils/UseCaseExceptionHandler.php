<?php

namespace App\Utils;

use App\Domain\Exceptions\EntityBuildValidationException;
use App\Domain\Exceptions\ValueObjectValidationException;
use App\Http\Exceptions\ClientException;
use App\Http\Exceptions\ServerException;
use Exception;

class UseCaseExceptionHandler
{
    public static function handle(Exception $error)
    {
        if ($error instanceof EntityBuildValidationException) {
            throw new ClientException($error->getMessage());
        }

        if ($error instanceof ValueObjectValidationException) {
            throw new ClientException($error->getMessage());
        }

        throw new ServerException($error->getMessage());
    }
}
