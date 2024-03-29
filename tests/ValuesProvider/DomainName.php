<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    DomainNameAllowedCharacters,
};
use HNV\Http\Uri\Normalizer\DomainName\{
    FullQualifiedDomainName as FullQualifiedDomainNameNormalizer,
    SubLevelDomain          as SubLevelDomainNormalizer,
    TopLevelDomain          as TopLevelDomainNormalizer,
};

use function str_repeat;
use function strtolower;
use function strtoupper;
use function ucfirst;
use function implode;
use function count;
use function array_shift;
use function array_diff;
/** ***********************************************************************************************
 * URI domain name values provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class DomainName implements ValuesProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function getValidValues(): array
    {
        $result = [];

        foreach (self::getAllValidValuesCombinations() as $value) {
            $result[$value] = strtolower($value);
        }

        return $result;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function getInvalidValues(): array
    {
        $subDomainPartValid = self::getValidSubLevelDomainParts()[0];
        $topDomainPartValid = self::getValidTopLevelDomainParts()[0];
        $partsDelimiter     = FullQualifiedDomainNameNormalizer::PARTS_DELIMITER;
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
    /** **********************************************************************
     * Get all valid values combinations set.
     *
     * @return  string[]                    Values set.
     ************************************************************************/
    private static function getAllValidValuesCombinations(): array
    {
        $subDomainParts     = [];
        $getSubDomainPart   = function() use ($subDomainParts) {
            if (count($subDomainParts) === 0) {
                $subDomainParts = self::getValidSubLevelDomainParts();
            }

            return array_shift($subDomainParts);
        };
        $topDomainParts     = self::getValidTopLevelDomainParts();
        $partsDelimiter     = FullQualifiedDomainNameNormalizer::PARTS_DELIMITER;
        $result             = [];

        for ($partsCount = 1; $partsCount <= 4; $partsCount++) {
            $parts      = [];

            for ($iteration = $partsCount; $iteration > 0; $iteration--) {
                $parts[] = $getSubDomainPart();
            }

            $parts[]    = $topDomainParts[0];
            $result[]   = implode($partsDelimiter, $parts);
        }

        foreach ($topDomainParts as $topDomainPart) {
            $result[] = $getSubDomainPart().$partsDelimiter.$topDomainPart;
        }

        return $result;
    }
    /** **********************************************************************
     * Get valid domain name sub-level parts set.
     *
     * @return  string[]                    Values set.
     ************************************************************************/
    private static function getValidSubLevelDomainParts(): array
    {
        $letter = 'd';
        $digit  = 1;
        $string = 'domain';

        $result = [
            $string,
            strtoupper($string),
            ucfirst($string),

            "$digit$string",
            "$string$digit",
            "$string$digit$string",

            $letter,
            strtoupper($letter),
            "$digit",
        ];

        foreach (DomainNameAllowedCharacters::get() as $char) {
            $result[] = "$string$char$string";
            $result[] = "$digit$char$digit";
            $result[] = "$string$char$digit";
            $result[] = "$digit$char$string";
        }

        return $result;
    }
    /** **********************************************************************
     * Get invalid domain name sub level parts set.
     *
     * @return  string[]                    Values set.
     ************************************************************************/
    private static function getInvalidSubLevelDomainParts(): array
    {
        $letter         = 'd';
        $string         = 'domain';

        $allowedChars   = DomainNameAllowedCharacters::get();
        $otherChars     = array_diff(
            SpecialCharacters::get(),
            UriGeneralDelimiters::get(),
            $allowedChars,
            [FullQualifiedDomainNameNormalizer::PARTS_DELIMITER]
        );

        $result         = [
            "$string ",
            " $string",
            "$string $string",
        ];

        foreach ($allowedChars as $char) {
            $result[]   = "$char$string";
            $result[]   = "$string$char";
        }
        foreach ($otherChars as $char) {
            $result[]   = "$char$string";
            $result[]   = "$string$char";
            $result[]   = "$string$char$string";
        }

        $result[] = str_repeat($letter, SubLevelDomainNormalizer::MAX_LENGTH + 1);

        return $result;
    }
    /** **********************************************************************
     * Get valid domain name top level parts set.
     *
     * @return  string[]                    Values set.
     ************************************************************************/
    private static function getValidTopLevelDomainParts(): array
    {
        $letter     = 'd';
        $minLength  = TopLevelDomainNormalizer::MIN_LENGTH;
        $maxLength  = TopLevelDomainNormalizer::MAX_LENGTH;
        $result     = [];

        for ($length = $minLength; $length <= $maxLength; $length++) {
            $value      = str_repeat($letter, $length);
            $result[]   = $value;
            $result[]   = strtoupper($value);
            $result[]   = ucfirst($value);
        }

        return $result;
    }
    /** **********************************************************************
     * Get invalid domain name top level parts set.
     *
     * @return  string[]                    Values set.
     ************************************************************************/
    private static function getInvalidTopLevelDomainParts(): array
    {
        $letter = 'd';
        $digit  = 1;
        $string = 'domain';

        $chars  = array_diff(
            SpecialCharacters::get(),
            UriGeneralDelimiters::get(),
            [FullQualifiedDomainNameNormalizer::PARTS_DELIMITER]
        );

        $result = [
            "$string ",
            " $string",
            "$string $string",
        ];

        for ($length = 1; $length < TopLevelDomainNormalizer::MIN_LENGTH; $length++) {
            $result[] = str_repeat($letter, $length);
        }

        $result[]   = str_repeat($letter, TopLevelDomainNormalizer::MAX_LENGTH + 1);
        $result[]   = str_repeat("$digit", TopLevelDomainNormalizer::MIN_LENGTH);

        foreach ($chars as $char) {
            $result[] = $char;
        }

        return $result;
    }
}
