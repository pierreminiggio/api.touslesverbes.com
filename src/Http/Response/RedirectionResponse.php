<?php

namespace App\Http\Response;

class RedirectionResponse implements Response
{

    public function __construct(private string $redirectionUrl)
    {}

    public function execute(): void
    {
        header('Location: ' . $this->redirectionUrl);
    }
}
