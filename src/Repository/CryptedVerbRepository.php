<?php

namespace App\Repository;

use App\Entity\Verb;
use PierreMiniggio\DatabaseFetcher\DatabaseFetcher;
use PierreMiniggio\SimpleCrypter\Crypter;

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
}
