<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\PortRules;

use function is_numeric;

class Port implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): int
    {
        if (!is_numeric($value)) {
            $valueString = (string) $value;

            throw new NormalizingException("value [{$valueString}] is not numeric");
        }

        $valueInt = (int) $value;
        $minValue = PortRules::MIN_VALUE;
        $maxValue = PortRules::MAX_VALUE;

        if ($valueInt < $minValue) {
            throw new NormalizingException("port [{$valueInt}] is less then {$minValue}");
        }
        if ($valueInt > $maxValue) {
            throw new NormalizingException("port [{$valueInt}] is grater then {$minValue}");
        }

        return $valueInt;
    }
}
