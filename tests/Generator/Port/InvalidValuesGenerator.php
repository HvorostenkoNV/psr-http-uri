<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Port;

use HNV\Http\Uri\Collection\PortRules;
use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\InvalidValue,
};

use function rand;

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    public function generate(): iterable
    {
        yield new InvalidValue(PortRules::MIN_VALUE - 1);
        yield new InvalidValue(PortRules::MAX_VALUE + 1);

        for ($iteration = 5; $iteration > 0; $iteration--) {
            yield new InvalidValue(rand(PortRules::MIN_VALUE - 100, PortRules::MIN_VALUE - 2));
            yield new InvalidValue(rand(PortRules::MAX_VALUE + 2, PortRules::MAX_VALUE + 100));
        }
    }
}
