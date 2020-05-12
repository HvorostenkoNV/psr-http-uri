<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\IpAddress;

use HNV\Http\Uri\Normalizer\{
    NormalizingException,
    NormalizerInterface
};

use function is_numeric;
use function implode;
use function explode;
use function trim;
use function count;
use function array_pop;
/** ***********************************************************************************************
 * IP address V4 normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class V4 implements NormalizerInterface
{
    public const PART_MIN_VALUE     = 0;
    public const PART_MAX_VALUE     = 255;
    public const PARTS_COUNT        = 4;
    public const PARTS_DELIMITER    = '.';
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
    {
        $valueString        = (string) $value;
        $valueTrim          = trim($valueString, self::PARTS_DELIMITER);
        $valueParts         = explode(self::PARTS_DELIMITER, $valueTrim);
        $valuePartsCount    = count($valueParts);
        $needPartsCount     = self::PARTS_COUNT;

        if ($valuePartsCount > $needPartsCount) {
            throw new NormalizingException(
                "ip address V4 \"$valueString\" contains more than $needPartsCount parts"
            );
        }
        if ($valuePartsCount === 1) {
            throw new NormalizingException("value \"$valueString\" is not ip address V4");
        }
        if ($valuePartsCount < $needPartsCount) {
            $lastPart = array_pop($valueParts);

            while (count($valueParts) < $needPartsCount - 1) {
                $valueParts[] = '0';
            }

            $valueParts[] = $lastPart;
        }

        foreach ($valueParts as $index => $part) {
            try {
                $valueParts[$index] = self::normalizeSegment($part);
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "ip address V4 segment \"$part\" is invalid",
                    0,
                    $exception
                );
            }
        }

        return implode(self::PARTS_DELIMITER, $valueParts);
    }
    /** **********************************************************************
     * Normalize IP address segment.
     *
     * @param   string $value               IP address segment.
     *
     * @return  int                         Normalized IP address segment.
     * @throws  NormalizingException        Normalizing error.
     ************************************************************************/
    private static function normalizeSegment(string $value): int
    {
        if (!is_numeric($value)) {
            throw new NormalizingException("value \"$value\" is not numeric");
        }

        $valueNumeric   = (int) $value;
        $minValue       = self::PART_MIN_VALUE;
        $maxValue       = self::PART_MAX_VALUE;

        if ($valueNumeric < $minValue) {
            throw new NormalizingException("value \"$value\" less than $minValue");
        }
        if ($valueNumeric > $maxValue) {
            throw new NormalizingException("value \"$value\" grater than $maxValue");
        }

        return $valueNumeric;
    }
}