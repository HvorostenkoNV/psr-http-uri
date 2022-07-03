<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\IpAddress;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};

use function array_pop;
use function count;
use function explode;
use function implode;
use function is_numeric;
use function trim;

/**
 * IP address V4 normalizer.
 */
class V4 implements NormalizerInterface
{
    public const PART_MIN_VALUE  = 0;
    public const PART_MAX_VALUE  = 255;
    public const PARTS_COUNT     = 4;
    public const PARTS_DELIMITER = '.';

    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueParts = self::splitValueIntoParts((string) $value);

        foreach ($valueParts as $index => $part) {
            try {
                $valueParts[$index] = self::normalizeSegment($part);
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "ip address V4 segment \"{$part}\" is invalid",
                    0,
                    $exception
                );
            }
        }

        return implode(self::PARTS_DELIMITER, $valueParts);
    }

    /**
     * Split ip v6 value into parts.
     *
     * @param string $value IP address v4
     *
     * @throws NormalizingException normalizing error
     *
     * @return string[] value parts
     */
    private static function splitValueIntoParts(string $value): array
    {
        $valueTrim       = trim($value, self::PARTS_DELIMITER);
        $valueParts      = explode(self::PARTS_DELIMITER, $valueTrim);
        $valuePartsCount = count($valueParts);
        $needPartsCount  = self::PARTS_COUNT;

        if ($valuePartsCount > $needPartsCount) {
            throw new NormalizingException(
                "ip address V4 \"{$value}\" contains more than {$needPartsCount} parts"
            );
        }

        if ($valuePartsCount === 1) {
            throw new NormalizingException("value \"{$value}\" is not ip address V4");
        }

        if ($valuePartsCount < $needPartsCount) {
            $lastPart = array_pop($valueParts);

            while (count($valueParts) < $needPartsCount - 1) {
                $valueParts[] = (string) self::PART_MIN_VALUE;
            }

            $valueParts[] = $lastPart;
        }

        return $valueParts;
    }

    /**
     * Normalize IP address segment.
     *
     * @param string $value IP address segment
     *
     * @throws NormalizingException normalizing error
     *
     * @return int normalized IP address segment
     */
    private static function normalizeSegment(string $value): int
    {
        if (!is_numeric($value)) {
            throw new NormalizingException("value \"{$value}\" is not numeric");
        }

        $valueNumeric = (int) $value;
        $minValue     = self::PART_MIN_VALUE;
        $maxValue     = self::PART_MAX_VALUE;

        if ($valueNumeric < $minValue) {
            throw new NormalizingException("value \"{$value}\" less than {$minValue}");
        }
        if ($valueNumeric > $maxValue) {
            throw new NormalizingException("value \"{$value}\" grater than {$maxValue}");
        }

        return $valueNumeric;
    }
}
