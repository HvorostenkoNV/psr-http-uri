<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Fragment;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function array_merge;
use function rawurlencode;
use function strtoupper;
use function ucfirst;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    private string $letter = 'f';
    private int    $digit  = 1;
    private string $string = 'fragment';

    public function generate(): iterable
    {
        foreach ($this->getSimpleValues() as $value) {
            yield new NormalizedValue($value, $value);
        }

        $allChars = SpecialCharacters::casesValues();

        foreach (array_merge($allChars, [' ']) as $char) {
            yield new NormalizedValue(rawurlencode($char), $char);
        }
    }

    private function getSimpleValues(): iterable
    {
        yield $this->string;
        yield strtoupper($this->string);
        yield ucfirst($this->string);

        yield "{$this->string}{$this->digit}";
        yield "{$this->string}{$this->digit}{$this->string}";
        yield "{$this->digit}{$this->string}";

        yield "{$this->string} ";
        yield "{$this->string} {$this->string}";
        yield " {$this->string}";
        yield ' ';

        yield $this->letter;
        yield "{$this->digit}";

        foreach (SpecialCharacters::cases() as $char) {
            yield $char->value;
        }
    }
}
