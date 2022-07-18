<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject;

class InvalidValue
{
    public function __construct(public readonly mixed $value)
    {
    }
}
