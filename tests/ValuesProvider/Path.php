<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\PathRules;

use function array_diff;
use function array_map;
use function array_merge;
use function array_shift;
use function count;
use function implode;
use function rawurlencode;
use function str_repeat;
use function strtoupper;
use function ucfirst;

/**
 * URI path normalized values collection.
 */
class Path implements ValuesProviderInterface
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
        $validValues = self::getValidValues();
        $result      = [];

        foreach (PathRules::ALLOWED_CHARACTERS_NON_FIRST as $case) {
            foreach ($validValues as $value) {
                $result[] = "{$case->value}{$value}";
            }
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

        for ($partsCount = 1; $partsCount <= 5; $partsCount++) {
            $parts = [];

            while (count($parts) < $partsCount) {
                $parts[] = $getValidPart();
            }

            $result[] = implode(PathRules::PARTS_SEPARATOR->value, $parts);
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
        $separator       = PathRules::PARTS_SEPARATOR->value;
        $normalizedParts = self::getValidNormalizedParts();
        $simplePart      = self::getValidSimpleParts()[0];
        $result          = [];

        foreach ($normalizedParts as $value => $valueNormalized) {
            $result[$value] = $valueNormalized;
        }

        foreach ([
            $separator,
            $separator.$simplePart,
            $simplePart.$separator,
            $separator.$simplePart.$separator,
        ] as $value) {
            $result[$value] = $value;
        }

        for ($repeatCount = 1; $repeatCount <= 3; $repeatCount++) {
            $separatorRepeated = str_repeat($separator, $repeatCount);

            foreach ([
                $separatorRepeated,
                $separatorRepeated.$simplePart,
                $simplePart.$separatorRepeated,
                $separatorRepeated.$simplePart.$separatorRepeated,
            ] as $value) {
                $result[$value] = $value;
            }
        }

        return $result;
    }

    /**
     * Get valid simple (without need for normalizing) values parts set.
     *
     * @return string[] values set
     */
    private static function getValidSimpleParts(): array
    {
        $letter = 'p';
        $digit  = 1;
        $string = 'path';

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
        $string             = 'path';
        $invalidFirstChars  = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            PathRules::ALLOWED_CHARACTERS_NON_FIRST
        );
        $allowedChars       = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            PathRules::ALLOWED_CHARACTERS_OTHERS
        );
        $otherChars         = array_diff(
            SpecialCharacters::casesValues(),
            $allowedChars,
            $invalidFirstChars,
            [PathRules::PARTS_SEPARATOR->value]
        );
        $result = [];

        foreach ($allowedChars as $char) {
            $charEncoded          = rawurlencode($char);
            $result[$char]        = $char;
            $result[$charEncoded] = $char;
        }
        foreach ($invalidFirstChars as $char) {
            $charEncoded                  = rawurlencode($char);
            $result[$string.$char]        = $string.$char;
            $result[$string.$charEncoded] = $string.$char;
        }
        foreach (array_merge($otherChars, [' ']) as $char) {
            $charEncoded          = rawurlencode($char);
            $result[$char]        = $charEncoded;
            $result[$charEncoded] = $charEncoded;
        }

        return $result;
    }
}
