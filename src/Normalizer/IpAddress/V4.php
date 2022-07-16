<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\IpAddress;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\IpAddressV4Rules;

use function array_pop;
use function count;
use function explode;
use function implode;
use function is_numeric;
use function trim;

class V4 implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueParts = self::splitIntoParts((string) $value);

        foreach ($valueParts as $index => $part) {
            try {
                $valueParts[$index] = self::normalizeSegment($part);
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "ip address V4 segment [{$part}] is invalid",
                    0,
                    $exception
                );
            }
        }

        return implode(IpAddressV4Rules::PARTS_DELIMITER->value, $valueParts);
    }

    /**
     * @throws NormalizingException
     *
     * @return string[]
     */
    private static function splitIntoParts(string $value): array
    {
        $valueTrim       = trim($value, IpAddressV4Rules::PARTS_DELIMITER->value);
        $valueParts      = explode(IpAddressV4Rules::PARTS_DELIMITER->value, $valueTrim);
        $valuePartsCount = count($valueParts);
        $needPartsCount  = IpAddressV4Rules::PARTS_COUNT;

        if ($valuePartsCount > $needPartsCount) {
            throw new NormalizingException(
                "ip address V4 [{$value}] contains more than {$needPartsCount} parts"
            );
        }

        if ($valuePartsCount === 1) {
            throw new NormalizingException("value [{$value}] is not ip address V4");
        }

        if ($valuePartsCount < $needPartsCount) {
            $lastPart = array_pop($valueParts);

            while (count($valueParts) < $needPartsCount - 1) {
                $valueParts[] = (string) IpAddressV4Rules::PART_MIN_VALUE;
            }

            $valueParts[] = $lastPart;
        }

        return $valueParts;
    }

    /**
     * @throws NormalizingException
     */
    private static function normalizeSegment(string $value): int
    {
        if (!is_numeric($value)) {
            throw new NormalizingException("value [{$value}] is not numeric");
        }

        $valueNumeric = (int) $value;
        $minValue     = IpAddressV4Rules::PART_MIN_VALUE;
        $maxValue     = IpAddressV4Rules::PART_MAX_VALUE;

        if ($valueNumeric < $minValue) {
            throw new NormalizingException("value [{$value}] less than {$minValue}");
        }
        if ($valueNumeric > $maxValue) {
            throw new NormalizingException("value [{$value}] grater than {$maxValue}");
        }

        return $valueNumeric;
    }
}
