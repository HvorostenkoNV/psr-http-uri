<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject\Authority;

use HNV\Http\UriTests\ValueObject\UserInfo\Data as UserInfoData;
use InvalidArgumentException;

/**
 * @property-read string $userLogin
 * @property-read string $userPassword
 */
class Data
{
    public function __construct(
        public readonly UserInfoData $userData,
        public readonly string $host,
        public readonly int $port,
    ) {
    }

    public function __get(string $field): string
    {
        return match ($field) {
            'userLogin'     => $this->userData->login,
            'userPassword'  => $this->userData->password,
            default         => throw new InvalidArgumentException("unknown field [{$field}]"),
        };
    }
}
