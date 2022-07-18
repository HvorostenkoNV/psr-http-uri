<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Scheme;

use HNV\Http\Uri\Collection\SchemeRules;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function strtolower;
use function strtoupper;
use function ucfirst;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    private string $letter = 's';
    private int    $digit  = 1;
    private string $string = 'scheme';

    public function generate(): iterable
    {
        foreach ($this->getValues() as $value) {
            yield new NormalizedValue($value, strtolower($value));
        }
    }

    private function getValues(): iterable
    {
        yield $this->string;
        yield strtoupper($this->string);
        yield ucfirst($this->string);

        yield "{$this->string}{$this->digit}";
        yield "{$this->string}{$this->digit}{$this->string}";

        yield "{$this->letter}{$this->letter}";
        yield "{$this->letter}{$this->digit}";

        yield $this->letter.strtoupper($this->letter);
        yield strtoupper($this->letter).strtoupper($this->letter);
        yield strtoupper($this->letter).$this->letter;

        foreach (SchemeRules::ALLOWED_CHARACTERS as $case) {
            yield "{$this->string}{$case->value}";
            yield "{$this->string}{$case->value}{$this->string}";
        }
    }
}
