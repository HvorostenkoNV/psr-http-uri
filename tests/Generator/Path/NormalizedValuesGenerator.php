<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Path;

use Generator;
use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\PathRules;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function array_diff;
use function array_fill;
use function array_map;
use function array_merge;
use function implode;
use function rawurlencode;
use function str_repeat;
use function strtoupper;
use function ucfirst;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    private string     $letter      = 'p';
    private int        $digit       = 1;
    private string     $string      = 'path';
    private ?Generator $simpleParts = null;

    public function generate(): iterable
    {
        foreach ($this->getSimpleValues() as $value) {
            yield new NormalizedValue($value, $value);
        }
        foreach ($this->getNormalizedValues() as $value => $valueNormalized) {
            yield new NormalizedValue($value, $valueNormalized);
        }
    }

    private function getSimpleValues(): iterable
    {
        for ($partsCount = 1; $partsCount <= 5; $partsCount++) {
            $part  = $this->excludeNewSimplePart();
            $parts = array_fill(0, $partsCount, $part);

            yield implode(PathRules::PARTS_SEPARATOR->value, $parts);
        }
    }

    private function getNormalizedValues(): iterable
    {
        $separator  = PathRules::PARTS_SEPARATOR->value;
        $simplePart = $this->excludeNewSimplePart();

        foreach ($this->getNormalizedParts() as $value => $valueNormalized) {
            yield $value => $valueNormalized;
        }

        foreach ([
            $separator,
            $separator.$simplePart,
            $simplePart.$separator,
            $separator.$simplePart.$separator,
        ] as $value) {
            yield $value => $value;
        }

        for ($repeatCount = 1; $repeatCount <= 3; $repeatCount++) {
            $separatorRepeated = str_repeat($separator, $repeatCount);

            foreach ([
                $separatorRepeated,
                $separatorRepeated.$simplePart,
                $simplePart.$separatorRepeated,
                $separatorRepeated.$simplePart.$separatorRepeated,
            ] as $value) {
                yield $value => $value;
            }
        }
    }

    private function getSimpleParts(): Generator
    {
        yield $this->string;
        yield strtoupper($this->string);
        yield ucfirst($this->string);

        yield "{$this->string}{$this->digit}";
        yield "{$this->string}{$this->digit}{$this->string}";
        yield "{$this->digit}{$this->string}";

        yield $this->letter;
        yield "{$this->digit}";
    }

    private function getNormalizedParts(): Generator
    {
        $string             = 'path';
        $invalidFirstChars  = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            PathRules::ALLOWED_CHARACTERS_NON_FIRST
        );
        $allowedChars       = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            PathRules::ALLOWED_CHARACTERS_OTHERS
        );
        $otherChars         = array_diff(
            SpecialCharacters::casesValues(),
            $allowedChars,
            $invalidFirstChars,
            [PathRules::PARTS_SEPARATOR->value]
        );

        foreach ($allowedChars as $char) {
            $charEncoded = rawurlencode($char);

            yield $char         => $char;
            yield $charEncoded  => $char;
        }
        foreach ($invalidFirstChars as $char) {
            $charEncoded = rawurlencode($char);

            yield $string.$char         => $string.$char;
            yield $string.$charEncoded  => $string.$char;
        }
        foreach (array_merge($otherChars, [' ']) as $char) {
            $charEncoded = rawurlencode($char);

            yield $char         => $charEncoded;
            yield $charEncoded  => $charEncoded;
        }
    }

    private function excludeNewSimplePart(): string
    {
        if (!$this->simpleParts || !$this->simpleParts->valid()) {
            $this->simpleParts = $this->getSimpleParts();
        }

        $value = $this->simpleParts->current();
        $this->simpleParts->next();

        return $value;
    }
}
