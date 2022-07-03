<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\{
    QueryAllowedCharacters,
    UriSubDelimiters,
};

use function explode;
use function implode;
use function rawurldecode;
use function rawurlencode;
use function str_replace;
use function strlen;

/**
 * URI query string normalizer.
 */
class Query implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString         = (string) $value;
        $fieldsSeparator     = UriSubDelimiters::QUERY_FIELDS_SEPARATOR->value;
        $fieldValueSeparator = UriSubDelimiters::QUERY_FIELD_VALUE_SEPARATOR->value;
        $valueExploded       = explode($fieldsSeparator, $valueString);
        $result              = [];

        foreach ($valueExploded as $part) {
            $partExploded = explode($fieldValueSeparator, $part, 2);
            $key          = $partExploded[0];
            $keyValue     = $partExploded[1] ?? '';

            if (strlen($key) === 0) {
                continue;
            }

            try {
                $keyNormalized = self::normalizeValue($key);
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "query part \"{$key}\" is invalid",
                    0,
                    $exception
                );
            }

            try {
                $keyValueNormalized = strlen($keyValue) > 0 ? self::normalizeValue($keyValue) : null;
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "query part \"{$keyValue}\" is invalid",
                    0,
                    $exception
                );
            }

            $result[] = $keyValueNormalized
                ? $keyNormalized.$fieldValueSeparator.$keyValueNormalized
                : $keyNormalized;
        }

        return implode($fieldsSeparator, $result);
    }

    /**
     * Normalize query value.
     *
     * @param string $value query value
     *
     * @throws NormalizingException normalizing error
     *
     * @return string normalized query value
     */
    private static function normalizeValue(string $value): string
    {
        if (strlen($value) === 0) {
            throw new NormalizingException('value is empty string');
        }

        $result = rawurlencode(rawurldecode($value));

        foreach (QueryAllowedCharacters::cases() as $case) {
            $charEncoded = rawurlencode($case->value);
            $result      = str_replace($charEncoded, $case->value, $result);
        }

        return $result;
    }
}
