<?php

namespace App\Repository;

use App\Enum\SourceEnum;
use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class VerbRepository
{
    public function __construct(private DatabaseFetcher $fetcher)
    {}

    /**
     * @params string[] $verbs
     * @params int $source
     * @see SourceEnum
     */
    public function addNewVerbsIfMissing(array $verbs, int $sourceId): void
    {
        foreach ($verbs as $verb) {
            var_dump($verb); die();

            $verbParameters = ['verb' => $verb];
            $findVerbQuery = [
                $this->fetcher
                    ->createQuery('verb')
                    ->select('id')
                    ->where('name = :verb')
                ,
                $verbParameters
            ];
            $queriedVerbs = $this->fetcher->query(...$findVerbQuery);

            if (count($queriedVerbs) === 0) {
                $this->fetcher->exec(
                    $this->fetcher
                    ->createQuery('verb')
                    ->insertInto('name', ':verb')
                    ,
                    $verbParameters
                );

                $queriedVerbs = $this->fetcher->query(...$findVerbQuery);
            }
        }
    }
}
