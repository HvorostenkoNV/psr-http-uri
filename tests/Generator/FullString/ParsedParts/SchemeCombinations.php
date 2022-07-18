<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\FullString\ParsedParts;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    PathRules,
    QueryRules,
    SchemeRules,
};
use HNV\Http\UriTests\Generator\{
    AbstractCombinationsGenerator,
    Scheme\InvalidValuesGenerator       as SchemeInvalidValuesGenerator,
    Scheme\NormalizedValuesGenerator    as SchemeNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    FullString\ParsedData as FullStringParsedData,
    InvalidValue,
    NormalizedValue,
};

class SchemeCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $schemeValidCombinations      = [];

    /** @var InvalidValue[] */
    private array $schemeInvalidCombinations    = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new SchemeNormalizedValuesGenerator())->generate() as $value) {
            $this->schemeValidCombinations[] = $value;
        }
        foreach ((new SchemeInvalidValuesGenerator())->generate() as $value) {
            $this->schemeInvalidCombinations[] = $value;
        }
    }

    /**
     * @return FullStringParsedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getFullValues();
        yield from $this->getValuesWithoutAuthority();
        yield from $this->getValuesWithoutPath();
        yield from $this->getValuesWithoutQuery();
        yield from $this->getValuesWithoutFragment();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->schemeValidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathPartsDelimiter.$this->pathNormalized,
                $this->queryNormalized,
                $this->fragmentNormalized,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathPartsDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->schemeInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
        }
    }

    private function getValuesWithoutAuthority(): iterable
    {
        $schemeDelimiter   = SchemeRules::URI_DELIMITER->value;
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->schemeValidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                $value->valueNormalized,
                '',
                '',
                0,
                '',
                $this->pathNormalized,
                $this->queryNormalized,
                $this->fragmentNormalized,
                $value->valueNormalized.$schemeDelimiter
                .$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$queryDelimiter.$this->query,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path
                .$fragmentDelimiter.$this->fragment,
                true,
                $value->valueNormalized,
                '',
                '',
                0,
                '',
                $this->pathNormalized,
                '',
                $this->fragmentNormalized,
                $value->valueNormalized.$schemeDelimiter
                .$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path,
                true,
                $value->valueNormalized,
                '',
                '',
                0,
                '',
                $this->pathNormalized,
                '',
                '',
                $value->valueNormalized.$schemeDelimiter
                .$this->pathNormalized,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path
                .$queryDelimiter.$this->query,
                true,
                $value->valueNormalized,
                '',
                '',
                0,
                '',
                $this->pathNormalized,
                $this->queryNormalized,
                '',
                $value->valueNormalized.$schemeDelimiter
                .$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->schemeInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$queryDelimiter.$this->query,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$this->path
                .$queryDelimiter.$this->query,
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

        foreach ($this->schemeValidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                $this->queryNormalized,
                $this->fragmentNormalized,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$fragmentDelimiter.$this->fragment,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                '',
                $this->fragmentNormalized,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                $this->queryNormalized,
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                '',
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized,
            );
        }

        foreach ($this->schemeInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority,
                false,
            );
        }
    }

    private function getValuesWithoutQuery(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->schemeValidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path
                .$fragmentDelimiter.$this->fragment,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathPartsDelimiter.$this->pathNormalized,
                '',
                $this->fragmentNormalized,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathPartsDelimiter.$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathPartsDelimiter.$this->pathNormalized,
                '',
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathPartsDelimiter.$this->pathNormalized,
            );
        }

        foreach ($this->schemeInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path,
                false,
            );
        }
    }

    private function getValuesWithoutFragment(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->schemeValidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query,
                true,
                $value->valueNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathPartsDelimiter.$this->pathNormalized,
                $this->queryNormalized,
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathPartsDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->schemeInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $value->value.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query,
                false,
            );
        }
    }
}
