<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator;

use HNV\Http\Helper\Generator\GeneratorInterface;
use HNV\Http\UriTests\ValueObject\InvalidValue;

interface InvalidValuesGeneratorInterface extends GeneratorInterface
{
    /**
     * @return InvalidValue[]
     */
    public function generate(): iterable;
}
