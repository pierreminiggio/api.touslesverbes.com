<?php

namespace App\Repository;

use App\Entity\Verb;
use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;
use PierreMiniggio\SimpleCrypter\Crypter;
use PierreMiniggio\SimpleCrypter\CrypterException;

class CryptedVerbRepository
{
    public function __construct(private Crypter $crypter, private DatabaseFetcher $fetcher)
    {}

    /**
     * @return Verb[]
     */
    public function findAll(): array
    {
        return $this->createVerbEntitiesFromQuery(
            $this->fetcher->query($this->fetcher->createQuery('verb')->select('*'))
        );
    }

    public function findOneByUuid(string $uuid): ?Verb
    {
        try {
            $id = $this->crypter->decrypt($uuid);
        } catch (CrypterException $e) {
            return null;
        }

        $id = (int) $id;

        if ($id === 0) {
            return null;
        }

        $queriedVerbs = $this->fetcher->query(
            $this->fetcher
                ->createQuery('verb')
                ->select('*')
                ->where('id = :id')
            ,
            ['id' => $id]
        );

        if (count($queriedVerbs) === 0) {
            return null;
        }

        $queriedVerb = $queriedVerbs[0];

        return new Verb($uuid, $queriedVerb['name'], $queriedVerb['group_id']);
    }

    public function findOneByName(string $name): ?Verb
    {
        $queriedVerbs = $this->fetcher->query(
            $this->fetcher
                ->createQuery('verb')
                ->select('*')
                ->where('name = :name')
            ,
            ['name' => $name]
        );

        if (count($queriedVerbs) === 0) {
            return null;
        }

        return $this->createVerbEntityFromQuery($queriedVerbs[0]);
    }

    /**
     * @return Verb[]
     */
    public function searchByName(string $name): array
    {
        return $this->createVerbEntitiesFromQuery($this->fetcher->query(
            $this->fetcher
                ->createQuery('verb')
                ->select('*')
                ->where('name LIKE :name')
            ,
            ['name' => '%' . $name . '%']
        ));
    }

    /**
     * @return Verb[]
     */
    private function createVerbEntitiesFromQuery(array $queriedVerbs): array
    {
        $verbs = [];

        foreach ($queriedVerbs as $queriedVerb) {
            $verbs[] = $this->createVerbEntityFromQuery($queriedVerb);
        }

        return $verbs;
    }

    private function createVerbEntityFromQuery(array $queriedVerb): Verb
    {
        return new Verb(
            $this->crypter->crypt($queriedVerb['id']),
            $queriedVerb['name'],
            $queriedVerb['group_id'] !== null ? ((int) $queriedVerb['group_id']) : null
        );
    }
}
