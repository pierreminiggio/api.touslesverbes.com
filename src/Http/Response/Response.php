<?php

namespace App\Http\Response;

interface Response
{
    public function execute(): void;
}
