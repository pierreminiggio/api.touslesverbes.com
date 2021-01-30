<?php

namespace App\Entity;

class Verb
{
    public function __construct(public string $uuid, public string $name, public ?int $group)
    {}
}
