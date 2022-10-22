<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\NormalizerInterface;
use HNV\Http\Uri\Collection\QueryRules;

use function explode;
use function implode;
use function rawurldecode;
use function rawurlencode;
use function str_replace;
use function strlen;

class Query implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString         = (string) $value;
        $fieldsSeparator     = QueryRules::FIELDS_SEPARATOR->value;
        $fieldValueSeparator = QueryRules::FIELD_VALUE_SEPARATOR->value;
        $valueExploded       = explode($fieldsSeparator, $valueString);
        $result              = [];

        foreach ($valueExploded as $part) {
            $partExploded = explode($fieldValueSeparator, $part, 2);
            $key          = $partExploded[0];
            $keyValue     = $partExploded[1] ?? '';

            if (strlen($key) === 0) {
                continue;
            }

            $keyNormalized      = self::normalizeValue($key);
            $keyValueNormalized = strlen($keyValue) > 0 ? self::normalizeValue($keyValue) : null;
            $result[]           = $keyValueNormalized
                ? $keyNormalized.$fieldValueSeparator.$keyValueNormalized
                : $keyNormalized;
        }

        return implode($fieldsSeparator, $result);
    }

    private static function normalizeValue(string $value): string
    {
        $result                 = rawurlencode(rawurldecode($value));
        $allowedChars           = [];
        $allowedCharsEncoded    = [];

        foreach (QueryRules::ALLOWED_CHARACTERS as $case) {
            $allowedChars[]        = $case->value;
            $allowedCharsEncoded[] = rawurlencode($case->value);
        }

        return str_replace($allowedCharsEncoded, $allowedChars, $result);
    }
}
