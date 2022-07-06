<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider\UserInfo;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\UriTests\ValuesProvider\ValuesProviderInterface;

use function rawurlencode;
use function strtoupper;
use function ucfirst;

/**
 * URI user (login or password) info values provider.
 */
class Value implements ValuesProviderInterface
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
        $letter = 'u';
        $digit  = 1;
        $string = 'user';

        return [
            $string,
            strtoupper($string),
            ucfirst($string),

            "{$digit}",
            $letter,

            "{$digit}{$string}",
            "{$string}{$digit}",
            "{$string}{$digit}{$string}",
        ];
    }

    /**
     * Get valid values with their normalized representation set.
     *
     * @return string[] values set
     */
    private static function getValidNormalizedValues(): array
    {
        $result = [];

        foreach (SpecialCharacters::casesValues() as $char) {
            $charEncoded          = rawurlencode($char);
            $result[$char]        = $charEncoded;
            $result[$charEncoded] = $charEncoded;
        }

        return $result;
    }
}
