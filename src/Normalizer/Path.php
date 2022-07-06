<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\{
    PathAllowedCharactersAny,
    PathAllowedCharactersNonFirst,
    UriSubDelimiters,
};

use function array_map;
use function array_merge;
use function explode;
use function implode;
use function rawurldecode;
use function rawurlencode;
use function str_replace;
use function strlen;

/**
 * URI path normalizer.
 */
class Path implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString       = (string) $value;
        $valueExploded     = explode(UriSubDelimiters::PATH_PARTS_SEPARATOR->value, $valueString);
        $invalidFirstChars = PathAllowedCharactersNonFirst::casesValues();
        $result            = [];

        foreach ($invalidFirstChars as $char) {
            if ($valueString[0] === $char) {
                throw new NormalizingException(
                    "path \"{$valueString}\" can not begin with character \"{$char}\""
                );
            }
        }

        foreach ($valueExploded as $part) {
            try {
                $result[] = strlen($part) > 0 ? self::normalizePart($part) : '';
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "path part \"{$part}\" validation failed",
                    0,
                    $exception
                );
            }
        }

        return implode(UriSubDelimiters::PATH_PARTS_SEPARATOR->value, $result);
    }

    /**
     * Normalize path part value.
     *
     * @throws NormalizingException normalizing error
     */
    private static function normalizePart(string $value): string
    {
        if (strlen($value) === 0) {
            throw new NormalizingException('value is empty string');
        }

        $result              = rawurlencode(rawurldecode($value));
        $allowedChars        = array_merge(
            PathAllowedCharactersNonFirst::casesValues(),
            PathAllowedCharactersAny::casesValues(),
        );
        $allowedCharsEncoded = array_map(fn (string $value): string => rawurlencode($value), $allowedChars);

        return str_replace($allowedCharsEncoded, $allowedChars, $result);
    }
}
