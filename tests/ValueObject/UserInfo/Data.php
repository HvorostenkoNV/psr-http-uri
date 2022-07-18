<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject\UserInfo;

class Data
{
    public function __construct(
        public readonly string $login,
        public readonly string $password
    ) {
    }
}
