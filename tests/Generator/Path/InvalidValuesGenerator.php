<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Path;

use HNV\Http\Uri\Collection\PathRules;
use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\InvalidValue,
};

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    public function generate(): iterable
    {
        $validValues = (new NormalizedValuesGenerator())->generate();
        $result      = [];

        foreach (PathRules::ALLOWED_CHARACTERS_NON_FIRST as $case) {
            foreach ($validValues as $value) {
                yield new InvalidValue("{$case->value}{$value->valueNormalized}");
            }
        }

        return $result;
    }
}
