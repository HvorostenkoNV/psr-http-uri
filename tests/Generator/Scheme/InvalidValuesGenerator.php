<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Scheme;

use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\{
    SchemeRules,
    UriDelimiters,
};
use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\InvalidValue,
};

use function array_diff;
use function array_map;

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    private string $letter = 's';
    private int    $digit  = 1;
    private string $string = 'scheme';

    public function generate(): iterable
    {
        $allowedChars   = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            SchemeRules::ALLOWED_CHARACTERS
        );
        $uriDelimiters  = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            UriDelimiters::generalDelimiters()
        );
        $otherChars     = array_diff(
            SpecialCharacters::casesValues(),
            $uriDelimiters,
            $allowedChars
        );

        yield new InvalidValue("{$this->string} ");
        yield new InvalidValue(" {$this->string}");
        yield new InvalidValue("{$this->string} {$this->string}");

        yield new InvalidValue($this->letter);
        yield new InvalidValue("{$this->digit}{$this->letter}");
        yield new InvalidValue("{$this->digit}{$this->string}");

        foreach ($allowedChars as $char) {
            yield new InvalidValue("{$char}{$this->string}");
        }

        foreach ($otherChars as $char) {
            yield new InvalidValue("{$char}{$this->string}");
            yield new InvalidValue("{$this->string}{$char}");
            yield new InvalidValue("{$this->string}{$char}{$this->string}");
        }
    }
}
