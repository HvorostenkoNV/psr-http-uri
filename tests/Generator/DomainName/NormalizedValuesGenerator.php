<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\DomainName;

use Generator;
use HNV\Http\Uri\Collection\DomainNameRules;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function array_fill;
use function implode;
use function strtolower;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    use GeneratorTrait;

    private ?Generator $subDomainParts = null;

    public function generate(): iterable
    {
        foreach ($this->getValues() as $value) {
            yield new NormalizedValue($value, strtolower($value));
        }
    }

    private function getValues(): iterable
    {
        $topDomainParts = $this->getValidTopLevelDomainParts();
        $partsDelimiter = DomainNameRules::LEVELS_DELIMITER->value;

        for ($partsCount = 1; $partsCount <= 4; $partsCount++) {
            $part    = $this->excludeNewSubDomainPart();
            $parts   = array_fill(0, $partsCount, $part);
            $parts[] = $topDomainParts->current();

            yield implode($partsDelimiter, $parts);
        }

        foreach ($topDomainParts as $topDomainPart) {
            yield $this->excludeNewSubDomainPart().$partsDelimiter.$topDomainPart;
        }
    }

    private function excludeNewSubDomainPart(): string
    {
        if (!$this->subDomainParts || !$this->subDomainParts->valid()) {
            $this->subDomainParts = $this->getValidSubLevelDomainParts();
        }

        $value = $this->subDomainParts->current();
        $this->subDomainParts->next();

        return $value;
    }
}
