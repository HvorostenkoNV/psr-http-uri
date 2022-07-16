<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\{
    DomainNameRules,
    UriDelimiters,
};

use function array_diff;
use function array_map;
use function array_shift;
use function count;
use function implode;
use function str_repeat;
use function strtolower;
use function strtoupper;
use function ucfirst;

/**
 * URI domain name values provider.
 */
class DomainName implements ValuesProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getValidValues(): array
    {
        $result = [];

        foreach (self::getAllValidValuesCombinations() as $value) {
            $result[$value] = strtolower($value);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public static function getInvalidValues(): array
    {
        $subDomainPartValid = self::getValidSubLevelDomainParts()[0];
        $topDomainPartValid = self::getValidTopLevelDomainParts()[0];
        $partsDelimiter     = DomainNameRules::LEVELS_DELIMITER->value;
        $result             = [];

        foreach (self::getInvalidSubLevelDomainParts() as $subPart) {
            $result[] = $subPart.$partsDelimiter.$topDomainPartValid;
        }
        foreach (self::getInvalidTopLevelDomainParts() as $topPart) {
            $result[] = $subDomainPartValid.$partsDelimiter.$topPart;
        }

        $result[] = $subDomainPartValid;
        $result[] = $topDomainPartValid;
        $result[] = $subDomainPartValid.$topDomainPartValid;
        $result[] = $partsDelimiter.$subDomainPartValid.$partsDelimiter.$topDomainPartValid;
        $result[] = $subDomainPartValid.$partsDelimiter.$partsDelimiter.$topDomainPartValid;
        $result[] = $subDomainPartValid.$partsDelimiter.$topDomainPartValid.$partsDelimiter;

        return $result;
    }

    /**
     * Get all valid values combinations set.
     *
     * @return string[] values set
     */
    private static function getAllValidValuesCombinations(): array
    {
        $subDomainParts   = [];
        $getSubDomainPart = function () use ($subDomainParts) {
            if (count($subDomainParts) === 0) {
                $subDomainParts = self::getValidSubLevelDomainParts();
            }

            return array_shift($subDomainParts);
        };
        $topDomainParts = self::getValidTopLevelDomainParts();
        $partsDelimiter = DomainNameRules::LEVELS_DELIMITER->value;
        $result         = [];

        for ($partsCount = 1; $partsCount <= 4; $partsCount++) {
            $parts = [];

            for ($iteration = $partsCount; $iteration > 0; $iteration--) {
                $parts[] = $getSubDomainPart();
            }

            $parts[]  = $topDomainParts[0];
            $result[] = implode($partsDelimiter, $parts);
        }

        foreach ($topDomainParts as $topDomainPart) {
            $result[] = $getSubDomainPart().$partsDelimiter.$topDomainPart;
        }

        return $result;
    }

    /**
     * Get valid domain name sublevel parts set.
     *
     * @return string[] values set
     */
    private static function getValidSubLevelDomainParts(): array
    {
        $letter = 'd';
        $digit  = 1;
        $string = 'domain';

        $result = [
            $string,
            strtoupper($string),
            ucfirst($string),

            "{$digit}{$string}",
            "{$string}{$digit}",
            "{$string}{$digit}{$string}",

            $letter,
            strtoupper($letter),
            "{$digit}",
        ];

        foreach (DomainNameRules::SUB_LEVEL_ALLOWED_CHARACTERS as $case) {
            $result[] = "{$string}{$case->value}{$string}";
            $result[] = "{$digit}{$case->value}{$digit}";
            $result[] = "{$string}{$case->value}{$digit}";
            $result[] = "{$digit}{$case->value}{$string}";
        }

        return $result;
    }

    /**
     * Get invalid domain name sublevel parts set.
     *
     * @return string[] values set
     */
    private static function getInvalidSubLevelDomainParts(): array
    {
        $letter = 'd';
        $string = 'domain';

        $allowedChars   = array_map(
            fn (SpecialCharacters $char): string => $char->value,
            DomainNameRules::SUB_LEVEL_ALLOWED_CHARACTERS
        );
        $uriDelimiters  = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            UriDelimiters::generalDelimiters()
        );
        $otherChars     = array_diff(
            SpecialCharacters::casesValues(),
            $uriDelimiters,
            $allowedChars,
            [DomainNameRules::LEVELS_DELIMITER->value]
        );

        $result = [
            "{$string} ",
            " {$string}",
            "{$string} {$string}",
        ];

        foreach ($allowedChars as $char) {
            $result[] = "{$char}{$string}";
            $result[] = "{$string}{$char}";
        }
        foreach ($otherChars as $char) {
            $result[] = "{$char}{$string}";
            $result[] = "{$string}{$char}";
            $result[] = "{$string}{$char}{$string}";
        }

        $result[] = str_repeat($letter, DomainNameRules::SUB_LEVEL_MAX_LENGTH + 1);

        return $result;
    }

    /**
     * Get valid domain name top level parts set.
     *
     * @return string[] values set
     */
    private static function getValidTopLevelDomainParts(): array
    {
        $letter    = 'd';
        $minLength = DomainNameRules::TOP_LEVEL_MIN_LENGTH;
        $maxLength = DomainNameRules::TOP_LEVEL_MAX_LENGTH;
        $result    = [];

        for ($length = $minLength; $length <= $maxLength; $length++) {
            $value    = str_repeat($letter, $length);
            $result[] = $value;
            $result[] = strtoupper($value);
            $result[] = ucfirst($value);
        }

        return $result;
    }

    /**
     * Get invalid domain name top level parts set.
     *
     * @return string[] values set
     */
    private static function getInvalidTopLevelDomainParts(): array
    {
        $letter = 'd';
        $digit  = 1;
        $string = 'domain';

        $uriDelimiters  = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            UriDelimiters::generalDelimiters()
        );
        $chars          = array_diff(
            SpecialCharacters::casesValues(),
            $uriDelimiters,
            [DomainNameRules::LEVELS_DELIMITER->value]
        );

        $result = [
            "{$string} ",
            " {$string}",
            "{$string} {$string}",
        ];

        for ($length = 1; $length < DomainNameRules::TOP_LEVEL_MIN_LENGTH; $length++) {
            $result[] = str_repeat($letter, $length);
        }

        $result[] = str_repeat($letter, DomainNameRules::TOP_LEVEL_MAX_LENGTH + 1);
        $result[] = str_repeat("{$digit}", DomainNameRules::TOP_LEVEL_MIN_LENGTH);

        foreach ($chars as $char) {
            $result[] = $char;
        }

        return $result;
    }
}
