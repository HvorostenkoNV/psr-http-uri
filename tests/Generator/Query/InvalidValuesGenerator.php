<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Query;

use HNV\Http\Uri\Collection\QueryRules;
use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\InvalidValue,
};

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    use GeneratorTrait;

    public function generate(): iterable
    {
        $fieldsDelimiter = QueryRules::FIELDS_SEPARATOR->value;
        $pairDelimiter   = QueryRules::FIELD_VALUE_SEPARATOR->value;
        $validPart       = $this->getValidSimpleParts()->current();

        foreach ($this->getInvalidParts() as $invalidPart) {
            yield new InvalidValue($invalidPart);
            yield new InvalidValue($invalidPart.$pairDelimiter.$validPart);
            yield new InvalidValue($validPart.$pairDelimiter.$invalidPart);
            yield new InvalidValue($validPart.$fieldsDelimiter.$invalidPart);
        }
    }
}
