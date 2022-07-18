<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject\Authority;

class ParsedData
{
    public function __construct(
        public readonly string $valueToParse,
        public readonly bool $isValid,
        public readonly string|null $scheme = null,
        public readonly string|null $userInfo = null,
        public readonly string|null $host = null,
        public readonly int|null $port = null,
        public readonly string|null $valueNormalized = null,
    ) {
    }
}
