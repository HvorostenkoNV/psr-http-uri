<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\DomainName;

use Generator;
use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\{
    DomainNameRules,
    UriDelimiters,
};

use function array_diff;
use function array_map;
use function str_repeat;
use function strtoupper;
use function ucfirst;

trait GeneratorTrait
{
    private string $letter  = 'd';
    private int    $digit   = 1;
    private string $string  = 'domain';

    protected function getValidSubLevelDomainParts(): Generator
    {
        yield $this->string;
        yield strtoupper($this->string);
        yield ucfirst($this->string);

        yield ucfirst("{$this->digit}{$this->string}");
        yield ucfirst("{$this->string}{$this->digit}");
        yield ucfirst("{$this->string}{$this->digit}{$this->string}");

        yield $this->letter;
        yield strtoupper($this->letter);
        yield "{$this->digit}";

        foreach (DomainNameRules::SUB_LEVEL_ALLOWED_SPECIAL_CHARACTERS as $case) {
            yield "{$this->string}{$case->value}{$this->string}";
            yield "{$this->digit}{$case->value}{$this->digit}";
            yield "{$this->string}{$case->value}{$this->digit}";
            yield "{$this->digit}{$case->value}{$this->string}";
        }
    }

    protected function getInvalidSubLevelDomainParts(): Generator
    {
        $allowedChars   = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            DomainNameRules::SUB_LEVEL_ALLOWED_SPECIAL_CHARACTERS
        );
        $uriDelimiters  = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            UriDelimiters::generalDelimiters()
        );
        $otherChars     = array_diff(
            SpecialCharacters::casesValues(),
            $uriDelimiters,
            $allowedChars,
            [DomainNameRules::LEVELS_DELIMITER->value]
        );

        yield "{$this->string} ";
        yield " {$this->string}";
        yield "{$this->string} {$this->string}";

        foreach ($allowedChars as $char) {
            yield "{$char}{$this->string}";
            yield "{$this->string}{$char}";
        }
        foreach ($otherChars as $char) {
            yield "{$char}{$this->string}";
            yield "{$this->string}{$char}";
            yield "{$this->string}{$char}{$this->string}";
        }

        yield str_repeat($this->letter, DomainNameRules::SUB_LEVEL_MAX_LENGTH + 1);
    }

    protected function getValidTopLevelDomainParts(): Generator
    {
        for (
            $length = DomainNameRules::TOP_LEVEL_MIN_LENGTH;
            $length <= DomainNameRules::TOP_LEVEL_MAX_LENGTH;
            $length++
        ) {
            $value = str_repeat($this->letter, $length);

            yield $value;
            yield strtoupper($value);
            yield ucfirst($value);
        }
    }

    protected function getInvalidTopLevelDomainParts(): Generator
    {
        $uriDelimiters  = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            UriDelimiters::generalDelimiters()
        );
        $otherChars     = array_diff(
            SpecialCharacters::casesValues(),
            $uriDelimiters,
            [DomainNameRules::LEVELS_DELIMITER->value]
        );

        yield "{$this->string} ";
        yield " {$this->string}";
        yield "{$this->string} {$this->string}";

        for ($length = 1; $length < DomainNameRules::TOP_LEVEL_MIN_LENGTH; $length++) {
            yield str_repeat($this->letter, $length);
        }

        yield str_repeat($this->letter, DomainNameRules::TOP_LEVEL_MAX_LENGTH + 1);
        yield str_repeat("{$this->digit}", DomainNameRules::TOP_LEVEL_MIN_LENGTH);

        foreach ($otherChars as $char) {
            yield $char;
        }
    }
}
