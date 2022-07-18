<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\IpAddressV4;

use Generator;
use HNV\Http\Uri\Collection\IpAddressV4Rules;

use function count;
use function implode;
use function rand;
use function shuffle;

trait GeneratorTrait
{
    protected function getValidRandomValuePart(): int
    {
        return rand(
            IpAddressV4Rules::PART_MIN_VALUE + 1,
            IpAddressV4Rules::PART_MAX_VALUE - 1
        );
    }

    protected function getInvalidRandomSmallValuePart(): int
    {
        return rand(
            IpAddressV4Rules::PART_MIN_VALUE - 100,
            IpAddressV4Rules::PART_MIN_VALUE - 2
        );
    }

    protected function getInvalidRandomBigValuePart(): int
    {
        return rand(
            IpAddressV4Rules::PART_MAX_VALUE + 2,
            IpAddressV4Rules::PART_MAX_VALUE + 100
        );
    }

    protected function buildValueFromParts(array $parts): string
    {
        return implode(IpAddressV4Rules::PARTS_DELIMITER->value, $parts);
    }

    protected function buildValueInDifferentCombinations(array $parts): Generator
    {
        for ($iteration = count($parts); $iteration > 0; $iteration--) {
            shuffle($parts);
            yield $this->buildValueFromParts($parts);
        }
    }
}
