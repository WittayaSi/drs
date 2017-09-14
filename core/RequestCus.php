<?php

namespace App\Core;

class RequestCus
{
    public static function uri()
    {
        //die(var_dump($_SERVER));
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
