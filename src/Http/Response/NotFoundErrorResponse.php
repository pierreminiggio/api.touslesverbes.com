<?php

namespace App\Http\Response;

class NotFoundErrorResponse extends Response
{
    public function __construct()
    {
        return parent::__construct(404);
    }
}
