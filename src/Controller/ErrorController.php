<?php

namespace App\Controller;

use App\Http\Response\NotFoundErrorResponse;
use App\Http\Response\Response;

class ErrorController
{

    public function error404(): Response
    {
        return new NotFoundErrorResponse();
    }
}