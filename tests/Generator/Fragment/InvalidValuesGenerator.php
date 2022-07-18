<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Fragment;

use HNV\Http\UriTests\Generator\InvalidValuesGeneratorInterface;

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function generate(): iterable
    {
        yield from [];
    }
}
