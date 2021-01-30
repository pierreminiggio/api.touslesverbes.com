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
        $queriedVerbs = $this->fetcher->query($this->fetcher->createQuery('verb')->select('*'));

        $verbs = [];

        foreach ($queriedVerbs as $queriedVerb) {
            $verbs[] = new Verb(
                $this->crypter->crypt($queriedVerb['id']),
                $queriedVerb['name'],
                $queriedVerb['group_id'] !== null ? ((int) $queriedVerb['group_id']) : null
            );
        }

        return $verbs;
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
}
