<?php

namespace App\Controller\Verb;

use App\Http\Response\HttpOkResponse;
use App\Http\Response\NotFoundErrorResponse;
use App\Http\Response\Response;
use App\Repository\CryptedVerbRepository;

class SearchController
{

    public function __construct(private CryptedVerbRepository $repository)
    {}

    public function findOneByName(string $name): Response
    {
        $verb = $this->repository->findOneByName($name);

        if ($verb === null) {
            return new NotFoundErrorResponse();
        }

        return new HttpOkResponse(json_encode($verb));
    }

    public function searchByName(string $name): Response
    {
        return new HttpOkResponse(json_encode($this->repository->searchByName($name)));
    }
}
