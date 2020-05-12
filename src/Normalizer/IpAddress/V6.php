<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\IpAddress;

use HNV\Http\Uri\Normalizer\{
    NormalizingException,
    NormalizerInterface
};

use function strlen;
use function substr;
use function trim;
use function ltrim;
use function implode;
use function explode;
use function is_array;
use function count;
use function array_pop;
use function array_filter;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function preg_quote;
/** ***********************************************************************************************
 * IP address V6 normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class V6 implements NormalizerInterface
{
    public const PARTS_COUNT            = 8;
    public const PARTS_COUNT_WITHOUT_V4 = 6;
    public const DELIMITER              = ':';
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
    {
        $valueString    = (string) $value;
        $valueTrim      = trim($valueString);
        $delimiter      = self::DELIMITER;
        $shorten        = $delimiter.$delimiter;

        try {
            $valueExploded  = explode($delimiter, $valueTrim);
            $lastPart       = array_pop($valueExploded);
            $valueV4Part    = V4::normalize($lastPart);
            $valueV6Part    = implode($delimiter, $valueExploded);
            $valueV6Part    = substr($valueV6Part, strlen($delimiter) * -1) === $delimiter
                ? $valueV6Part.$delimiter
                : $valueV6Part;
            $isDual         = true;
        } catch (NormalizingException $exception) {
            $valueV4Part    = '';
            $valueV6Part    = $valueTrim;
            $isDual         = false;
        }

        try {
            $valueV6PartNormalized  = self::normalizeWithoutV4Part($valueV6Part, $isDual);
            $hasShortenInTheEnd     = substr($valueV6PartNormalized, strlen($shorten) * -1) === $shorten;

            if ($isDual && $hasShortenInTheEnd) {
                return $valueV6PartNormalized.$valueV4Part;
            }
            if ($isDual) {
                return $valueV6PartNormalized.$delimiter.$valueV4Part;
            }

            return $valueV6PartNormalized;
        } catch (NormalizingException $exception) {
            throw new NormalizingException(
                "ip address V6 \"$valueString\" normalization failed",
                0,
                $exception
            );
        }
    }
    /** **********************************************************************
     * Normalize IP address without v4 IP address postfix.
     *
     * @param   string  $value              IP address without v4 IP address postfix.
     * @param   bool    $hasV4Part          IP address was with v4 IP address postfix.
     *
     * @return  string                      Normalized IP address.
     * @throws  NormalizingException        Normalizing error.
     ************************************************************************/
    private static function normalizeWithoutV4Part(string $value, bool $hasV4Part): string
    {
        $delimiter              = self::DELIMITER;
        $valueExploded          = explode($delimiter, $value);
        $valueNonEmptyParts     = array_filter($valueExploded, function($value) {
            return strlen($value) > 0;
        });
        $currentPartsCount      = count($valueNonEmptyParts);
        $needPartsCount         = $hasV4Part ? self::PARTS_COUNT_WITHOUT_V4 : self::PARTS_COUNT;
        $shortsCount            = (int) preg_match_all("/[$delimiter]{2}/",     $value);
        $incorrectShortsCount   = (int) preg_match_all("/[$delimiter]{3,}/",    $value);
        $isShortened            = $shortsCount === 1;

        if ($shortsCount > 1 || $incorrectShortsCount > 0) {
            throw new NormalizingException(
                "ip address V6 part \"$value\" contains incorrect shortens"
            );
        }
        if (
            strlen($value) < 2  || (
                $value[0] === $delimiter &&
                $value[1] !== $delimiter
            )                       || (
                $value[strlen($value) - 1] === $delimiter &&
                $value[strlen($value) - 2] !== $delimiter
            )
        ) {
            throw new NormalizingException(
                "ip address V6 part \"$value\" has incorrect format"
            );
        }
        if (
            !$isShortened   && $currentPartsCount != $needPartsCount ||
            $isShortened    && $currentPartsCount >  $needPartsCount - 2
        ) {
            throw new NormalizingException(
                "ip address V6 part \"$value\" contains incorrect segments count"
            );
        }

        foreach ($valueExploded as $index => $part) {
            if ($isShortened && strlen($part) === 0) {
                continue;
            }

            try {
                $valueExploded[$index] = self::normalizeSegment($part);
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "ip address V6 segment \"$part\" is invalid",
                    0,
                    $exception
                );
            }
        }

        $valueImploded = implode($delimiter, $valueExploded);

        return !$isShortened
            ? self::convertToShortFormat($valueImploded)
            : $valueImploded;
    }
    /** **********************************************************************
     * Convert IP address to short format.
     *
     * @param   string $value               IP address.
     *
     * @return  string                      Converted IP address to short format.
     ************************************************************************/
    private static function convertToShortFormat(string $value): string
    {
        $delimiter      = self::DELIMITER;
        $shorten        = $delimiter.$delimiter;
        $longestValue   = '';

        preg_match_all("/([$delimiter]?0[$delimiter]?)+/", $value, $matches);

        $foundMatches   = isset($matches[0]) && is_array($matches[0]) ? $matches[0] : [];

        foreach ($foundMatches as $match) {
            if (strlen($match) > strlen($longestValue)) {
                $longestValue = $match;
            }
        }

        return strlen($longestValue) > 3
            ? preg_replace('/'.preg_quote($longestValue).'/', $shorten, $value, 1)
            : $value;
    }
    /** **********************************************************************
     * Normalize the v6 IP address segment.
     *
     * @param   string $value               V6 IP address segment.
     *
     * @return  string                      Normalized v6 IP address segment.
     * @throws  NormalizingException        Normalizing error.
     ************************************************************************/
    private static function normalizeSegment(string $value): string
    {
        $mask       = '/^[0-9a-fA-F]{1,4}$/';
        $matches    = [];

        preg_match($mask, $value, $matches);

        if (!isset($matches[0]) || $matches[0] !== $value) {
            throw new NormalizingException("value \"$value\" does not matched the pattern $mask");
        }

        $valueTrim = ltrim($value, '0');

        return strlen($valueTrim) === 0
            ? '0'
            : $valueTrim;
    }
}