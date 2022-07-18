<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\UserInfo;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\UriTests\{
    Generator\NormalizedValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

use function rawurlencode;
use function strtoupper;
use function ucfirst;

class ValueNormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    private string $letter = 'u';
    private int    $digit  = 1;
    private string $string = 'user';

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
        yield $this->string;
        yield strtoupper($this->string);
        yield ucfirst($this->string);

        yield "{$this->digit}";
        yield $this->letter;

        yield "{$this->digit}{$this->string}";
        yield "{$this->string}{$this->digit}";
        yield "{$this->string}{$this->digit}{$this->string}";
    }

    private function getNormalizedValues(): iterable
    {
        foreach (SpecialCharacters::casesValues() as $char) {
            $charEncoded = rawurlencode($char);

            yield $char        => $charEncoded;
            yield $charEncoded => $charEncoded;
        }
    }
}
