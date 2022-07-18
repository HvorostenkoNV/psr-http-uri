<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\FullString;

use HNV\Http\Helper\Generator\GeneratorInterface;
use HNV\Http\UriTests\Generator\FullString\ParsedParts\{
    AuthorityCombinations,
    FragmentCombinations,
    PathCombinations,
    QueryCombinations,
    SchemeCombinations,
};
use HNV\Http\UriTests\ValueObject\FullString\ParsedData as FullStringParsedData;

class ParsedDataGenerator implements GeneratorInterface
{
    /**
     * @return FullStringParsedData[]
     */
    public function generate(): iterable
    {
        yield from (new AuthorityCombinations())->generate();
        yield from (new FragmentCombinations())->generate();
        yield from (new PathCombinations())->generate();
        yield from (new QueryCombinations())->generate();
        yield from (new SchemeCombinations())->generate();
    }
}
