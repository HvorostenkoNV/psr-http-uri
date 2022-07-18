<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\UserInfo;

use HNV\Http\UriTests\Generator\InvalidValuesGeneratorInterface;

class ValueInvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    public function generate(): iterable
    {
        return [];
    }
}
