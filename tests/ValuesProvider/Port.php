<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Uri\Collection\PortRules;

use function rand;

/**
 * URI port normalized values set provider.
 */
class Port implements ValuesProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getValidValues(): array
    {
        $result = [
            PortRules::MAX_VALUE => PortRules::MAX_VALUE,
        ];

        for ($iteration = 10; $iteration > 0; $iteration--) {
            $randomValue = rand(PortRules::MIN_VALUE + 1, PortRules::MAX_VALUE - 1);

            $result[$randomValue] = $randomValue;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public static function getInvalidValues(): array
    {
        $result = [
            PortRules::MIN_VALUE - 1,
            PortRules::MAX_VALUE + 1,
        ];

        for ($iteration = 5; $iteration > 0; $iteration--) {
            $result[] = rand(PortRules::MIN_VALUE - 100, PortRules::MIN_VALUE - 2);
            $result[] = rand(PortRules::MAX_VALUE + 2, PortRules::MAX_VALUE + 100);
        }

        return $result;
    }
}
