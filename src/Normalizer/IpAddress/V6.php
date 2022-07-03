<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\IpAddress;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};

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

/**
 * IP address V6 normalizer.
 */
class V6 implements NormalizerInterface
{
    public const PARTS_COUNT            = 8;
    public const PARTS_COUNT_WITHOUT_V4 = 6;
    public const DELIMITER              = ':';
    public const SHORTEN                = self::DELIMITER.self::DELIMITER;
    private const SEGMENT_MASK          = '/^[0-9a-fA-F]{1,4}$/';
    private const SEGMENT_MINIMAL_VALUE = 0;

    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString = (string) $value;
        $valueTrim   = trim($valueString);
        $delimiter   = self::DELIMITER;

        try {
            $valueExploded        = explode($delimiter, $valueTrim);
            $lastPart             = array_pop($valueExploded);
            $valueV4Part          = V4::normalize($lastPart);
            $valueV6Part          = implode($delimiter, $valueExploded);
            $hasDelimiterInTheEnd = str_ends_with($valueV6Part, $delimiter);
            $valueV6Part          = $hasDelimiterInTheEnd ? $valueV6Part.$delimiter : $valueV6Part;
            $isDual               = true;
        } catch (NormalizingException) {
            $valueV4Part = '';
            $valueV6Part = $valueTrim;
            $isDual      = false;
        }

        try {
            $valueV6PartNormalized = self::normalizeWithoutV4Part($valueV6Part, $isDual);
            $hasShortenInTheEnd    = str_ends_with($valueV6PartNormalized, self::SHORTEN);

            if ($isDual && $hasShortenInTheEnd) {
                return $valueV6PartNormalized.$valueV4Part;
            }
            if ($isDual) {
                return $valueV6PartNormalized.$delimiter.$valueV4Part;
            }

            return $valueV6PartNormalized;
        } catch (NormalizingException $exception) {
            throw new NormalizingException(
                "ip address V6 \"{$valueString}\" normalization failed",
                0,
                $exception
            );
        }
    }

    /**
     * Normalize IP address without v4 IP address postfix.
     *
     * @param string $value     IP address without v4 IP address postfix
     * @param bool   $hasV4Part IP address was with v4 IP address postfix
     *
     * @throws NormalizingException normalizing error
     *
     * @return string normalized IP address
     */
    private static function normalizeWithoutV4Part(string $value, bool $hasV4Part): string
    {
        $isShortened = self::checkIsShortened($value);
        $valueParts  = self::splitValueIntoParts($value, $hasV4Part, $isShortened);

        foreach ($valueParts as $index => $part) {
            if ($isShortened && strlen($part) === 0) {
                continue;
            }

            try {
                $valueParts[$index] = self::normalizeSegment($part);
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "ip address V6 segment \"{$part}\" is invalid",
                    0,
                    $exception
                );
            }
        }

        $valueImploded = implode(self::DELIMITER, $valueParts);

        return !$isShortened
            ? self::convertToShortFormat($valueImploded)
            : $valueImploded;
    }

    /**
     * Check IP address v6 is shortened.
     *
     * @param string $value IP address v6
     *
     * @throws NormalizingException something wrong with shortens
     *
     * @return bool IP address is shortened
     */
    private static function checkIsShortened(string $value): bool
    {
        $delimiter            = self::DELIMITER;
        $shortsCount          = (int) preg_match_all("/[{$delimiter}]{2}/", $value);
        $incorrectShortsCount = (int) preg_match_all("/[{$delimiter}]{3,}/", $value);

        if ($shortsCount > 1 || $incorrectShortsCount > 0) {
            throw new NormalizingException(
                "ip address V6 part \"{$value}\" contains incorrect shortens"
            );
        }

        return $shortsCount === 1;
    }

    /**
     * Split ip v6 value into parts.
     *
     * @param string $value       IP address without v4 IP address postfix
     * @param bool   $hasV4Part   IP address was with v4 IP address postfix
     * @param bool   $isShortened IP address v6 is shortened
     *
     * @throws NormalizingException any process error
     *
     * @return string[] split value
     */
    private static function splitValueIntoParts(
        string $value,
        bool $hasV4Part,
        bool $isShortened
    ): array {
        $delimiter          = self::DELIMITER;
        $valueParts         = explode($delimiter, $value);
        $valueNonEmptyParts = array_filter($valueParts, fn ($value) => strlen($value) > 0);
        $currentPartsCount  = count($valueNonEmptyParts);
        $needPartsCount     = $hasV4Part ? self::PARTS_COUNT_WITHOUT_V4 : self::PARTS_COUNT;
        $hasInvalidStart    =
            $value[0] === $delimiter
            && $value[1] !== $delimiter;
        $hasInvalidEnd =
            $value[strlen($value) - 1] === $delimiter
            && $value[strlen($value) - 2] !== $delimiter;

        if (strlen($value) <= 1 || $hasInvalidStart || $hasInvalidEnd) {
            throw new NormalizingException(
                "ip address V6 part \"{$value}\" has incorrect format"
            );
        }

        if (
            !$isShortened && $currentPartsCount !== $needPartsCount
            || $isShortened && $currentPartsCount > $needPartsCount - 2
        ) {
            throw new NormalizingException(
                "ip address V6 part \"{$value}\" contains incorrect segments count"
            );
        }

        return $valueParts;
    }

    /**
     * Convert IP address to short format.
     *
     * @param string $value IP address
     *
     * @return string converted IP address to short format
     */
    private static function convertToShortFormat(string $value): string
    {
        $delimiter    = self::DELIMITER;
        $shorten      = self::SHORTEN;
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
     * Normalize the v6 IP address segment.
     *
     * @param string $value V6 IP address segment
     *
     * @throws NormalizingException normalizing error
     *
     * @return string normalized v6 IP address segment
     */
    private static function normalizeSegment(string $value): string
    {
        $mask         = self::SEGMENT_MASK;
        $minimalValue = (string) self::SEGMENT_MINIMAL_VALUE;
        $matches      = [];

        preg_match($mask, $value, $matches);

        if (!isset($matches[0]) || $matches[0] !== $value) {
            throw new NormalizingException("value \"{$value}\" does not matched the pattern {$mask}");
        }

        $valueTrim = ltrim($value, $minimalValue);

        return strlen($valueTrim) === 0 ? $minimalValue : $valueTrim;
    }
}
