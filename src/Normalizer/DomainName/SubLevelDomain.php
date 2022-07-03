<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\DomainNameAllowedCharacters;
use HNV\Http\Uri\Normalizer\RegularExpressionCheckerTrait;

use function str_replace;
use function strlen;
use function strtolower;

/**
 * Sub level domain normalizer.
 */
class SubLevelDomain implements NormalizerInterface
{
    use RegularExpressionCheckerTrait;

    public const MAX_LENGTH    = 63;
    private const MASK_PATTERN = '/^([a-z0-9]{1}[a-z0-9#SPECIAL_CHARS#]{0,}[a-z0-9]{1})|([a-z0-9]{1})$/';

    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);
        $maxLength      = self::MAX_LENGTH;

        if (strlen($valueLowercase) > $maxLength) {
            throw new NormalizingException(
                "sub domain \"{$valueString}\" is longer than {$maxLength}"
            );
        }

        if (!self::checkRegularExpressionMatch($valueLowercase)) {
            throw new NormalizingException(
                "sub domain \"{$valueString}\" is invalid"
            );
        }

        return $valueLowercase;
    }

    /**
     * {@inheritDoc}
     */
    protected static function buildRegularExpressionMask(): string
    {
        $specialCharsMask = '';

        foreach (DomainNameAllowedCharacters::cases() as $case) {
            $specialCharsMask .= "\\{$case->value}";
        }

        return str_replace('#SPECIAL_CHARS#', $specialCharsMask, self::MASK_PATTERN);
    }
}
