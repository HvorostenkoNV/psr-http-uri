<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Query;

use HNV\Http\Uri\Collection\QueryRules;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function array_fill;
use function count;
use function implode;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    use GeneratorTrait;

    public function generate(): iterable
    {
        foreach ($this->getSimpleValues() as $value) {
            yield new NormalizedValue($value, $value);
        }
        foreach ($this->getValidNormalizedValues() as $value => $valueNormalized) {
            yield new NormalizedValue($value, $valueNormalized);
        }
    }

    private function getSimpleValues(): iterable
    {
        for ($pairsCount = 1; $pairsCount <= 5; $pairsCount++) {
            $part  = $this->excludeNewValidSimplePart();
            $parts = array_fill(0, $pairsCount, $part);

            yield implode(QueryRules::FIELDS_SEPARATOR->value, $parts);
        }

        for ($pairsCount = 1; $pairsCount <= 5; $pairsCount++) {
            $pairs = [];

            while (count($pairs) < $pairsCount) {
                $key     = $this->excludeNewValidSimplePart();
                $value   = $this->excludeNewValidSimplePart();
                $pairs[] = $key.QueryRules::FIELD_VALUE_SEPARATOR->value.$value;
            }

            yield implode(QueryRules::FIELDS_SEPARATOR->value, $pairs);
        }
    }

    private function getValidNormalizedValues(): iterable
    {
        $fieldsDelimiter    = QueryRules::FIELDS_SEPARATOR->value;
        $pairDelimiter      = QueryRules::FIELD_VALUE_SEPARATOR->value;
        $validSimplePart    = $this->getValidSimpleParts()->current();

        foreach ($this->getValidNormalizedParts() as $value => $valueNormalized) {
            yield $value                                 => $valueNormalized;
            yield $value.$pairDelimiter.$validSimplePart => $valueNormalized.$pairDelimiter.$validSimplePart;
            yield $validSimplePart.$pairDelimiter.$value => $validSimplePart.$pairDelimiter.$valueNormalized;
        }

        yield $fieldsDelimiter.$validSimplePart                  => $validSimplePart;
        yield $validSimplePart.$fieldsDelimiter                  => $validSimplePart;
        yield $fieldsDelimiter.$validSimplePart.$fieldsDelimiter => $validSimplePart;

        yield $validSimplePart.$fieldsDelimiter.$fieldsDelimiter.$validSimplePart =>
            $validSimplePart.$fieldsDelimiter.$validSimplePart;

        yield $fieldsDelimiter                  => '';
        yield $fieldsDelimiter.$fieldsDelimiter => '';
        yield $validSimplePart.$pairDelimiter   => $validSimplePart;
    }
}
