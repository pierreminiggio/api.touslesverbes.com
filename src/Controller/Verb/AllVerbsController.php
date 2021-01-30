<?php

namespace App\Controller\Verb;

use App\Http\Response\HttpOkResponse;
use App\Http\Response\Response;
use App\Repository\CryptedVerbRepository;
use DateTime;

class AllVerbsController
{

    public function __construct(private CryptedVerbRepository $repository)
    {}
    
    public function __invoke(): Response
    {
        $basePath = __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
        ;

        $cachePath = $basePath . 'cache' . DIRECTORY_SEPARATOR;
        
        $verbsCacheFilePath = $cachePath . 'verbs.json';
        $lastCacheDateFilePath = $cachePath . 'last_cache_date';

        if (! file_exists($lastCacheDateFilePath)) {
            goto pullFromDatabase;
        }

        $cacheDate = new DateTime(file_get_contents($lastCacheDateFilePath));
        
        $lastMonthDate = new DateTime();
        $lastMonthDate->modify('-1 month');
        
        if ($cacheDate > $lastMonthDate) {
            return new HttpOkResponse(file_get_contents($verbsCacheFilePath));
        }

        pullFromDatabase:
        $verbs = $this->repository->findAll();
        $jsonVerbs = json_encode($verbs);
        file_put_contents($verbsCacheFilePath, $jsonVerbs);
        file_put_contents($lastCacheDateFilePath, (new DateTime())->format('Y-m-d H:i:s'));

        return new HttpOkResponse($jsonVerbs);
    }
}
