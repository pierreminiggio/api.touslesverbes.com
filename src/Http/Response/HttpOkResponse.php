<?php

namespace App\Http\Response;

class HttpOkResponse extends HttpResponse
{
    public function __construct(string $content = '')
    {
        return parent::__construct(200, $content);
    }
}
