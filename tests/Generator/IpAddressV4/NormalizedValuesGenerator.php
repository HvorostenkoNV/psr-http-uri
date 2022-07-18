<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\IpAddressV4;

use HNV\Http\Uri\Collection\IpAddressV4Rules;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function array_fill;
use function array_merge;
use function array_slice;
use function count;
use function rand;
use function str_repeat;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    use GeneratorTrait;

    public function generate(): iterable
    {
        $delimiter = IpAddressV4Rules::PARTS_DELIMITER->value;

        foreach ($this->getSimpleValues() as $value) {
            yield new NormalizedValue($value, $value);
            yield new NormalizedValue("{$delimiter}{$value}", $value);
            yield new NormalizedValue("{$value}{$delimiter}", $value);
            yield new NormalizedValue("{$delimiter}{$value}{$delimiter}", $value);
        }

        foreach ($this->getNormalizedValues() as $value => $normalizedValue) {
            yield new NormalizedValue($value, $normalizedValue);
            yield new NormalizedValue("{$delimiter}{$value}", $normalizedValue);
            yield new NormalizedValue("{$value}{$delimiter}", $normalizedValue);
            yield new NormalizedValue("{$delimiter}{$value}{$delimiter}", $normalizedValue);
        }
    }

    protected function getSimpleValues(): iterable
    {
        foreach ([
            IpAddressV4Rules::PART_MIN_VALUE,
            IpAddressV4Rules::PART_MIN_VALUE,
            $this->getValidRandomValuePart(),
        ] as $partToFill) {
            $valuesSet = array_fill(0, IpAddressV4Rules::PARTS_COUNT, $partToFill);

            yield from $this->buildValueInDifferentCombinations($valuesSet);
        }
    }

    protected function getNormalizedValues(): iterable
    {
        $valueParts = $valuePartsNormalized = [];
        while (count($valueParts) < IpAddressV4Rules::PARTS_COUNT) {
            $value                  = $this->getValidRandomValuePart();
            $minValue               = (string) IpAddressV4Rules::PART_MIN_VALUE;
            $valueParts[]           = str_repeat($minValue, rand(1, 5)).$value;
            $valuePartsNormalized[] = $value;
        }

        $valueFull           = $this->buildValueFromParts($valueParts);
        $valueFullNormalized = $this->buildValueFromParts($valuePartsNormalized);
        yield $valueFull => $valueFullNormalized;

        for ($partsCount = 2; $partsCount < IpAddressV4Rules::PARTS_COUNT; $partsCount++) {
            $valueParts            = array_fill(
                0,
                $partsCount,
                $this->getValidRandomValuePart()
            );
            $valuePartsWithoutLast = array_slice($valueParts, 0, -1);
            $middleParts           = array_fill(
                0,
                IpAddressV4Rules::PARTS_COUNT - count($valueParts),
                IpAddressV4Rules::PART_MIN_VALUE
            );
            $lastPart              = array_slice($valueParts, -1, 1);
            $valueFull             = $this->buildValueFromParts($valueParts);
            $valueFullNormalized   = $this->buildValueFromParts(array_merge(
                $valuePartsWithoutLast,
                $middleParts,
                $lastPart
            ));

            yield $valueFull => $valueFullNormalized;
        }
    }
}
