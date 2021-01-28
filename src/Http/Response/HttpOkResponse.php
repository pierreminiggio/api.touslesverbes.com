<?php

namespace App\Http\Response;

class HttpOkResponse extends Response
{
    public function __construct(string $content = '')
    {
        return parent::__construct(200, $content);
    }
}
