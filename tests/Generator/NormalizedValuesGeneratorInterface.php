<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator;

use HNV\Http\Helper\Generator\GeneratorInterface;
use HNV\Http\UriTests\ValueObject\NormalizedValue;

interface NormalizedValuesGeneratorInterface extends GeneratorInterface
{
    /**
     * @return NormalizedValue[]
     */
    public function generate(): iterable;
}
