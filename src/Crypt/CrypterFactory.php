<?php

namespace App\Crypt;

use App\Config\ConfigLoader;
use PierreMiniggio\SimpleCrypter\Crypter;
use PierreMiniggio\SimpleCrypter\SimpleCrypter;

class CrypterFactory
{
    
    private static Crypter $crypter;

    public static function make(): Crypter
    {

        if (! isset(static::$crypter)) {
            static::$crypter = new SimpleCrypter(ConfigLoader::load()['cryptKey']);
        }

        return static::$crypter;
    }
}
