<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\IpAddressV4;

use HNV\Http\Uri\Collection\IpAddressV4Rules;
use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\InvalidValue,
};

use function array_fill;
use function array_merge;

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    use GeneratorTrait;

    public function generate(): iterable
    {
        $delimiter = IpAddressV4Rules::PARTS_DELIMITER->value;

        foreach ($this->getValues() as $value) {
            yield new InvalidValue($value);
            yield new InvalidValue($delimiter.$value);
            yield new InvalidValue($value.$delimiter);
            yield new InvalidValue($delimiter.$value.$delimiter);
        }
    }

    protected function getValues(): iterable
    {
        $validPartsWithoutOne = array_fill(
            0,
            IpAddressV4Rules::PARTS_COUNT - 1,
            $this->getValidRandomValuePart()
        );

        foreach ([
            IpAddressV4Rules::PART_MIN_VALUE - 1,
            IpAddressV4Rules::PART_MAX_VALUE + 1,
            $this->getInvalidRandomSmallValuePart(),
            $this->getInvalidRandomBigValuePart(),
        ] as $invalidPart) {
            $valuesSet = array_merge($validPartsWithoutOne, [$invalidPart]);

            yield from $this->buildValueInDifferentCombinations($valuesSet);
        }

        $tooManyValidParts = array_fill(
            0,
            IpAddressV4Rules::PARTS_COUNT + 1,
            $this->getValidRandomValuePart()
        );

        yield from $this->buildValueInDifferentCombinations($tooManyValidParts);
    }
}
