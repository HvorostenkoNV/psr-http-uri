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
    Fragment\InvalidValuesGenerator     as FragmentInvalidValuesGenerator,
    Fragment\NormalizedValuesGenerator  as FragmentNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    FullString\ParsedData as FullStringParsedData,
    InvalidValue,
    NormalizedValue,
};

use function strlen;

class FragmentCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $fragmentValidCombinations    = [];

    /** @var InvalidValue[] */
    private array $fragmentInvalidCombinations  = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new FragmentNormalizedValuesGenerator())->generate() as $value) {
            $fragmentString = (string) $value->value;

            foreach (UriDelimiters::generalDelimiters() as $case) {
                if (str_contains($fragmentString, $case->value)) {
                    continue 2;
                }
            }

            $this->fragmentValidCombinations[] = $value;
        }

        foreach ((new FragmentInvalidValuesGenerator())->generate() as $value) {
            $this->fragmentInvalidCombinations[] = $value;
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
        yield from $this->getValuesWithoutQuery();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->fragmentValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathDelimiter.$this->pathNormalized,
                $this->queryNormalized,
                $value->valueNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
        }

        foreach ($this->fragmentInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
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

        foreach ($this->fragmentValidCombinations as $value) {
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter
                .$this->path.$queryDelimiter
                .$this->query
                .$fragmentDelimiter.$value->value,
                true,
                '',
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                $this->queryNormalized,
                $value->valueNormalized,
                $pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
            yield new FullStringParsedData(
                $queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
                true,
                '',
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                '',
                $value->valueNormalized,
                $pathDelimiter.$this->pathNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
            yield new FullStringParsedData(
                $fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
                false,
            );
        }

        foreach ($this->fragmentInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter
                .$this->path.$queryDelimiter
                .$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
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

        foreach ($this->fragmentValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                $this->queryNormalized,
                $value->valueNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                '',
                '',
                0,
                '',
                $pathDelimiter.$this->pathNormalized,
                '',
                $value->valueNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$pathDelimiter.$this->pathNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
        }

        foreach ($this->fragmentInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
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

        foreach ($this->fragmentValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                $this->queryNormalized,
                $value->valueNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$fragmentDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                '',
                '',
                $value->valueNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
        }

        foreach ($this->fragmentInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$value->value,
                false,
            );
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$fragmentDelimiter.$value->value,
                false,
            );
        }
    }

    private function getValuesWithoutQuery(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->fragmentValidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
                true,
                $this->schemeNormalized,
                $this->userInfoNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $this->authorityNormalized,
                $pathDelimiter.$this->pathNormalized,
                '',
                $value->valueNormalized,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$this->getFragmentWithDelimiter($value->valueNormalized),
            );
        }

        foreach ($this->fragmentInvalidCombinations as $value) {
            yield new FullStringParsedData(
                $this->scheme.$schemeDelimiter
                .$authorityDelimiter.$this->authority
                .$pathDelimiter.$this->path
                .$fragmentDelimiter.$value->value,
                false,
            );
        }
    }

    private function getFragmentWithDelimiter(string $value): string
    {
        return strlen($value) > 0 ? FragmentRules::URI_DELIMITER->value.$value : '';
    }
}
