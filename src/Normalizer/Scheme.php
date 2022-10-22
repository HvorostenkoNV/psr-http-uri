<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\SchemeRules;

use function preg_match;
use function strtolower;

class Scheme implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);
        $mask           = SchemeRules::mask();

        $matches = [];
        preg_match($mask, $valueLowercase, $matches);
        $isMatch = isset($matches[0]) && $matches[0] === $valueLowercase;

        if (!$isMatch) {
            throw new NormalizingException("scheme [{$valueLowercase}] "
                ."does not match pattern $mask");
        }

        return $valueLowercase;
    }
}
