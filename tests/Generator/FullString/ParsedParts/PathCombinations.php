<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\FullString\ParsedParts;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    PathRules,
    QueryRules,
    SchemeRules,
    UriDelimiters,
};
use HNV\Http\UriTests\Generator\{
    AbstractCombinationsGenerator,
    Path\InvalidValuesGenerator     as PathInvalidValuesGenerator,
    Path\NormalizedValuesGenerator  as PathNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    FullString\ParsedData as FullStringParsedData,
    InvalidValue,
    NormalizedValue,
};

use function ltrim;

class PathCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $pathValidCombinations                    = [];

    /** @var NormalizedValue[] */
    private array $pathValidCombinationsWithoutAuthority    = [];

    /** @var InvalidValue[] */
    private array $pathInvalidCombinations                  = [];

    public function __construct()
    {
        parent::__construct();

        $pathSeparator = PathRules::PARTS_SEPARATOR->value;

        foreach ((new PathNormalizedValuesGenerator())->generate() as $value) {
            foreach (UriDelimiters::generalDelimiters() as $case) {
                if (str_contains($value->value, $case->value)) {
                    continue 2;
                }
            }

            $pathWithSeparatorPrefix            = str_starts_with($value->value, $pathSeparator)
                ? $value->value
                : $pathSeparator.$value->value;
            $pathNormalizedWithSeparatorPrefix  = str_starts_with($value->valueNormalized, $pathSeparator)
                ? $value->valueNormalized
                : $pathSeparator.$value->valueNormalized;
            $pathNormalizedWithMinimizedPrefix  = str_starts_with($value->valueNormalized, $pathSeparator)
                ? $pathSeparator.ltrim($value->valueNormalized, $pathSeparator)
                : $value->valueNormalized;

            $this->pathValidCombinations[]                  = new NormalizedValue(
                $pathWithSeparatorPrefix,
                $pathNormalizedWithSeparatorPrefix
            );
            $this->pathValidCombinationsWithoutAuthority[]  = new NormalizedValue(
                $value->value,
                $pathNormalizedWithMinimizedPrefix
            );
        }

        foreach ((new PathInvalidValuesGenerator())->generate() as $value) {
            $this->pathInvalidCombinations[] = $value;
        }
    }

    /**
     * @return FullStringParsedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getFullValues();
        yield from $this->getValuesWithoutScheme();
        yield from $this->getValuesWithoutAuthority();
        yield from $this->getValuesWithoutQuery();
        yield from $this->getValuesWithoutFragment();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->pathValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $value->valueNormalized,
                $this->queryNormalized,
                $this->fragmentNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized.$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->pathInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
        }
    }

    private function getValuesWithoutScheme(): iterable
    {
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->pathValidCombinations as $value) {
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value,
                false,
            );
        }

        foreach ($this->pathInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority.$value->value,
                false,
            );
        }
    }

    private function getValuesWithoutAuthority(): iterable
    {
        $schemeDelimiter   = SchemeRules::URI_DELIMITER->value;
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->pathValidCombinationsWithoutAuthority as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                $this->queryNormalized,
                $this->fragmentNormalized,
                $this->schemeNormalized.$schemeDelimiter.$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                '',
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                $this->queryNormalized,
                $this->fragmentNormalized,
                $value->valueNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value
                .$fragmentDelimiter.$this->fragment,
                true,
                '',
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                '',
                $this->fragmentNormalized,
                $value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value
                .$queryDelimiter.$this->query,
                true,
                '',
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                $this->queryNormalized,
                '',
                $value->valueNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringParsedData(
                $value->value,
                true,
                '',
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                '',
                '',
                $value->valueNormalized,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                '',
                $this->fragmentNormalized,
                $this->schemeNormalized.$schemeDelimiter.$value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value
                .$queryDelimiter.$this->query,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                $this->queryNormalized,
                '',
                $this->schemeNormalized.$schemeDelimiter.$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $value->valueNormalized,
                '',
                '',
                $this->schemeNormalized.$schemeDelimiter.$value->valueNormalized,
            );
        }

        foreach ($this->pathInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value
                .$queryDelimiter.$this->query,
                false,
            );
            yield new FullStringParsedData(
                $value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value
                .$queryDelimiter.$this->query,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter.$value->value,
                false,
            );
        }
    }

    private function getValuesWithoutQuery(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->pathValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value
                .$fragmentDelimiter.$this->fragment,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $value->valueNormalized,
                '',
                $this->fragmentNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized.$value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $value->valueNormalized,
                '',
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized.$value->valueNormalized,
            );
        }

        foreach ($this->pathInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
        }
    }

    private function getValuesWithoutFragment(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->pathValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $value->valueNormalized,
                $this->queryNormalized,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized.$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->pathInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority.$value->value
                .$queryDelimiter.$this->query,
                false,
            );
        }
    }
}
