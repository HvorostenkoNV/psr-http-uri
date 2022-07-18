<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\DomainName;

use HNV\Http\Uri\Collection\DomainNameRules;
use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\InvalidValue,
};

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    use GeneratorTrait;

    public function generate(): iterable
    {
        $subDomainPartValid = $this->getValidSubLevelDomainParts()->current();
        $topDomainPartValid = $this->getValidTopLevelDomainParts()->current();
        $partsDelimiter     = DomainNameRules::LEVELS_DELIMITER->value;

        foreach ($this->getInvalidSubLevelDomainParts() as $subPart) {
            yield new InvalidValue($subPart.$partsDelimiter.$topDomainPartValid);
        }
        foreach ($this->getInvalidTopLevelDomainParts() as $topPart) {
            yield new InvalidValue($subDomainPartValid.$partsDelimiter.$topPart);
        }

        yield new InvalidValue($subDomainPartValid);
        yield new InvalidValue($topDomainPartValid);
        yield new InvalidValue($subDomainPartValid.$topDomainPartValid);

        yield new InvalidValue(
            $partsDelimiter.$subDomainPartValid
            .$partsDelimiter.$topDomainPartValid
        );
        yield new InvalidValue(
            $subDomainPartValid.$partsDelimiter
            .$partsDelimiter.$topDomainPartValid
        );
        yield new InvalidValue(
            $subDomainPartValid.$partsDelimiter
            .$topDomainPartValid.$partsDelimiter
        );
    }
}
