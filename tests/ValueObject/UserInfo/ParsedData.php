<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject\UserInfo;

class ParsedData
{
    public function __construct(
        public readonly string $valueToParse,
        public readonly bool $isValid,
        public readonly string|null $valueNormalized = null,
    ) {
    }
}
