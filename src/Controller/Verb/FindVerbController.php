<?php

namespace App\Controller\Verb;

use App\Http\Response\HttpOkResponse;
use App\Http\Response\NotFoundErrorResponse;
use App\Http\Response\Response;
use App\Repository\CryptedVerbRepository;

class FindVerbController
{

    public function __construct(private CryptedVerbRepository $repository)
    {}

    public function __invoke(string $uuid): Response
    {
        $verb = $this->repository->findOneByUuid($uuid);

        if ($verb === null) {
            return new NotFoundErrorResponse();
        }

        return new HttpOkResponse(json_encode($verb));
    }
}
