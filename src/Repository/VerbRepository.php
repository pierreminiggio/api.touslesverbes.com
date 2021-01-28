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

            $verbId = (int) $queriedVerbs[0]['id'];
            $verbSourceParameters = ['verb_id' => $verbId, 'source_id' => $sourceId];
            $queriedSourceIds = $this->fetcher->query(
                $this->fetcher
                    ->createQuery('verb_source')
                    ->select('id')
                    ->where('verb_id = :verb_id AND source_id = :source_id')
                ,
                $verbSourceParameters
            );

            if (count($queriedSourceIds) === 0) {
                $this->fetcher->exec(
                    $this->fetcher
                        ->createQuery('verb_source')
                        ->insertInto('verb_id, source_id', ':verb_id, :source_id')
                    ,
                    $verbSourceParameters
                );
            }
        }
    }
}
