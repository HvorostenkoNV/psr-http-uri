<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\{
    QueryAllowedCharacters,
    UriSubDelimiters,
};

use function array_diff;
use function array_merge;
use function array_shift;
use function count;
use function implode;
use function rawurlencode;
use function strtoupper;
use function ucfirst;

/**
 * URI query normalized values collection.
 */
class Query implements ValuesProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getValidValues(): array
    {
        $result = [];

        foreach (self::getValidSimpleValues() as $value) {
            $result[$value] = $value;
        }
        foreach (self::getValidNormalizedValues() as $value => $valueNormalized) {
            $result[$value] = $valueNormalized;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public static function getInvalidValues(): array
    {
        $fieldsDelimiter = UriSubDelimiters::QUERY_FIELDS_SEPARATOR->value;
        $pairDelimiter   = UriSubDelimiters::QUERY_FIELD_VALUE_SEPARATOR->value;
        $validPart       = self::getValidSimpleParts()[0];
        $invalidParts    = self::getInvalidParts();
        $result          = [];

        foreach ($invalidParts as $invalidPart) {
            $result[] = $invalidPart;
            $result[] = $invalidPart.$pairDelimiter.$validPart;
            $result[] = $validPart.$pairDelimiter.$invalidPart;
            $result[] = $validPart.$fieldsDelimiter.$invalidPart;
        }

        return $result;
    }

    /**
     * Get valid simple (without need for normalizing) values set.
     *
     * @return string[] values set
     */
    private static function getValidSimpleValues(): array
    {
        $validParts   = [];
        $getValidPart = function () use ($validParts) {
            if (count($validParts) === 0) {
                $validParts = self::getValidSimpleParts();
            }

            return array_shift($validParts);
        };
        $result = [];

        for ($pairsCount = 1; $pairsCount <= 5; $pairsCount++) {
            $pairs = [];

            while (count($pairs) < $pairsCount) {
                $pairs[] = $getValidPart();
            }

            $result[] = implode(UriSubDelimiters::QUERY_FIELDS_SEPARATOR->value, $pairs);
        }
        for ($pairsCount = 1; $pairsCount <= 5; $pairsCount++) {
            $pairs = [];

            while (count($pairs) < $pairsCount) {
                $key     = $getValidPart();
                $value   = $getValidPart();
                $pairs[] = $key.UriSubDelimiters::QUERY_FIELD_VALUE_SEPARATOR->value.$value;
            }

            $result[] = implode(UriSubDelimiters::QUERY_FIELDS_SEPARATOR->value, $pairs);
        }

        return $result;
    }

    /**
     * Get valid values with their normalized representation set.
     *
     * @return string[] values set
     */
    private static function getValidNormalizedValues(): array
    {
        $fieldsDelimiter      = UriSubDelimiters::QUERY_FIELDS_SEPARATOR->value;
        $pairDelimiter        = UriSubDelimiters::QUERY_FIELD_VALUE_SEPARATOR->value;
        $validNormalizedParts = self::getValidNormalizedParts();
        $validSimplePart      = self::getValidSimpleParts()[0];
        $result               = [];

        foreach ($validNormalizedParts as $value => $valueNormalized) {
            $result[$value]                                 = $valueNormalized;
            $result[$value.$pairDelimiter.$validSimplePart] = $valueNormalized.$pairDelimiter.$validSimplePart;
            $result[$validSimplePart.$pairDelimiter.$value] = $validSimplePart.$pairDelimiter.$valueNormalized;
        }

        $result[$fieldsDelimiter.$validSimplePart]                                   = $validSimplePart;
        $result[$validSimplePart.$fieldsDelimiter]                                   = $validSimplePart;
        $result[$fieldsDelimiter.$validSimplePart.$fieldsDelimiter]                  = $validSimplePart;
        $result[$validSimplePart.$fieldsDelimiter.$fieldsDelimiter.$validSimplePart] =
            $validSimplePart.$fieldsDelimiter.$validSimplePart;
        $result[$fieldsDelimiter]                  = '';
        $result[$fieldsDelimiter.$fieldsDelimiter] = '';
        $result[$validSimplePart.$pairDelimiter]   = $validSimplePart;

        return $result;
    }

    /**
     * Get valid simple (without need for normalizing) values parts set.
     *
     * @return string[] values set
     */
    private static function getValidSimpleParts(): array
    {
        $letter = 'q';
        $digit  = 1;
        $string = 'query';

        return [
            $string,
            strtoupper($string),
            ucfirst($string),

            "{$string}{$digit}",
            "{$string}{$digit}{$string}",
            "{$digit}{$string}",

            $letter,
            "{$digit}",
        ];
    }

    /**
     * Get valid parts with their normalized representation set.
     *
     * @return string[] values set
     */
    private static function getValidNormalizedParts(): array
    {
        $allowedChars = QueryAllowedCharacters::casesValues();
        $otherChars   = array_diff(
            SpecialCharacters::casesValues(),
            $allowedChars,
            [
                UriSubDelimiters::QUERY_FIELDS_SEPARATOR->value,
                UriSubDelimiters::QUERY_FIELD_VALUE_SEPARATOR->value,
            ]
        );
        $result = [];

        foreach ($allowedChars as $char) {
            $charEncoded          = rawurlencode($char);
            $result[$char]        = $char;
            $result[$charEncoded] = $char;
        }
        foreach (array_merge($otherChars, [' ']) as $char) {
            $charEncoded          = rawurlencode($char);
            $result[$char]        = $charEncoded;
            $result[$charEncoded] = $charEncoded;
        }

        return $result;
    }

    /**
     * Get invalid values parts set.
     *
     * @return string[] values set
     */
    private static function getInvalidParts(): array
    {
        return [];
    }
}
