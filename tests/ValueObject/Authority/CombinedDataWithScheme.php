<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValueObject\Authority;

use HNV\Http\UriTests\ValueObject\UserInfo\Data as UserInfoData;

/**
 * @property-read string $authority
 */
class CombinedDataWithScheme extends CombinedData
{
    public function __construct(
        string $scheme,
        UserInfoData $userData,
        string $host,
        int $port,
        public readonly string $schemeNormalized,
        string $authority,
    ) {
        parent::__construct(
            $scheme,
            $userData,
            $host,
            $port,
            $authority,
        );
    }

    public function __get(string $field): string
    {
        return match ($field) {
            'authority' => $this->fullValue,
            default     => parent::__get($field),
        };
    }
}
