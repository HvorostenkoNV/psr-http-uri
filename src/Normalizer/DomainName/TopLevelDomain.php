<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Normalizer\RegularExpressionCheckerTrait;

use function strlen;
use function strtolower;

/**
 * Top level domain normalizer.
 */
class TopLevelDomain implements NormalizerInterface
{
    use RegularExpressionCheckerTrait;

    public const MIN_LENGTH = 2;
    public const MAX_LENGTH = 6;
    private const MASK      = '/^[a-z]{1,}$/';

    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);
        $minLength      = self::MIN_LENGTH;
        $maxLength      = self::MAX_LENGTH;

        if (strlen($valueLowercase) < $minLength) {
            throw new NormalizingException(
                "top level domain \"{$valueString}\" is shorter than {$minLength}"
            );
        }
        if (strlen($valueLowercase) > $maxLength) {
            throw new NormalizingException(
                "top level domain \"{$valueString}\" is longer than {$maxLength}"
            );
        }

        if (!self::checkRegularExpressionMatch($valueLowercase)) {
            throw new NormalizingException(
                "top level domain \"{$valueString}\" is invalid"
            );
        }

        return $valueLowercase;
    }

    /**
     * {@inheritDoc}
     */
    protected static function buildRegularExpressionMask(): string
    {
        return self::MASK;
    }
}
