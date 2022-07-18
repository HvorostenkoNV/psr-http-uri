<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Query;

use Generator;
use HNV\Http\Helper\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\QueryRules;

use function array_diff;
use function array_map;
use function array_merge;
use function rawurlencode;
use function strtoupper;
use function ucfirst;

trait GeneratorTrait
{
    private string     $letter      = 'q';
    private int        $digit       = 1;
    private string     $string      = 'query';
    private ?Generator $simpleParts = null;

    protected function getValidSimpleParts(): Generator
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

    protected function getValidNormalizedParts(): Generator
    {
        $allowedChars = array_map(
            fn (SpecialCharacters $character): string => $character->value,
            QueryRules::ALLOWED_CHARACTERS
        );
        $otherChars   = array_diff(
            SpecialCharacters::casesValues(),
            $allowedChars,
            [
                QueryRules::FIELDS_SEPARATOR->value,
                QueryRules::FIELD_VALUE_SEPARATOR->value,
            ]
        );

        foreach ($allowedChars as $char) {
            $charEncoded = rawurlencode($char);

            yield $char         => $char;
            yield $charEncoded  => $char;
        }
        foreach (array_merge($otherChars, [' ']) as $char) {
            $charEncoded = rawurlencode($char);

            yield $char         => $charEncoded;
            yield $charEncoded  => $charEncoded;
        }
    }

    protected function getInvalidParts(): array
    {
        return [];
    }

    protected function excludeNewValidSimplePart(): string
    {
        if (!$this->simpleParts || !$this->simpleParts->valid()) {
            $this->simpleParts = $this->getValidSimpleParts();
        }

        $value = $this->simpleParts->current();
        $this->simpleParts->next();

        return $value;
    }
}
