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
    Query\InvalidValuesGenerator    as QueryInvalidValuesGenerator,
    Query\NormalizedValuesGenerator as QueryNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    FullString\ParsedData as FullStringParsedData,
    InvalidValue,
    NormalizedValue,
};

use function strlen;

class QueryCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $queryValidCombinations   = [];

    /** @var InvalidValue[] */
    private array $queryInvalidCombinations = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new QueryNormalizedValuesGenerator())->generate() as $value) {
            foreach (UriDelimiters::generalDelimiters() as $case) {
                if (str_contains($value->value, $case->value)) {
                    continue 2;
                }
            }

            $this->queryValidCombinations[] = $value;
        }

        foreach ((new QueryInvalidValuesGenerator())->generate() as $value) {
            $this->queryInvalidCombinations[] = $value;
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
        yield from $this->getValuesWithoutPath();
        yield from $this->getValuesWithoutFragment();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->queryValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathDelimiter.$this->pathNormalized,
                $value->valueNormalized,
                $this->fragmentNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized)
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->queryInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
        }
    }

    private function getValuesWithoutScheme(): iterable
    {
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->queryValidCombinations as $value) {
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                true,
                '',
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                $value->valueNormalized,
                $this->fragmentNormalized,
                $pathDelimiter.$this->pathNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized)
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter.$this->path.
                $queryDelimiter.$value->value,
                true,
                '',
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                $value->valueNormalized,
                '',
                $pathDelimiter.$this->pathNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized),
            );
            yield new FullStringParsedData(
                $queryDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value,
                false,
            );
        }

        foreach ($this->queryInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter.$this->path.
                $queryDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $queryDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value,
                false,
            );
        }
    }

    private function getValuesWithoutAuthority(): iterable
    {
        $schemeDelimiter   = SchemeRules::URI_DELIMITER->value;
        $pathDelimiter     = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->queryValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                $value->valueNormalized,
                $this->fragmentNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$pathDelimiter.$this->pathNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized)
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$queryDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                $value->valueNormalized,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$pathDelimiter.$this->pathNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized),
            );
        }

        foreach ($this->queryInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$queryDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value,
                false,
            );
        }
    }

    private function getValuesWithoutPath(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->queryValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                $value->valueNormalized,
                $this->fragmentNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized)
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                $value->valueNormalized,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized),
            );
        }

        foreach ($this->queryInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$value->value,
                false,
            );
        }
    }

    private function getValuesWithoutFragment(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->queryValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathDelimiter.$this->pathNormalized,
                $value->valueNormalized,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$this->getQueryWithDelimiter($value->valueNormalized),
            );
        }

        foreach ($this->queryInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$value->value,
                false,
            );
        }
    }

    private function getQueryWithDelimiter(string $value): string
    {
        return strlen($value) > 0 ? QueryRules::URI_DELIMITER->value.$value : '';
    }
}
