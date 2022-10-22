<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\DomainNameRules;

use function count;
use function explode;
use function implode;

class FullQualifiedDomainName implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString        = (string) $value;
        $valueExploded      = explode(DomainNameRules::LEVELS_DELIMITER->value, $valueString);
        $normalizedParts    = [];

        if (count($valueExploded) < 2) {
            throw new NormalizingException("domain [{$valueString}] has not enough parts");
        }

        foreach ($valueExploded as $index => $subDomain) {
            $isLastPart         = $index + 1 === count($valueExploded);
            $normalizedParts[]  = $isLastPart
                ? TopLevelDomain::normalize($subDomain)
                : SubLevelDomain::normalize($subDomain);
        }

        return implode(DomainNameRules::LEVELS_DELIMITER->value, $normalizedParts);
    }
}
