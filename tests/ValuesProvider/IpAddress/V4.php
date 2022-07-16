<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider\IpAddress;

use HNV\Http\Uri\Collection\IpAddressV4Rules;
use HNV\Http\UriTests\ValuesProvider\ValuesProviderInterface;

use function array_fill;
use function array_merge;
use function array_slice;
use function count;
use function implode;
use function rand;
use function shuffle;
use function str_repeat;

/**
 * IP address V4 normalized values set provider.
 */
class V4 implements ValuesProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getValidValues(): array
    {
        $delimiter = IpAddressV4Rules::PARTS_DELIMITER->value;
        $result    = [];

        foreach (self::getValidSimpleValues() as $value) {
            $result[$value]                             = $value;
            $result["{$delimiter}{$value}"]             = $value;
            $result["{$value}{$delimiter}"]             = $value;
            $result["{$delimiter}{$value}{$delimiter}"] = $value;
        }
        foreach (self::getValidNormalizedValues() as $value => $valueNormalized) {
            $result[$value]                             = $valueNormalized;
            $result["{$delimiter}{$value}"]             = $valueNormalized;
            $result["{$value}{$delimiter}"]             = $valueNormalized;
            $result["{$delimiter}{$value}{$delimiter}"] = $valueNormalized;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public static function getInvalidValues(): array
    {
        $delimiter = IpAddressV4Rules::PARTS_DELIMITER->value;
        $result    = [];

        foreach (self::getInvalidSimpleValues() as $value) {
            $result[] = $value;
            $result[] = $delimiter.$value;
            $result[] = $value.$delimiter;
            $result[] = $delimiter.$value.$delimiter;
        }

        return $result;
    }

    /**
     * Get valid simple (without need for normalizing) values set.
     *
     * @return string[] values set
     */
    private static function getValidSimpleValues(): array
    {
        $minValuesSet = array_fill(
            0,
            IpAddressV4Rules::PARTS_COUNT,
            IpAddressV4Rules::PART_MIN_VALUE
        );
        $maxValuesSet = array_fill(
            0,
            IpAddressV4Rules::PARTS_COUNT,
            IpAddressV4Rules::PART_MAX_VALUE
        );
        $differentValuesSet = array_fill(
            0,
            IpAddressV4Rules::PARTS_COUNT,
            self::getValidRandomValuePart()
        );

        return array_merge(
            self::buildValueInDifferentCombinations($minValuesSet),
            self::buildValueInDifferentCombinations($maxValuesSet),
            self::buildValueInDifferentCombinations($differentValuesSet)
        );
    }

    /**
     * Get valid values with their normalized representation set.
     *
     * @return string[] values set
     */
    private static function getValidNormalizedValues(): array
    {
        $result = [];

        $valueParts           = [];
        $valuePartsNormalized = [];

        while (count($valueParts) < IpAddressV4Rules::PARTS_COUNT) {
            $value                  = self::getValidRandomValuePart();
            $valueParts[]           = str_repeat('0', rand(1, 5)).$value;
            $valuePartsNormalized[] = $value;
        }

        $valueFull           = self::buildValueFromParts($valueParts);
        $valueFullNormalized = self::buildValueFromParts($valuePartsNormalized);
        $result[$valueFull]  = $valueFullNormalized;

        for ($partsCount = 2; $partsCount < IpAddressV4Rules::PARTS_COUNT; $partsCount++) {
            $valueParts = array_fill(
                0,
                $partsCount,
                self::getValidRandomValuePart()
            );
            $valuePartsWithoutLast = array_slice($valueParts, 0, -1);
            $middleParts           = array_fill(
                0,
                IpAddressV4Rules::PARTS_COUNT - count($valueParts),
                IpAddressV4Rules::PART_MIN_VALUE
            );
            $lastPart = array_slice($valueParts, -1, 1);

            $valueFull           = self::buildValueFromParts($valueParts);
            $valueFullNormalized = self::buildValueFromParts(array_merge(
                $valuePartsWithoutLast,
                $middleParts,
                $lastPart
            ));
            $result[$valueFull] = $valueFullNormalized;
        }

        return $result;
    }

    /**
     * Get valid values with their normalized representation set.
     *
     * @return string[] values set
     */
    private static function getInvalidSimpleValues(): array
    {
        $validPartsWithoutOne = array_fill(
            0,
            IpAddressV4Rules::PARTS_COUNT - 1,
            self::getValidRandomValuePart()
        );
        $invalidParts = [
            IpAddressV4Rules::PART_MIN_VALUE - 1,
            IpAddressV4Rules::PART_MAX_VALUE + 1,
            self::getInvalidRandomSmallValuePart(),
            self::getInvalidRandomBigValuePart(),
        ];
        $result = [];

        foreach ($invalidParts as $invalidPart) {
            $valuesSet = array_merge($validPartsWithoutOne, [$invalidPart]);
            $result    = array_merge($result, self::buildValueInDifferentCombinations($valuesSet));
        }

        $tooManyValidParts = array_fill(
            0,
            IpAddressV4Rules::PARTS_COUNT + 1,
            self::getValidRandomValuePart()
        );

        return array_merge($result, self::buildValueInDifferentCombinations($tooManyValidParts));
    }

    /**
     * Get valid random value part.
     */
    private static function getValidRandomValuePart(): int
    {
        return rand(
            IpAddressV4Rules::PART_MIN_VALUE + 1,
            IpAddressV4Rules::PART_MAX_VALUE - 1
        );
    }

    /**
     * Get invalid random small value part.
     */
    private static function getInvalidRandomSmallValuePart(): int
    {
        return rand(
            IpAddressV4Rules::PART_MIN_VALUE - 100,
            IpAddressV4Rules::PART_MIN_VALUE - 2
        );
    }

    /**
     * Get invalid random big value part.
     */
    private static function getInvalidRandomBigValuePart(): int
    {
        return rand(
            IpAddressV4Rules::PART_MAX_VALUE + 2,
            IpAddressV4Rules::PART_MAX_VALUE + 100
        );
    }

    /**
     * Build full value from parts.
     *
     * @param int[] $parts parts
     */
    private static function buildValueFromParts(array $parts): string
    {
        return implode(IpAddressV4Rules::PARTS_DELIMITER->value, $parts);
    }

    /**
     * Build values set from parts in different combinations.
     *
     * @param int[] $parts parts
     *
     * @return string[] values set
     */
    private static function buildValueInDifferentCombinations(array $parts): array
    {
        $result = [];

        for ($iteration = count($parts); $iteration > 0; $iteration--) {
            shuffle($parts);
            $result[] = self::buildValueFromParts($parts);
        }

        return $result;
    }
}
