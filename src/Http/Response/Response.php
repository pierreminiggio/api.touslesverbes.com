<?php

namespace App\Http\Response;

class Response
{

    public function __construct(private int $code, private string $content = '',)
    {}

    public function render(): void
    {
        http_response_code($this->code);
        echo $this->content;
    }
}
