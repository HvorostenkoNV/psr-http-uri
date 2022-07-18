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
    Authority\ParsedDataGenerator as AuthorityParsedDataGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    Authority\ParsedData            as AuthorityParsedData,
    Authority\ParsedDataWithScheme  as AuthorityParsedDataWithScheme,
    FullString\ParsedData           as FullStringParsedData,
};

use function is_string;
use function strlen;

class AuthorityCombinations extends AbstractCombinationsGenerator
{
    /** @var AuthorityParsedDataWithScheme[] */
    private array $authorityValidCombinations                = [];

    /** @var AuthorityParsedDataWithScheme[] */
    private array $authorityInvalidCombinations              = [];

    /** @var AuthorityParsedData[] */
    private array $authorityWithoutSchemeValidCombinations   = [];

    /** @var AuthorityParsedData[] */
    private array $authorityWithoutSchemeInvalidCombinations = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new AuthorityParsedDataGenerator())->generate() as $combination) {
            $hasScheme              = is_string($combination->scheme)
                && strlen($combination->scheme) > 0;
            $schemePostfix          = SchemeRules::URI_DELIMITER->value
                .AuthorityRules::URI_DELIMITER;
            $combinationWithScheme  = new AuthorityParsedDataWithScheme(
                $hasScheme
                    ? $combination->valueToParse
                    : $this->scheme.$schemePostfix.$combination->valueToParse,
                $combination->isValid,
                $hasScheme ? $combination->scheme : $this->schemeNormalized,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->valueNormalized,
                $hasScheme
                    ? $combination->scheme.$schemePostfix.$combination->valueNormalized
                    : $this->schemeNormalized.$schemePostfix.$combination->valueNormalized,
            );

            $combination->isValid
                ? $this->authorityValidCombinations[]   = $combinationWithScheme
                : $this->authorityInvalidCombinations[] = $combinationWithScheme;

            if (!$hasScheme) {
                $combination->isValid
                    ? $this->authorityWithoutSchemeValidCombinations[]   = $combination
                    : $this->authorityWithoutSchemeInvalidCombinations[] = $combination;
            }
        }
    }

    /**
     * @return FullStringParsedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getFullValues();
        yield from $this->getValuesWithoutScheme();
        yield from $this->getValuesWithoutPath();
        yield from $this->getValuesWithoutQuery();
        yield from $this->getValuesWithoutFragment();
    }

    private function getFullValues(): iterable
    {
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                $pathPartsDelimiter.$this->pathNormalized,
                $this->queryNormalized,
                $this->fragmentNormalized,
                $combination->valueNormalized
                .$pathPartsDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
        }
    }

    private function getValuesWithoutScheme(): iterable
    {
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->authorityWithoutSchemeValidCombinations as $combination) {
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$queryDelimiter.$this->query,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query,
                false
            );
        }

        foreach ($this->authorityWithoutSchemeInvalidCombinations as $combination) {
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$queryDelimiter.$this->query,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$fragmentDelimiter.$this->fragment,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path,
                false
            );
            yield new FullStringParsedData(
                $authorityDelimiter.$combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query,
                false
            );
        }
    }

    private function getValuesWithoutPath(): iterable
    {
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                '',
                $this->queryNormalized,
                $this->fragmentNormalized,
                $combination->valueNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $combination->valueToParse
                .$fragmentDelimiter.$this->fragment,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                '',
                '',
                $this->fragmentNormalized,
                $combination->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $combination->valueToParse,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                '',
                '',
                '',
                $combination->valueNormalized,
            );
            yield new FullStringParsedData(
                $combination->valueToParse
                .$queryDelimiter.$this->query,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                '',
                $this->queryNormalized,
                '',
                $combination->valueNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$queryDelimiter.$this->query
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $combination->valueToParse
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $combination->valueToParse,
                false,
            );
            yield new FullStringParsedData(
                $combination->valueToParse
                .$queryDelimiter.$this->query,
                false,
            );
        }
    }

    private function getValuesWithoutQuery(): iterable
    {
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$fragmentDelimiter.$this->fragment,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                $pathPartsDelimiter.$this->pathNormalized,
                '',
                $this->fragmentNormalized,
                $combination->valueNormalized
                .$pathPartsDelimiter.$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                $pathPartsDelimiter.$this->pathNormalized,
                '',
                '',
                $combination->valueNormalized
                .$pathPartsDelimiter.$this->pathNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$fragmentDelimiter.$this->fragment,
                false,
            );
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path,
                false,
            );
        }
    }

    private function getValuesWithoutFragment(): iterable
    {
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query,
                true,
                $combination->scheme,
                $combination->userInfo,
                $combination->host,
                $combination->port,
                $combination->authority,
                $pathPartsDelimiter.$this->pathNormalized,
                $this->queryNormalized,
                '',
                $combination->valueNormalized
                .$pathPartsDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringParsedData(
                $combination->valueToParse
                .$pathPartsDelimiter.$this->path
                .$queryDelimiter.$this->query,
                false,
            );
        }
    }
}
