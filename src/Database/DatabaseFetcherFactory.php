<?php

namespace App\Database;

use App\Config\ConfigLoader;
use PierreMiniggio\DatabaseConnection\DatabaseConnection;
use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class DatabaseFetcherFactory
{

    private static DatabaseFetcher $fetcher;

    public static function make(): DatabaseFetcher
    {

        if (! isset(static::$fetcher)) {
            $config = ConfigLoader::load();
            static::$fetcher = new DatabaseFetcher(new DatabaseConnection(
                $config['host'],
                $config['database'],
                $config['username'],
                $config['password']
            ));
        }

        return static::$fetcher;
    }
}
