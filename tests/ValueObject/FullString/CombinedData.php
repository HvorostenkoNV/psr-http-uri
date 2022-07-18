<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject\FullString;

use HNV\Http\UriTests\ValueObject\Authority\Data as AuthorityData;
use InvalidArgumentException;

/**
 * @property-read string $userLogin
 * @property-read string $userPassword
 * @property-read string $host
 * @property-read int    $port
 */
class CombinedData
{
    public function __construct(
        public readonly string $scheme,
        public readonly AuthorityData $authority,
        public readonly string $path,
        public readonly string $query,
        public readonly string $fragment,
        public readonly string $fullValue,
    ) {
    }

    public function __get(string $field): string|int
    {
        return match ($field) {
            'userLogin'     => $this->authority->userLogin,
            'userPassword'  => $this->authority->userPassword,
            'host'          => $this->authority->host,
            'port'          => $this->authority->port,
            default         => throw new InvalidArgumentException("unknown field [{$field}]"),
        };
    }
}
