<?php

namespace App\Http\Response;

class HttpResponse implements Response
{

    public function __construct(private int $code, private string $content = '',)
    {}

    public function execute(): void
    {
        http_response_code($this->code);
        echo $this->content;
    }
}
