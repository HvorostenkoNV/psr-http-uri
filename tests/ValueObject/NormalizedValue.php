<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject;

class NormalizedValue
{
    public function __construct(
        public readonly mixed $value,
        public readonly mixed $valueNormalized
    ) {
    }
}
