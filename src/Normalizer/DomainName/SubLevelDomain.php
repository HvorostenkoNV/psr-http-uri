<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\DomainNameRules;

use function preg_match;
use function strtolower;

class SubLevelDomain implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);

        foreach (DomainNameRules::subLevelMasks() as $mask) {
            $matches = [];
            preg_match($mask, $valueLowercase, $matches);
            $isMatch = isset($matches[0]) && $matches[0] === $valueLowercase;

            if (!$isMatch) {
                throw new NormalizingException("sub-level domain [{$valueString}] "
                    ."does not match pattern $mask");
            }
        }

        return $valueLowercase;
    }
}
