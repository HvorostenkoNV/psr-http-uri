<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\{
    SchemeAllowedCharacters,
    UriGeneralDelimiters,
};

use function array_column;
use function array_diff;
use function strtolower;
use function strtoupper;
use function ucfirst;

/**
 * URI scheme values provider.
 */
class Scheme implements ValuesProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getValidValues(): array
    {
        $letter = 's';
        $digit  = 1;
        $string = 'scheme';
        $result = [];
        $data   = [
            $string,
            strtoupper($string),
            ucfirst($string),

            "{$string}{$digit}",
            "{$string}{$digit}{$string}",

            "{$letter}{$letter}",
            "{$letter}{$digit}",

            $letter.strtoupper($letter),
            strtoupper($letter).strtoupper($letter),
            strtoupper($letter).$letter,
        ];

        foreach (SchemeAllowedCharacters::cases() as $case) {
            $data[] = "{$string}{$case->value}";
            $data[] = "{$string}{$case->value}{$string}";
        }

        foreach ($data as $value) {
            $result[$value] = strtolower($value);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public static function getInvalidValues(): array
    {
        $letter = 's';
        $digit  = 1;
        $string = 'scheme';

        $allowedChars = array_column(SchemeAllowedCharacters::cases(), 'value');
        $otherChars   = array_diff(
            SpecialCharacters::get(),
            array_column(UriGeneralDelimiters::cases(), 'value'),
            $allowedChars
        );

        $result = [
            "{$string} ",
            " {$string}",
            "{$string} {$string}",

            $letter,
            "{$digit}{$letter}",
            "{$digit}{$string}",
        ];

        foreach ($allowedChars as $char) {
            $result[] = "{$char}{$string}";
        }
        foreach ($otherChars as $char) {
            $result[] = "{$char}{$string}";
            $result[] = "{$string}{$char}";
            $result[] = "{$string}{$char}{$string}";
        }

        return $result;
    }
}
