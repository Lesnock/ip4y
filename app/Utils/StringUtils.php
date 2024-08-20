<?php

namespace App\Utils;

class StringUtils
{
    public static function camelCaseToSnakeCase($string)
    {
        $output = preg_replace('/[A-Z]/', '_$0', $string);
        return strtolower($output);
    }

    public static function snakeCaseToCamelCase($string)
    {
        $words = explode('_', $string);
        $camelCase = strtolower(array_shift($words));
        $camelCase .= implode('', array_map('ucfirst', $words));
        return $camelCase;
    }
}
