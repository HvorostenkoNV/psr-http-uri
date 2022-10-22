<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\IpAddress;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\IpAddressV6Rules;

use function array_filter;
use function array_pop;
use function count;
use function explode;
use function implode;
use function is_array;
use function ltrim;
use function preg_match;
use function preg_match_all;
use function preg_quote;
use function preg_replace;
use function str_ends_with;
use function strlen;
use function trim;

class V6 implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString = (string) $value;
        $valueTrim   = trim($valueString);
        $delimiter   = IpAddressV6Rules::DELIMITER->value;

        try {
            $valueExploded        = explode($delimiter, $valueTrim);
            $lastPart             = array_pop($valueExploded);
            $valueV4Part          = V4::normalize($lastPart);
            $valueV6Part          = implode($delimiter, $valueExploded);
            $hasDelimiterInTheEnd = str_ends_with($valueV6Part, $delimiter);
            $valueV6Part          = $hasDelimiterInTheEnd ? $valueV6Part.$delimiter : $valueV6Part;
            $isDual               = true;
        } catch (NormalizingException) {
            $valueV4Part          = '';
            $valueV6Part          = $valueTrim;
            $isDual               = false;
        }

        $valueV6PartNormalized = self::normalizeV6WithoutV4Part($valueV6Part, $isDual);
        $hasShortenInTheEnd    = str_ends_with($valueV6PartNormalized, IpAddressV6Rules::SHORTEN);

        return match (true) {
            $isDual && $hasShortenInTheEnd  => $valueV6PartNormalized.$valueV4Part,
            $isDual                         => $valueV6PartNormalized.$delimiter.$valueV4Part,
            default                         => $valueV6PartNormalized,
        };
    }

    /**
     * @throws NormalizingException
     */
    private static function normalizeV6WithoutV4Part(string $value, bool $hasV4Part): string
    {
        $isShortened = self::checkV6IsShortened($value);
        $valueParts  = self::splitV6IntoParts($value, $hasV4Part, $isShortened);

        foreach ($valueParts as $index => $part) {
            if (!$isShortened || strlen($part) > 0) {
                $valueParts[$index] = self::normalizeV6Segment($part);
            }
        }

        $valueImploded = implode(IpAddressV6Rules::DELIMITER->value, $valueParts);

        return !$isShortened
            ? self::convertV6ToShortFormat($valueImploded)
            : $valueImploded;
    }

    /**
     * @throws NormalizingException
     */
    private static function checkV6IsShortened(string $value): bool
    {
        $delimiter            = IpAddressV6Rules::DELIMITER->value;
        $shortsCount          = (int) preg_match_all("/[{$delimiter}]{2}/", $value);
        $incorrectShortsCount = (int) preg_match_all("/[{$delimiter}]{3,}/", $value);

        if ($shortsCount > 1 || $incorrectShortsCount > 0) {
            throw new NormalizingException("ip address V6 part [{$value}] "
                .'contains incorrect shortens');
        }

        return $shortsCount === 1;
    }

    /**
     * @throws NormalizingException
     */
    private static function splitV6IntoParts(
        string $value,
        bool $hasV4Part,
        bool $isShortened
    ): array {
        $delimiter          = IpAddressV6Rules::DELIMITER->value;
        $valueParts         = explode($delimiter, $value);
        $valueNonEmptyParts = array_filter($valueParts, fn ($value) => strlen($value) > 0);
        $currentPartsCount  = count($valueNonEmptyParts);
        $needPartsCount     = $hasV4Part
            ? IpAddressV6Rules::PARTS_COUNT_WITHOUT_V4
            : IpAddressV6Rules::PARTS_COUNT;
        $hasInvalidStart    =
            $value[0] === $delimiter
            && $value[1] !== $delimiter;
        $hasInvalidEnd      =
            $value[strlen($value) - 1] === $delimiter
            && $value[strlen($value) - 2] !== $delimiter;

        if (strlen($value) <= 1 || $hasInvalidStart || $hasInvalidEnd) {
            throw new NormalizingException("ip address V6 part [{$value}] has incorrect format");
        }

        if (
            !$isShortened && $currentPartsCount !== $needPartsCount
            || $isShortened && $currentPartsCount > $needPartsCount - 2
        ) {
            throw new NormalizingException("ip address V6 part [{$value}] "
                .'contains incorrect segments count');
        }

        return $valueParts;
    }

    private static function convertV6ToShortFormat(string $value): string
    {
        $delimiter    = IpAddressV6Rules::DELIMITER->value;
        $shorten      = IpAddressV6Rules::SHORTEN;
        $longestValue = '';

        preg_match_all("/([{$delimiter}]?0[{$delimiter}]?)+/", $value, $matches);

        $foundMatches = isset($matches[0]) && is_array($matches[0]) ? $matches[0] : [];

        foreach ($foundMatches as $match) {
            if (strlen($match) > strlen($longestValue)) {
                $longestValue = $match;
            }
        }

        return strlen($longestValue) > 3
            ? preg_replace('/'.preg_quote($longestValue).'/', $shorten, $value, 1)
            : $value;
    }

    /**
     * @throws NormalizingException
     */
    private static function normalizeV6Segment(string $value): string
    {
        $mask         = IpAddressV6Rules::mask();
        $minimalValue = '0';

        $matches = [];
        preg_match($mask, $value, $matches);
        $isMatch = isset($matches[0]) && $matches[0] === $value;

        if (!$isMatch) {
            throw new NormalizingException("ip address V6 segment [{$value}] "
                ."does not matched the pattern {$mask}");
        }

        $valueTrim = ltrim($value, $minimalValue);

        return strlen($valueTrim) === 0 ? $minimalValue : $valueTrim;
    }
}
