<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\DomainNameRules;

use function preg_match;
use function strlen;
use function strtolower;

class TopLevelDomain implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);
        $minLength      = DomainNameRules::TOP_LEVEL_MIN_LENGTH;
        $maxLength      = DomainNameRules::TOP_LEVEL_MAX_LENGTH;

        if (strlen($valueLowercase) < $minLength) {
            throw new NormalizingException(
                "top-level domain [{$valueString}] is shorter than {$minLength}"
            );
        }
        if (strlen($valueLowercase) > $maxLength) {
            throw new NormalizingException(
                "top-level domain [{$valueString}] is longer than {$maxLength}"
            );
        }

        $matches = [];
        preg_match(DomainNameRules::topLevelMask(), $valueLowercase, $matches);
        $isMatch = isset($matches[0]) && $matches[0] === $valueLowercase;

        if (!$isMatch) {
            throw new NormalizingException("top-level domain [{$valueString}] is invalid");
        }

        return $valueLowercase;
    }
}
