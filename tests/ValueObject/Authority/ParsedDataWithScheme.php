<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject\Authority;

class ParsedDataWithScheme extends ParsedData
{
    public function __construct(
        string $valueToParse,
        bool $isValid,
        string|null $scheme = null,
        string|null $userInfo = null,
        string|null $host = null,
        int|null $port = null,
        public readonly string|null $authority = null,
        string|null $valueNormalized = null,
    ) {
        parent::__construct(
            $valueToParse,
            $isValid,
            $scheme,
            $userInfo,
            $host,
            $port,
            $valueNormalized,
        );
    }
}
