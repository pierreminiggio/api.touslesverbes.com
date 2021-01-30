<?php

namespace App\Config;

class ConfigLoader
{
    
    private static array $config;

    public static function load(): array
    {
        if (! isset(static::$config)) {
            static::$config = require __DIR__
                . DIRECTORY_SEPARATOR
                . '..'
                . DIRECTORY_SEPARATOR
                . '..'
                . DIRECTORY_SEPARATOR
                . 'config.php'
            ;
        }

        return static::$config;
    }
}
