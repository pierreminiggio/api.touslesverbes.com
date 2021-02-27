<?php

namespace App\Repository;

use App\Enum\SourceEnum;
use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;

class VerbRepository
{
    public function __construct(private DatabaseFetcher $fetcher)
    {
    }

    /**
     * @param string[] $verbs
     * @param int $sourceId
     * @see SourceEnum
     */
    public function addNewVerbsIfMissing(array $verbs, int $sourceId): void
    {
        foreach ($verbs as $verb) {
            $queriedVerbs = $this->findIdsByNameQuery($verb);

            if (count($queriedVerbs) === 0) {
                $this->insertVerb($verb);
                $queriedVerbs = $this->findIdsByNameQuery($verb);
            }

            $this->insertSourceIfMissing((int) $queriedVerbs[0]['id'], $sourceId);
        }
    }

    /**
     * @param string[] $verbs
     * @param int $groupId
     * @param int $sourceId
     * @see SourceEnum
     */
    public function addNewVerbsAndGroupIfMissing(array $verbs, int $groupId, int $sourceId): void
    {
        foreach ($verbs as $verb) {
            $queriedVerbs = $this->findIdsAndGroupsByNameQuery($verb);

            if (count($queriedVerbs) === 0) {
                $this->insertVerb($verb);
                $queriedVerbs = $this->findIdsAndGroupsByNameQuery($verb);
            }

            $verb = $queriedVerbs[0];
            $verbId = (int) $verb['id'];

            if ($verb['group_id'] === null) {
                $this->fetcher->exec(
                    $this->fetcher
                        ->createQuery('verb')
                        ->update('group_id = :group_id')
                        ->where('id = :id'),
                    ['id' => $verbId, 'group_id' => $groupId]
                );
            }

            $this->insertSourceIfMissing($verbId, $sourceId);
        }
    }

    private function findIdsByNameQuery(string $verb): array
    {
        return $this->fetcher->query(
            $this->fetcher
                ->createQuery('verb')
                ->select('id')
                ->where('name = :verb'),
            ['verb' => $verb]
        );
    }

    private function findIdsAndGroupsByNameQuery(string $verb): array
    {
        return $this->fetcher->query(
            $this->fetcher
                ->createQuery('verb')
                ->select('id', 'group_id')
                ->where('name = :verb'),
            ['verb' => $verb]
        );
    }

    private function insertVerb(string $verb, ?int $groupId = null): void
    {
        $this->fetcher->exec(
            $this->fetcher
                ->createQuery('verb')
                ->insertInto('name, group_id', ':verb, :group_id'),
            ['verb' => $verb, 'group_id' => $groupId]
        );
    }

    /**
     * @see SourceEnum
     */
    private function insertSourceIfMissing(int $verbId, int $sourceId): void
    {
        $verbSourceParameters = ['verb_id' => $verbId, 'source_id' => $sourceId];
        $queriedSourceIds = $this->fetcher->query(
            $this->fetcher
                ->createQuery('verb_source')
                ->select('id')
                ->where('verb_id = :verb_id AND source_id = :source_id'),
            $verbSourceParameters
        );

        if (count($queriedSourceIds) === 0) {
            $this->fetcher->exec(
                $this->fetcher
                    ->createQuery('verb_source')
                    ->insertInto('verb_id, source_id', ':verb_id, :source_id'),
                $verbSourceParameters
            );
        }
    }
}
