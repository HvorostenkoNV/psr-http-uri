<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Helper\Collection\SpecialCharacters;

use function array_merge;
use function rawurlencode;
use function strtoupper;
use function ucfirst;

/**
 * URI fragment normalized values set provider.
 */
class Fragment implements ValuesProviderInterface
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
        return [];
    }

    /**
     * Get valid simple (without need for normalizing) values set.
     *
     * @return string[] values set
     */
    private static function getValidSimpleValues(): array
    {
        $letter = 'f';
        $digit  = 1;
        $string = 'fragment';
        $result = [
            $string,
            strtoupper($string),
            ucfirst($string),

            "{$string}{$digit}",
            "{$string}{$digit}{$string}",
            "{$digit}{$string}",

            "{$string} ",
            "{$string} {$string}",
            " {$string}",
            ' ',

            $letter,
            "{$digit}",
        ];

        foreach (SpecialCharacters::cases() as $char) {
            $result[] = $char->value;
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
        $allChars = SpecialCharacters::casesValues();
        $result   = [];

        foreach (array_merge($allChars, [' ']) as $char) {
            $result[rawurlencode($char)] = $char;
        }

        return $result;
    }
}
