<?php

namespace App\Http\Response;

class NotFoundErrorResponse extends HttpResponse
{
    public function __construct()
    {
        return parent::__construct(404);
    }
}
