<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Port;

use HNV\Http\Uri\Collection\PortRules;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function rand;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    public function generate(): iterable
    {
        yield new NormalizedValue(PortRules::MAX_VALUE, PortRules::MAX_VALUE);

        for ($iteration = 10; $iteration > 0; $iteration--) {
            $randomValue = rand(PortRules::MIN_VALUE + 1, PortRules::MAX_VALUE - 1);

            yield new NormalizedValue($randomValue, $randomValue);
        }
    }
}
